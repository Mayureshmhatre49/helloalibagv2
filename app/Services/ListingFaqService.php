<?php

namespace App\Services;

use App\Models\Listing;

/**
 * Generates context-aware FAQs for a Listing.
 *
 * Each FAQ is an associative array with `q` (question) and `a` (answer).
 * Returned as an indexed array — order matters for both the visible
 * accordion and the FAQPage JSON-LD schema.
 */
class ListingFaqService
{
    // Approximate driving distances (km) from Alibaug-area centroids to Mandwa jetty.
    // Used to give "how far from Mumbai ferry" answers some specificity.
    private const array DISTANCE_FROM_MANDWA_KM = [
        'mandwa' => 1,
        'kihim' => 7,
        'sasawane' => 5,
        'awas' => 12,
        'zirad' => 10,
        'versoli' => 14,
        'alibaug-town' => 17,
        'nagaon' => 22,
        'dhokawade' => 25,
        'kashid' => 38,
    ];

    public function forListing(Listing $listing): array
    {
        $listing->loadMissing(['amenities:id,name,icon', 'area:id,name,slug', 'category:id,name,slug']);

        $faqs = [];
        $faqs = array_merge($faqs, $this->categoryFaqs($listing));
        $faqs = array_merge($faqs, $this->amenityFaqs($listing));
        $faqs = array_merge($faqs, $this->locationFaqs($listing));
        $faqs = array_merge($faqs, $this->bookingFaqs($listing));

        // Cap at 8 — too many FAQs dilutes SEO and harms UX.
        return array_slice($faqs, 0, 8);
    }

    private function categoryFaqs(Listing $listing): array
    {
        $cat = $listing->category?->slug;
        $title = $listing->title;
        $out = [];

        if ($cat === 'stay') {
            if ($listing->price) {
                $out[] = [
                    'q' => "How much does {$title} cost per night?",
                    'a' => "{$title} is priced from ₹" . number_format((float) $listing->price, 0) . " per night. Pricing varies by season, length of stay, and group size — confirm the final rate when you send your inquiry.",
                ];
            }
            $guests = $listing->listingAttributes?->firstWhere('attribute_key', 'guests')?->attribute_value
                ?? $listing->listingAttributes?->firstWhere('attribute_key', 'max_guests')?->attribute_value;
            $bedrooms = $listing->listingAttributes?->firstWhere('attribute_key', 'bedrooms')?->attribute_value;
            if ($guests || $bedrooms) {
                $parts = [];
                if ($bedrooms) {
                    $parts[] = "{$bedrooms} bedroom" . ($bedrooms !== '1' ? 's' : '');
                }
                if ($guests) {
                    $parts[] = "comfortably hosts up to {$guests} guests";
                }
                $out[] = [
                    'q' => "How many people can stay at {$title}?",
                    'a' => "{$title} has " . implode(' and ', $parts) . '. Larger groups can sometimes be accommodated by arrangement — ask the host directly.',
                ];
            }
        } elseif ($cat === 'eat') {
            $out[] = [
                'q' => "Do I need to book a table at {$title}?",
                'a' => "Yes — for groups of 4 or more, and on weekends, we strongly recommend booking ahead. Use the inquiry form to share your preferred date, time and party size.",
            ];
            $cuisine = $listing->listingAttributes?->firstWhere('attribute_key', 'cuisine')?->attribute_value;
            if ($cuisine) {
                $out[] = [
                    'q' => "What cuisine does {$title} serve?",
                    'a' => "{$title} specialises in {$cuisine} cuisine. Menus vary seasonally and based on the produce of the day — confirm specific dishes with the team when you book.",
                ];
            }
        } elseif ($cat === 'explore') {
            if ($listing->price) {
                $out[] = [
                    'q' => "How much does {$title} cost?",
                    'a' => 'Starting at ₹' . number_format((float) $listing->price, 0) . ' per person. Group rates may be available — send an inquiry for exact pricing.',
                ];
            }
            $out[] = [
                'q' => "What's the best time to book {$title}?",
                'a' => 'Book 3–7 days in advance for weekends and holidays. Weekday slots are usually available with shorter notice. Monsoon-sensitive activities (watersports, boat trips) may be paused June–September.',
            ];
        } elseif ($cat === 'events') {
            $out[] = [
                'q' => "Is {$title} available on my preferred date?",
                'a' => 'Availability changes weekly — especially around weekends and festivals. Use the inquiry form with your preferred date and we\'ll confirm directly with the organiser.',
            ];
        } elseif ($cat === 'real-estate') {
            $out[] = [
                'q' => "Is {$title} ready to move in or under construction?",
                'a' => 'Status varies by unit. Send an inquiry and the listing agent will share the current construction phase, possession timeline, and any registration documents.',
            ];
            if ($listing->price) {
                $out[] = [
                    'q' => "What's the price of {$title}?",
                    'a' => 'Quoted from ₹' . number_format((float) $listing->price, 0) . '. Final pricing depends on the specific unit, payment plan and any negotiated additions — confirm with the listing agent.',
                ];
            }
        }

        return $out;
    }

