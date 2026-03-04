<?php

namespace Database\Seeders;

use App\Models\Amenity;
use App\Models\Area;
use App\Models\Category;
use App\Models\Listing;
use App\Models\ListingAttribute;
use App\Models\ListingImage;
use App\Models\Review;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks for clean seeding
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Review::truncate();
        ListingImage::truncate();
        ListingAttribute::truncate();
        DB::table('listing_amenity')->truncate();
        Listing::truncate();
        Amenity::truncate();
        Area::truncate();
        Category::truncate();
        User::truncate();
        Role::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Roles
        $admin = Role::create(['name' => 'Admin', 'slug' => 'admin']);
        $owner = Role::create(['name' => 'Owner', 'slug' => 'owner']);
        $user = Role::create(['name' => 'User', 'slug' => 'user']);

        // Admin user
        $adminUser = User::create([
            'name' => 'Admin',
            'email' => 'admin@helloalibaug.com',
            'password' => Hash::make('password'),
            'role_id' => $admin->id,
        ]);

        // Owner user
        $ownerUser = User::create([
            'name' => 'Rajesh Mehta',
            'email' => 'owner@helloalibaug.com',
            'password' => Hash::make('password'),
            'role_id' => $owner->id,
            'phone' => '9876543210',
        ]);

        // Regular user
        $regularUser = User::create([
            'name' => 'Priya Singh',
            'email' => 'user@helloalibaug.com',
            'password' => Hash::make('password'),
            'role_id' => $user->id,
        ]);

        // Categories
        $categoriesData = [
            ['name' => 'Stay', 'slug' => 'stay', 'icon' => 'villa', 'description' => 'Luxury villas, hotels, and vacation rentals', 'sort_order' => 1],
            ['name' => 'Eat', 'slug' => 'eat', 'icon' => 'restaurant', 'description' => 'Restaurants, cafes, and dining experiences', 'sort_order' => 2],
            ['name' => 'Events', 'slug' => 'events', 'icon' => 'celebration', 'description' => 'Events, parties, and celebrations', 'sort_order' => 3],
            ['name' => 'Explore', 'slug' => 'explore', 'icon' => 'explore', 'description' => 'Activities, tours, and experiences', 'sort_order' => 4],
            ['name' => 'Services', 'slug' => 'services', 'icon' => 'concierge', 'description' => 'Local services and concierge', 'sort_order' => 5],
            ['name' => 'Real Estate', 'slug' => 'real-estate', 'icon' => 'real_estate_agent', 'description' => 'Property sales, land, and investments', 'sort_order' => 6],
        ];
        foreach ($categoriesData as $cat) {
            Category::create($cat);
        }

        $stay = Category::where('slug', 'stay')->first();
        $eat = Category::where('slug', 'eat')->first();
        $realEstate = Category::where('slug', 'real-estate')->first();

        // Areas
        $areasData = [
            ['name' => 'Mandwa', 'slug' => 'mandwa', 'tagline' => 'The gateway to luxury living'],
            ['name' => 'Kihim', 'slug' => 'kihim', 'tagline' => 'Quiet beaches & green lanes'],
            ['name' => 'Alibaug Town', 'slug' => 'alibaug-town', 'tagline' => 'Culture, history & markets'],
            ['name' => 'Awas', 'slug' => 'awas', 'tagline' => 'Exclusive estates & privacy'],
            ['name' => 'Nagaon', 'slug' => 'nagaon', 'tagline' => 'Vibrant beach life'],
            ['name' => 'Versoli', 'slug' => 'versoli', 'tagline' => 'Coastal charm & sunsets'],
            ['name' => 'Zirad', 'slug' => 'zirad', 'tagline' => 'Luxury retreats & estates'],
            ['name' => 'Kashid', 'slug' => 'kashid', 'tagline' => 'White sands & blue waters'],
            ['name' => 'Sasawane', 'slug' => 'sasawane', 'tagline' => 'Beachfront serenity'],
        ];
        foreach ($areasData as $area) {
            Area::create($area);
        }

        // Amenities
        $amenitiesData = [
            ['name' => 'Pool', 'icon' => 'pool', 'category' => 'outdoor'],
            ['name' => 'WiFi', 'icon' => 'wifi', 'category' => 'essential'],
            ['name' => 'Pet Friendly', 'icon' => 'pets', 'category' => 'policy'],
            ['name' => 'AC', 'icon' => 'ac_unit', 'category' => 'essential'],
            ['name' => 'Kitchen', 'icon' => 'soup_kitchen', 'category' => 'indoor'],
            ['name' => 'Beach View', 'icon' => 'beach_access', 'category' => 'outdoor'],
            ['name' => 'Free Parking', 'icon' => 'directions_car', 'category' => 'essential'],
            ['name' => 'BBQ Grill', 'icon' => 'outdoor_grill', 'category' => 'outdoor'],
            ['name' => 'Jacuzzi', 'icon' => 'hot_tub', 'category' => 'outdoor'],
            ['name' => 'Smart TV', 'icon' => 'tv', 'category' => 'indoor'],
            ['name' => 'Washer & Dryer', 'icon' => 'local_laundry_service', 'category' => 'indoor'],
            ['name' => 'Garden', 'icon' => 'forest', 'category' => 'outdoor'],
            ['name' => 'Gym', 'icon' => 'fitness_center', 'category' => 'indoor'],
            ['name' => 'Sea View', 'icon' => 'waves', 'category' => 'outdoor'],
            ['name' => 'Balcony', 'icon' => 'balcony', 'category' => 'outdoor'],
            ['name' => 'Caretaker', 'icon' => 'support_agent', 'category' => 'service'],
        ];
        foreach ($amenitiesData as $idx => $amenity) {
            Amenity::create(array_merge($amenity, ['sort_order' => $idx + 1]));
        }

        // Sample Listings
        $mandwa = Area::where('slug', 'mandwa')->first();
        $kihim = Area::where('slug', 'kihim')->first();
        $versoli = Area::where('slug', 'versoli')->first();
        $awas = Area::where('slug', 'awas')->first();
        $nagaon = Area::where('slug', 'nagaon')->first();
        $alibaugTown = Area::where('slug', 'alibaug-town')->first();
        $kashid = Area::where('slug', 'kashid')->first();

        $listingsData = [
            [
                'title' => 'Villa Azure',
                'category_id' => $stay->id,
                'area_id' => $mandwa->id,
                'description' => 'Escape to Villa Azure, a stunning 5-bedroom beachfront sanctuary.',
                'price' => 25000,
                'status' => 'approved',
                'is_featured' => true,
                'created_by' => $ownerUser->id,
                'approved_by' => $adminUser->id,
                'approved_at' => now(),
                'views_count' => 342,
                'address' => 'Mandwa Beach Rd',
                'phone' => '9876543210',
                'attrs' => ['bedrooms' => '5', 'bathrooms' => '6', 'guests' => '12', 'property_type' => 'Villa'],
                'amenity_list' => ['Pool', 'WiFi', 'AC', 'Kitchen', 'Free Parking', 'BBQ Grill', 'Pet Friendly', 'Smart TV'],
                'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBHuPW2nZ9CBpHnmANOp3SIsfglCpDQtDCmFYFnp1ceTHd2LRTRS1pI-yd_l475PBGmdJkkmKGmUi9QyQwUwo9Ysa7qC2Z04BwqDEub9kDNOjt7OMBpxwgMBXrHGgAOgO8LrVfVkd37NQlBkgvcurMYfZqOJAr5p1eELeZceudlPcU6Gc7EpN5vNp0nfro8OoQs_SNLqIOm_bHmclKb9ZH7sCMcvrMtYtD9GGVJvnSq-Hgn6VeQA5c-jadtjNWXIxOUzztFHpTg_28',
            ],
            [
                'title' => 'The Mango Grove',
                'category_id' => $stay->id,
                'area_id' => $kihim->id,
                'description' => 'A charming farmhouse surrounded by mango orchards.',
                'price' => 12500,
                'status' => 'approved',
                'is_featured' => true,
                'created_by' => $ownerUser->id,
                'approved_by' => $adminUser->id,
                'approved_at' => now(),
                'views_count' => 215,
                'attrs' => ['bedrooms' => '3', 'bathrooms' => '3', 'guests' => '6', 'property_type' => 'Farmhouse'],
                'amenity_list' => ['WiFi', 'Kitchen', 'BBQ Grill', 'Garden', 'Free Parking'],
                'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCIo0ULYWj_eek0qA_uDZdHCOxQNJrg_dlJX0YIZyw7HMvrLwoG7PedXbuoGGdY9CIua4HE22aLOUP6b5SXPWLmRbMhPYey7k_LG6aOtvJS8_ITKgP_IxC-F_6qZKT6GEH6d7ZuLzc_FkXgoPF86wftOYJjcvP4a9YTfmPMlI-s0ebfTLf34v6Ye5XInJ8KGk8trOx2hCib8P_fG19WufWfCO576tHAnYKsS3ESMHXVw6crxFQhb8ihmC3rBTLo46035CMVZ28CetI',
            ],
            [
                'title' => 'Seaview Penthouse',
                'category_id' => $stay->id,
                'area_id' => $versoli->id,
                'description' => 'A stunning penthouse with panoramic sea views.',
                'price' => 32000,
                'status' => 'approved',
                'is_featured' => true,
                'created_by' => $ownerUser->id,
                'approved_by' => $adminUser->id,
                'approved_at' => now(),
                'views_count' => 189,
                'attrs' => ['bedrooms' => '3', 'bathrooms' => '3', 'guests' => '6', 'property_type' => 'Apartment'],
                'amenity_list' => ['WiFi', 'AC', 'Sea View', 'Balcony', 'Smart TV'],
                'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAzRFo4Flj9MkSFgxeImqBdv5cMB0M__mXX4TKhvC0g4oTw5Mefpg2wVdHGvV2A6u7rr0MRPjYvjrh8_IqS5gAQxNdRpfvUtkMj_vlpBR0VdDZXKsau_MVnkMafQychYvqNiniQgwy6rAKbQb1Ov1YKNUF10_hKL2xh-FPi2Ps90WEzO4eFXWtpg8RYmeDrCEoEURREGmBOFx_VNNbNfbGzp0djSpQyFToRJ7227KrO68sF0aNpzdKx6P6XmoxrQ40dMxInSZJrSYs',
            ],
            [
                'title' => 'Palm Retreat',
                'category_id' => $stay->id,
                'area_id' => $awas->id,
                'description' => 'Traditional Konkan-style villa with palm-lined pathways.',
                'price' => 18000,
                'status' => 'approved',
                'is_featured' => true,
                'created_by' => $ownerUser->id,
                'approved_by' => $adminUser->id,
                'approved_at' => now(),
                'views_count' => 168,
                'attrs' => ['bedrooms' => '4', 'bathrooms' => '4', 'guests' => '8', 'property_type' => 'Villa'],
                'amenity_list' => ['Pool', 'WiFi', 'AC', 'Garden', 'Caretaker'],
                'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBbcHrCzL9lZfveWPRONE2buEfJQCofxnJ9M9tV6VXXLIVLE8PhTkurcTR8zL2_o6I8Pg9FWw6gPeCNh8N8m_qD8kTdSANU8UKpFxjzUZjcq6HQnXhmooj5R7E2xooWdOw5QB9ZdoLMFnMYaAGn1GwE1bQaJPWOjUKrnznCFLt8J5DWH-gO71PtqzbodmyDp7CGy0Yjoi497gbk8jUam7hqN5bGZzmPxzT3-Crr5ICyEVLCgzgOIOW70ZlacmPM9EV_G6OgvB11xdo',
            ],
            [
                'title' => 'Coastal Retreat Bungalow',
                'category_id' => $stay->id,
                'area_id' => $nagaon->id,
                'description' => 'Modern coastal bungalow near Nagaon beach.',
                'price' => 18500,
                'status' => 'pending',
                'is_featured' => false,
                'created_by' => $ownerUser->id,
                'views_count' => 0,
                'attrs' => ['bedrooms' => '3', 'bathrooms' => '3', 'guests' => '6', 'property_type' => 'Bungalow'],
                'amenity_list' => ['WiFi', 'AC', 'Kitchen', 'Free Parking'],
                'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuB99YIaFPHQJygML6KW0WN6o008rf_ea0utDaigJpWV6h695ireIHvFT-UAPi9jL1YU68Vwxpxk1EesQoOdV4ydlEW5CUTJQBO3s9MCOfbPVRtOGRKI6T6v7DaGLRKxz3eRtN_SzRC9jHowXVZH60xGyuEg85lOc1mKh-RRw--0SXN0XGX6Tw4xRGvYwdPlq7sGxa_Y92Zh56B9YZ6J2D72dNb8RJRUZu54DwcRrirkiCQPB-VMZRA9Va0rJtP-D54Pf8nGq6tmUSk',
            ],
            [
                'title' => 'Best Seafood Restaurant Alibaug',
                'category_id' => $eat->id,
                'area_id' => $alibaugTown->id,
                'description' => 'Authentic Konkan seafood with ocean views.',
                'price' => null,
                'status' => 'approved',
                'is_featured' => true,
                'created_by' => $ownerUser->id,
                'approved_by' => $adminUser->id,
                'approved_at' => now(),
                'views_count' => 456,
                'attrs' => ['cuisine' => 'Seafood', 'seating_capacity' => '120'],
                'amenity_list' => ['WiFi', 'AC', 'Free Parking'],
                'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuATb9RqGRLNFwUnQkWGl-9So1gvmChn9lIU5-O8ax4n6CwjvO5_9BVjLpG7-OWLHtD3WzXUsUMn6WrUw8jgAKWUhGwW63c_ctTOT-HiYF5llGQd7rhuC0VPDEO59P22jRNnr96kagM51UHylZHGsWreEzgPzIudqafKUyGFAPmk1tfzLRtxdBYXHSv5MERRU2kkgbbffH7CYp0EOiDP_9x_LKgbKY48QuSWD2PNxaYksGkz9N92V3K3Cz7PhEIAqibMH57qGdTKPEc',
            ],
            [
                'title' => 'Premium Plot in Kashid',
                'category_id' => $realEstate->id,
                'area_id' => $kashid->id,
                'description' => 'Prime 5000 sqft plot close to Kashid beach.',
                'price' => 7500000,
                'status' => 'approved',
                'is_featured' => false,
                'is_premium' => true,
                'created_by' => $ownerUser->id,
                'approved_by' => $adminUser->id,
                'approved_at' => now(),
                'views_count' => 89,
                'attrs' => ['plot_area' => '5000 sqft', 'property_type' => 'Plot'],
                'amenity_list' => [],
                'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAoq5HSGj_rGnnQYNWExlk-xpJUoKlh3Tmelol2EetYt_1sDRKSajXt5Lz_jBTL1CPg3HIvDLucc8NZLRyuDMBydhyQjpndFIulabcVxhT_1BckU8fisVquejmuRN1SmFmRZhyM-HJxCwT-L3VbJ3BJbpnyu_n7HCLTghJ1hzzsAO7ZU7MUum58W524kxv1S3pkTTNzdmZuY33E2GNII8hE5UIi8vx9g0ehCrnyhTIS6XNzVcJqquC59PmqvxaYSCo8Uh64xMJlgY4',
            ],
        ];

        foreach ($listingsData as $data) {
            $attrs = $data['attrs'] ?? [];
            $amenityList = $data['amenity_list'] ?? [];
            $imageUrl = $data['image'] ?? null;
            unset($data['attrs'], $data['amenity_list'], $data['image']);

            $listing = Listing::create($data);

            foreach ($attrs as $key => $value) {
                ListingAttribute::create([
                    'listing_id' => $listing->id,
                    'attribute_key' => $key,
                    'attribute_value' => $value,
                ]);
            }

            if (!empty($amenityList)) {
                $amenityIds = Amenity::whereIn('name', $amenityList)->pluck('id')->toArray();
                $listing->amenities()->sync($amenityIds);
            }

            if ($imageUrl) {
                ListingImage::create([
                    'listing_id' => $listing->id,
                    'path' => $imageUrl,
                    'alt_text' => $listing->title,
                    'sort_order' => 0,
                    'is_primary' => true,
                ]);
            }
        }

        // Sample Reviews
        $approvedListings = Listing::where('status', 'approved')->take(4)->get();

        foreach ($approvedListings as $listing) {
            Review::create([
                'listing_id' => $listing->id,
                'user_id' => $regularUser->id,
                'rating' => 5,
                'comment' => 'Absolutely stunning. Highly recommend!',
                'status' => 'approved',
            ]);
            Review::create([
                'listing_id' => $listing->id,
                'user_id' => $ownerUser->id,
                'rating' => 4,
                'comment' => 'A perfect slice of paradise near Mumbai.',
                'status' => 'approved',
            ]);
        }
    }
}
