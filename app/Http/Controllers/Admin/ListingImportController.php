<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Area;
use App\Models\Category;
use App\Models\Listing;
use App\Models\ListingImage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ListingImportController extends Controller
{
    /**
     * Show the CSV import page.
     */
    public function index()
    {
        return view('admin.listings.import');
    }

    /**
     * Process the uploaded CSV and bulk-create listings.
     */
    public function store(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        // Resolve the owner user (Nishat Mhatre) — all imports go under this account
        $owner = User::where('email', 'owner@helloalibaug.com')->first();
        if (!$owner) {
            return back()->with('error', 'Owner account (owner@helloalibaug.com) not found. Please seed the database first.');
        }

        $file     = $request->file('csv_file');
        $handle   = fopen($file->getRealPath(), 'r');
        $headers  = null;
        $imported = 0;
        $skipped  = 0;
        $errors   = [];

        // Pre-load lookups for fast matching
        $categories = Category::pluck('id', 'slug')->toArray();
        $areas      = Area::pluck('id', 'slug')->toArray();
        $amenityMap = Amenity::pluck('id', 'name')->toArray();

        DB::beginTransaction();
        try {
            $rowNumber = 0;
            while (($row = fgetcsv($handle)) !== false) {
                $rowNumber++;

                // First row = headers
                if ($rowNumber === 1) {
                    $headers = array_map('trim', $row);
                    continue;
                }

                // Combine headers with row values
                if (count($headers) !== count($row)) {
                    $errors[] = "Row {$rowNumber}: column count mismatch — skipped.";
                    $skipped++;
                    continue;
                }

                $data = array_combine($headers, array_map('trim', $row));

                // ── Validate required fields ──────────────────────────────────
                $title        = $data['title']         ?? '';
                $categorySlug = $data['category_slug'] ?? '';
                $areaSlug     = $data['area_slug']     ?? '';

                if (empty($title)) {
                    $errors[] = "Row {$rowNumber}: missing 'title' — skipped.";
                    $skipped++;
                    continue;
                }

                if (empty($categorySlug) || !isset($categories[$categorySlug])) {
                    $errors[] = "Row {$rowNumber} ({$title}): unknown category_slug '{$categorySlug}' — skipped.";
                    $skipped++;
                    continue;
                }

                if (empty($areaSlug) || !isset($areas[$areaSlug])) {
                    $errors[] = "Row {$rowNumber} ({$title}): unknown area_slug '{$areaSlug}' — skipped.";
                    $skipped++;
                    continue;
                }

                // ── Build the listing payload ─────────────────────────────────
                $price = null;
                if (!empty($data['price']) && is_numeric($data['price'])) {
                    $price = (float) $data['price'];
                }

                $listing = Listing::create([
                    'title'       => $title,
                    'category_id' => $categories[$categorySlug],
                    'area_id'     => $areas[$areaSlug],
                    'description' => $data['description'] ?? null,
                    'price'       => $price,
                    'address'     => $data['address']  ?? null,
                    'phone'       => $data['phone']    ?? null,
                    'email'       => $data['email']    ?? null,
                    'website'     => $data['website']  ?? null,
                    'whatsapp'    => $data['whatsapp'] ?? null,
                    'status'      => 'pending',     // always pending — Nishat approves
                    'is_featured' => false,
                    'created_by'  => $owner->id,
                    'views_count' => 0,
                ]);

                // ── Amenities ─────────────────────────────────────────────────
                if (!empty($data['amenities'])) {
                    $amenityNames = array_map('trim', explode('|', $data['amenities']));
                    $ids = [];
                    foreach ($amenityNames as $name) {
                        if (isset($amenityMap[$name])) {
                            $ids[] = $amenityMap[$name];
                        }
                    }
                    if (!empty($ids)) {
                        $listing->amenities()->sync($ids);
                    }
                }

                // ── Primary Image ─────────────────────────────────────────────
                if (!empty($data['image_url'])) {
                    ListingImage::create([
                        'listing_id' => $listing->id,
                        'path'       => $data['image_url'],
                        'alt_text'   => $listing->title,
                        'is_primary' => true,
                        'sort_order' => 0,
                    ]);
                }

                $imported++;
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('CSV Import failed: ' . $e->getMessage());
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        } finally {
            fclose($handle);
        }

        $message = "{$imported} listing(s) imported successfully and placed in the pending queue.";
        if ($skipped > 0) {
            $message .= " {$skipped} row(s) were skipped.";
        }

        return back()
            ->with('import_success', $message)
            ->with('import_errors', $errors)
            ->with('imported_count', $imported)
            ->with('skipped_count', $skipped);
    }
}