    private function amenityFaqs(Listing $listing): array
    {
        if ($listing->amenities->isEmpty()) {
            return [];
        }

        $amenityNames = $listing->amenities->pluck('name')->map(fn ($n) => strtolower($n))->all();
        $title = $listing->title;
        $out = [];

        if (\in_array('pool', $amenityNames, true)) {
            $out[] = [
                'q' => "Does {$title} have a swimming pool?",
                'a' => "Yes — {$title} has a private swimming pool. Pool dimensions, depth and access hours are confirmed at the time of booking.",
            ];
        }

        if (\in_array('pet friendly', $amenityNames, true)) {
            $out[] = [
                'q' => "Is {$title} pet-friendly?",
                'a' => "Yes — {$title} welcomes pets. Please mention the type and size of pet when you send your inquiry so the host can prepare appropriately.",
            ];
        } elseif ($listing->category?->slug === 'stay') {
            $out[] = [
                'q' => "Are pets allowed at {$title}?",
                'a' => "Pets are not listed as a standard amenity at {$title}. If you'd like to bring a pet, please ask the host directly — some properties allow it by arrangement.",
            ];
        }

        if (\in_array('free parking', $amenityNames, true)) {
            $out[] = [
                'q' => "Is parking available at {$title}?",
                'a' => "Yes — free on-site parking is included. Confirm vehicle count and any larger-vehicle considerations (SUVs, tempos) with the host beforehand.",
            ];
        }

        return $out;
    }

    private function locationFaqs(Listing $listing): array
    {
        $area = $listing->area;
        if (!$area) {
            return [];
        }

        $title = $listing->title;
        $distance = self::DISTANCE_FROM_MANDWA_KM[$area->slug] ?? null;

        $out = [];

        if ($distance !== null) {
            $time = $distance <= 5 ? '10–15 minutes' : ($distance <= 15 ? '20–30 minutes' : ($distance <= 25 ? '40–55 minutes' : 'roughly 1 hour'));
            $out[] = [
                'q' => "How far is {$title} from the Mandwa ferry jetty?",
                'a' => "{$title} is in {$area->name}, about {$distance} km from Mandwa jetty — roughly {$time} by car or auto, depending on traffic.",
            ];
        }

        $out[] = [
            'q' => "Where exactly is {$title} located?",
            'a' => "{$title} is located in {$area->name}, Alibaug, Maharashtra. Exact address and detailed driving directions are shared once your inquiry is confirmed.",
        ];

        return $out;
    }

    private function bookingFaqs(Listing $listing): array
    {
        $title = $listing->title;

        return [
            [
                'q' => "How do I book {$title}?",
                'a' => 'Use the inquiry form on this page — share your dates, group size, and any specific requirements. The host typically replies within 24 hours with availability and a final quote.',
            ],
            [
                'q' => "Is {$title} a verified listing?",
                'a' => "Yes — every listing on Hello Alibaug is reviewed and approved before going live. Pricing and amenities are kept up to date by the host directly.",
            ],
        ];
    }
}
