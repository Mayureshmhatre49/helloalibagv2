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
        $user  = Role::create(['name' => 'User',  'slug' => 'user']);

        // Admin user
        $adminUser = User::create([
            'name'     => 'Admin',
            'email'    => 'admin@helloalibaug.com',
            'password' => Hash::make('Admin@123'),
            'role_id'  => $admin->id,
        ]);

        // Owner user — Nishat Mhatre (admin)
        $ownerUser = User::create([
            'name'     => 'Nishat Mhatre',
            'email'    => 'owner@helloalibaug.com',
            'password' => Hash::make('Owner@123'),
            'role_id'  => $admin->id,   // admin role as requested
            'phone'    => '9082804115',
        ]);

        // Regular user
        $regularUser = User::create([
            'name'     => 'Priya Singh',
            'email'    => 'user@helloalibaug.com',
            'password' => Hash::make('User@123'),
            'role_id'  => $user->id,
        ]);

        // Categories
        $categoriesData = [
            ['name' => 'Stay',        'slug' => 'stay',        'icon' => 'villa',              'description' => 'Luxury villas, hotels, and vacation rentals',  'sort_order' => 1],
            ['name' => 'Eat',         'slug' => 'eat',         'icon' => 'restaurant',         'description' => 'Restaurants, cafes, and dining experiences',    'sort_order' => 2],
            ['name' => 'Events',      'slug' => 'events',      'icon' => 'celebration',        'description' => 'Events, parties, and celebrations',             'sort_order' => 3],
            ['name' => 'Explore',     'slug' => 'explore',     'icon' => 'explore',            'description' => 'Activities, tours, and experiences',            'sort_order' => 4],
            ['name' => 'Services',    'slug' => 'services',    'icon' => 'concierge',          'description' => 'Local services and concierge',                  'sort_order' => 5],
            ['name' => 'Real Estate', 'slug' => 'real-estate', 'icon' => 'real_estate_agent',  'description' => 'Property sales, land, and investments',         'sort_order' => 6],
        ];
        foreach ($categoriesData as $cat) {
            Category::create($cat);
        }

        $stay = Category::where('slug', 'stay')->first();
        $eat  = Category::where('slug', 'eat')->first();

        // Areas
        $areasData = [
            ['name' => 'Mandwa',       'slug' => 'mandwa',       'tagline' => 'The gateway to luxury living'],
            ['name' => 'Kihim',        'slug' => 'kihim',        'tagline' => 'Quiet beaches & green lanes'],
            ['name' => 'Alibaug Town', 'slug' => 'alibaug-town', 'tagline' => 'Culture, history & markets'],
            ['name' => 'Awas',         'slug' => 'awas',         'tagline' => 'Exclusive estates & privacy'],
            ['name' => 'Nagaon',       'slug' => 'nagaon',       'tagline' => 'Vibrant beach life'],
            ['name' => 'Versoli',      'slug' => 'versoli',      'tagline' => 'Coastal charm & sunsets'],
            ['name' => 'Zirad',        'slug' => 'zirad',        'tagline' => 'Luxury retreats & estates'],
            ['name' => 'Kashid',       'slug' => 'kashid',       'tagline' => 'White sands & blue waters'],
            ['name' => 'Sasawane',     'slug' => 'sasawane',     'tagline' => 'Beachfront serenity'],
            ['name' => 'Dhokawade',    'slug' => 'dhokawade',    'tagline' => 'Village charm & farm flavours'],
        ];
        foreach ($areasData as $area) {
            Area::create($area);
        }

        $alibaugTown = Area::where('slug', 'alibaug-town')->first();
        $dhokawade   = Area::where('slug', 'dhokawade')->first();

        // Amenities
        $amenitiesData = [
            ['name' => 'Pool',            'icon' => 'pool',                   'category' => 'outdoor'],
            ['name' => 'WiFi',            'icon' => 'wifi',                   'category' => 'essential'],
            ['name' => 'Pet Friendly',    'icon' => 'pets',                   'category' => 'policy'],
            ['name' => 'AC',              'icon' => 'ac_unit',                'category' => 'essential'],
            ['name' => 'Kitchen',         'icon' => 'soup_kitchen',           'category' => 'indoor'],
            ['name' => 'Beach View',      'icon' => 'beach_access',           'category' => 'outdoor'],
            ['name' => 'Free Parking',    'icon' => 'directions_car',         'category' => 'essential'],
            ['name' => 'BBQ Grill',       'icon' => 'outdoor_grill',          'category' => 'outdoor'],
            ['name' => 'Jacuzzi',         'icon' => 'hot_tub',                'category' => 'outdoor'],
            ['name' => 'Smart TV',        'icon' => 'tv',                     'category' => 'indoor'],
            ['name' => 'Washer & Dryer',  'icon' => 'local_laundry_service',  'category' => 'indoor'],
            ['name' => 'Garden',          'icon' => 'forest',                 'category' => 'outdoor'],
            ['name' => 'Gym',             'icon' => 'fitness_center',         'category' => 'indoor'],
            ['name' => 'Sea View',        'icon' => 'waves',                  'category' => 'outdoor'],
            ['name' => 'Balcony',         'icon' => 'balcony',                'category' => 'outdoor'],
            ['name' => 'Caretaker',       'icon' => 'support_agent',          'category' => 'service'],
            ['name' => 'Meals Included',  'icon' => 'restaurant_menu',        'category' => 'service'],
        ];
        foreach ($amenitiesData as $idx => $amenity) {
            Amenity::create(array_merge($amenity, ['sort_order' => $idx + 1]));
        }

        // ─────────────────────────────────────────────
        // LISTING 1 — Paisley Experience (Eat / Dining)
        // Website: https://www.paisleyexperience.com/
        // ─────────────────────────────────────────────
        $paisley = Listing::create([
            'title'       => 'Paisley Experience',
            'category_id' => $eat->id,
            'area_id'     => $dhokawade->id,
            'description' => 'Paisley Experience offers bespoke catering and farm dining in Alibaug. Enjoy heirloom Pachkalshi flavours, private sit-down meals for small groups, and menus crafted the traditional way. Choose from à la carte or signature taats — generous, seasonal plates made fresh to order. Perfect for intimate dining or tailored catering at your special occasions.',
            'price'       => null,
            'status'      => 'approved',
            'is_featured' => true,
            'created_by'  => $ownerUser->id,
            'approved_by' => $adminUser->id,
            'approved_at' => now(),
            'views_count' => 312,
            'address'     => 'Dhokawade 1, Alibaug 402201',
            'phone'       => '9082804115',
            'website'     => 'https://www.paisleyexperience.com/',
        ]);

        foreach ([
            'cuisine'          => 'Maharashtrian / Konkan',
            'seating_capacity' => '30',
            'meal_type'        => 'Farm Dining & Catering',
        ] as $key => $value) {
            ListingAttribute::create(['listing_id' => $paisley->id, 'attribute_key' => $key, 'attribute_value' => $value]);
        }

        $amenityIds = Amenity::whereIn('name', ['WiFi', 'Free Parking', 'Meals Included'])->pluck('id')->toArray();
        $paisley->amenities()->sync($amenityIds);

        // Real images from www.paisleyexperience.com (Wix CDN)
        $paisleyImages = [
            [
                'path'       => 'https://static.wixstatic.com/media/98bf86_2a434a1cb25b43429a15435fbaf62311~mv2.jpg/v1/crop/x_0,y_173,w_1080,h_696/fill/w_1002,h_662,al_c,q_85,usm_0.66_1.00_0.01,enc_avif,quality_auto/Taat.jpg',
                'alt_text'   => 'Paisley Experience – Signature Taat Meal',
                'is_primary' => true,
                'sort_order' => 0,
            ],
            [
                'path'       => 'https://static.wixstatic.com/media/98bf86_dfaaed3f117143bc82300644d523e98b~mv2.jpg/v1/fill/w_1000,h_640,al_c,q_85,usm_0.66_1.00_0.01,enc_avif,quality_auto/Catering_edited.jpg',
                'alt_text'   => 'Paisley Experience – Catering Setup',
                'is_primary' => false,
                'sort_order' => 1,
            ],
            [
                'path'       => 'https://static.wixstatic.com/media/98bf86_7592667b75f54a0c91405ab30cbd16fc~mv2.jpg/v1/crop/x_0,y_10,w_768,h_492/fill/w_768,h_492,al_c,q_85,enc_avif,quality_auto/Experiences.jpg',
                'alt_text'   => 'Paisley Experience – Cultural Experiences',
                'is_primary' => false,
                'sort_order' => 2,
            ],
        ];
        foreach ($paisleyImages as $img) {
            ListingImage::create(array_merge(['listing_id' => $paisley->id], $img));
        }

        // ─────────────────────────────────────────────
        // LISTING 2 — Hestia Villa (Stay)
        // Website: https://hestiavillas.in/
        // ─────────────────────────────────────────────
        $hestia = Listing::create([
            'title'       => 'Hestia Villa',
            'category_id' => $stay->id,
            'area_id'     => $dhokawade->id,
            'description' => 'Discover intelligent villas and refined interiors with Hestia Villas. Bespoke design, sustainable architecture, and premium second-home investments near Mumbai. Hestia specialises in thoughtfully designed, sustainable villas connected to nature while delivering enduring lifestyle value. Every villa is crafted with an uncompromising focus on build quality, longevity, and livability.',
            'price'       => 35000,
            'status'      => 'approved',
            'is_featured' => true,
            'created_by'  => $ownerUser->id,
            'approved_by' => $adminUser->id,
            'approved_at' => now(),
            'views_count' => 278,
            'address'     => 'At. Post. Dhokawade, Alibag, Raigad, Maharashtra 402201',
            'phone'       => '8010234802',
            'website'     => 'https://hestiavillas.in/',
        ]);

        foreach ([
            'property_type' => 'Villa',
            'bedrooms'      => '3-6',
            'bathrooms'     => '4-7',
            'guests'        => 'Up to 18',
            'architect'     => 'Hestia Villas',
        ] as $key => $value) {
            ListingAttribute::create(['listing_id' => $hestia->id, 'attribute_key' => $key, 'attribute_value' => $value]);
        }

        $amenityIds = Amenity::whereIn('name', ['Pool', 'WiFi', 'AC', 'Kitchen', 'Garden', 'Caretaker', 'Free Parking', 'Smart TV'])->pluck('id')->toArray();
        $hestia->amenities()->sync($amenityIds);

        // Real images from hestiavillas.in
        $hestiaImages = [
            [
                'path'       => 'https://hestiavillas.in/wp-content/uploads/2022/02/Picture1-2-1024x562.jpg',
                'alt_text'   => 'Hestia Villa – Exterior View',
                'is_primary' => true,
                'sort_order' => 0,
            ],
            [
                'path'       => 'https://hestiavillas.in/wp-content/uploads/2023/07/4.jpg',
                'alt_text'   => 'Hestia Villa – Interior Space',
                'is_primary' => false,
                'sort_order' => 1,
            ],
            [
                'path'       => 'https://hestiavillas.in/wp-content/uploads/2022/02/image10-e1676548129722.jpeg',
                'alt_text'   => 'Hestia Villa – Outdoor Living Area',
                'is_primary' => false,
                'sort_order' => 2,
            ],
        ];
        foreach ($hestiaImages as $img) {
            ListingImage::create(array_merge(['listing_id' => $hestia->id], $img));
        }

        // ─────────────────────────────────────────────
        // LISTING 3 — Casa Frangipani (Stay)
        // Website: https://hestiavillas.in/case_study/casa-frangipani-rent-and-buy/
        // ─────────────────────────────────────────────
        $casaFrangipani = Listing::create([
            'title'       => 'Casa Frangipani',
            'category_id' => $stay->id,
            'area_id'     => $dhokawade->id,
            'description' => 'Casa Frangipani is a luxurious 6-bedroom villa in Alibaug, perfect for those seeking peace and privacy amidst nature. Surrounded by lush greenery, this beautifully designed retreat welcomes you through a grand entrance into airy, sunlit spaces. Crafted with 100-year-old teak wood and glass windows, the villa offers breathtaking views of the verdant landscape. Designed for relaxation — the shimmering pool, open terrace, and BBQ sit-out create the perfect setting for a weekend escape.',
            'price'       => 45000,
            'status'      => 'approved',
            'is_featured' => true,
            'created_by'  => $ownerUser->id,
            'approved_by' => $adminUser->id,
            'approved_at' => now(),
            'views_count' => 520,
            'address'     => 'Dhokawade, Alibag, Raigad, Maharashtra 402201',
            'phone'       => '8010234802',
            'website'     => 'https://hestiavillas.in/case_study/casa-frangipani-rent-and-buy/',
        ]);

        foreach ([
            'property_type' => 'Villa',
            'bedrooms'      => '6',
            'bathrooms'     => '7',
            'guests'        => '18',
            'pool_size'     => '20ft x 40ft',
            'lawn_area'     => '2000 sq. ft',
        ] as $key => $value) {
            ListingAttribute::create(['listing_id' => $casaFrangipani->id, 'attribute_key' => $key, 'attribute_value' => $value]);
        }

        $amenityIds = Amenity::whereIn('name', ['Pool', 'WiFi', 'AC', 'Kitchen', 'Garden', 'BBQ Grill', 'Balcony', 'Caretaker', 'Meals Included', 'Smart TV', 'Free Parking'])->pluck('id')->toArray();
        $casaFrangipani->amenities()->sync($amenityIds);

        // Real images from hestiavillas.in — Casa Frangipani gallery
        $casaImages = [
            [
                'path'       => 'https://hestiavillas.in/wp-content/uploads/2025/01/casa-frangipani-657c43.webp',
                'alt_text'   => 'Casa Frangipani – Main View',
                'is_primary' => true,
                'sort_order' => 0,
            ],
            [
                'path'       => 'https://hestiavillas.in/wp-content/uploads/2025/01/casa-frangifani1.jpeg',
                'alt_text'   => 'Casa Frangipani – Villa Entrance',
                'is_primary' => false,
                'sort_order' => 1,
            ],
            [
                'path'       => 'https://hestiavillas.in/wp-content/uploads/2025/01/casa-frangifani2.jpeg',
                'alt_text'   => 'Casa Frangipani – Swimming Pool',
                'is_primary' => false,
                'sort_order' => 2,
            ],
            [
                'path'       => 'https://hestiavillas.in/wp-content/uploads/2025/01/casa-frangifani3.jpeg',
                'alt_text'   => 'Casa Frangipani – Bedroom',
                'is_primary' => false,
                'sort_order' => 3,
            ],
            [
                'path'       => 'https://hestiavillas.in/wp-content/uploads/2025/01/casa-frangifani4.jpeg',
                'alt_text'   => 'Casa Frangipani – Living Area',
                'is_primary' => false,
                'sort_order' => 4,
            ],
            [
                'path'       => 'https://hestiavillas.in/wp-content/uploads/2025/01/Lawn-scaled.jpg',
                'alt_text'   => 'Casa Frangipani – Lawn',
                'is_primary' => false,
                'sort_order' => 5,
            ],
            [
                'path'       => 'https://hestiavillas.in/wp-content/uploads/2025/01/casa-frangipani-8b5e9e.webp',
                'alt_text'   => 'Casa Frangipani – Garden View',
                'is_primary' => false,
                'sort_order' => 6,
            ],
        ];
        foreach ($casaImages as $img) {
            ListingImage::create(array_merge(['listing_id' => $casaFrangipani->id], $img));
        }
    }
}
