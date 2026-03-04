<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CategoryAttribute;
use App\Models\CategoryAttributeValue;
use App\Models\Amenity;
use Illuminate\Database\Seeder;

class CategoryAttributesSeeder extends Seeder
{
    public function run(): void
    {
        // ─── STAY ────────────────────────────────────────────────────────────────
        $stay = Category::where('slug', 'stay')->first();
        if ($stay) {
            CategoryAttribute::where('category_id', $stay->id)->delete();

            $attrs = [
                ['name' => 'Property Type', 'field_type' => 'select', 'is_required' => true, 'sort_order' => 1,
                 'values' => ['Villa', 'Farmhouse', 'Bungalow', 'Cottage', 'Studio Apartment', 'Serviced Apartment', 'Homestay', 'Resort', 'Eco Camp']],
                ['name' => 'Max Guests', 'field_type' => 'number', 'is_required' => true, 'sort_order' => 2],
                ['name' => 'Bedrooms', 'field_type' => 'number', 'is_required' => true, 'sort_order' => 3],
                ['name' => 'Bathrooms', 'field_type' => 'number', 'is_required' => true, 'sort_order' => 4],
                ['name' => 'Pool Type', 'field_type' => 'select', 'is_required' => false, 'sort_order' => 5,
                 'values' => ['Private Pool', 'Shared Pool', 'Infinity Pool', 'No Pool']],
                ['name' => 'View / Setting', 'field_type' => 'select', 'is_required' => false, 'sort_order' => 6,
                 'values' => ['Sea View', 'Garden View', 'Valley View', 'Forest View', 'City View', 'Paddy Field View']],
                ['name' => 'Check-in Time', 'field_type' => 'text', 'is_required' => false, 'sort_order' => 7],
                ['name' => 'Check-out Time', 'field_type' => 'text', 'is_required' => false, 'sort_order' => 8],
                ['name' => 'Minimum Stay (Nights)', 'field_type' => 'number', 'is_required' => false, 'sort_order' => 9],
                ['name' => 'Pricing Per', 'field_type' => 'select', 'is_required' => false, 'sort_order' => 10,
                 'values' => ['Per Night', 'Per Weekend (Fri-Sun)', 'Per Week', 'Per Month']],
            ];

            foreach ($attrs as $a) {
                $values = $a['values'] ?? [];
                unset($a['values']);
                $attr = CategoryAttribute::create(array_merge($a, ['category_id' => $stay->id]));
                foreach ($values as $i => $v) {
                    CategoryAttributeValue::create(['category_attribute_id' => $attr->id, 'value' => $v, 'label' => $v]);
                }
            }

            // Amenities for Stay
            $stayAmenities = [
                ['name' => 'Private Pool', 'icon' => 'pool', 'category' => 'stay'],
                ['name' => 'WiFi', 'icon' => 'wifi', 'category' => 'stay'],
                ['name' => 'Air Conditioning', 'icon' => 'ac_unit', 'category' => 'stay'],
                ['name' => 'Caretaker Included', 'icon' => 'person', 'category' => 'stay'],
                ['name' => 'Fully Equipped Kitchen', 'icon' => 'kitchen', 'category' => 'stay'],
                ['name' => 'BBQ Area', 'icon' => 'outdoor_grill', 'category' => 'stay'],
                ['name' => 'Beach Access', 'icon' => 'beach_access', 'category' => 'stay'],
                ['name' => 'Parking', 'icon' => 'local_parking', 'category' => 'stay'],
                ['name' => 'Generator Backup', 'icon' => 'electrical_services', 'category' => 'stay'],
                ['name' => 'Pet Friendly', 'icon' => 'pets', 'category' => 'stay'],
                ['name' => 'Garden / Lawn', 'icon' => 'yard', 'category' => 'stay'],
                ['name' => 'TV / OTT Access', 'icon' => 'tv', 'category' => 'stay'],
                ['name' => 'Security Camera', 'icon' => 'videocam', 'category' => 'stay'],
                ['name' => 'Washing Machine', 'icon' => 'local_laundry_service', 'category' => 'stay'],
                ['name' => 'Water Sports Nearby', 'icon' => 'surfing', 'category' => 'stay'],
            ];
            foreach ($stayAmenities as $i => $am) {
                Amenity::firstOrCreate(['name' => $am['name']], array_merge($am, ['sort_order' => $i + 1]));
            }
        }

        // ─── EAT ─────────────────────────────────────────────────────────────────
        $eat = Category::where('slug', 'eat')->first();
        if ($eat) {
            CategoryAttribute::where('category_id', $eat->id)->delete();

            $attrs = [
                ['name' => 'Cuisine Type', 'field_type' => 'select', 'is_required' => true, 'sort_order' => 1,
                 'values' => ['Indian', 'Continental', 'Coastal / Seafood', 'Chinese', 'Italian', 'Pan-Asian', 'Multi-Cuisine', 'Street Food', 'Café / Bakery']],
                ['name' => 'Seating Capacity', 'field_type' => 'number', 'is_required' => false, 'sort_order' => 2],
                ['name' => 'Setting', 'field_type' => 'select', 'is_required' => false, 'sort_order' => 3,
                 'values' => ['Indoor', 'Outdoor', 'Rooftop', 'Beach-side', 'Indoor + Outdoor']],
                ['name' => 'Average Cost for Two (₹)', 'field_type' => 'number', 'is_required' => false, 'sort_order' => 4],
                ['name' => 'Opening Hours', 'field_type' => 'text', 'is_required' => false, 'sort_order' => 5],
                ['name' => 'Alcohol Served', 'field_type' => 'select', 'is_required' => false, 'sort_order' => 6,
                 'values' => ['Yes — Full Bar', 'Yes — Beer & Wine Only', 'No']],
            ];

            foreach ($attrs as $a) {
                $values = $a['values'] ?? [];
                unset($a['values']);
                $attr = CategoryAttribute::create(array_merge($a, ['category_id' => $eat->id]));
                foreach ($values as $v) {
                    CategoryAttributeValue::create(['category_attribute_id' => $attr->id, 'value' => $v, 'label' => $v]);
                }
            }

            $eatAmenities = [
                ['name' => 'Outdoor / Terrace Seating', 'icon' => 'deck', 'category' => 'eat'],
                ['name' => 'Live Music', 'icon' => 'music_note', 'category' => 'eat'],
                ['name' => 'Pure Veg', 'icon' => 'eco', 'category' => 'eat'],
                ['name' => 'Accepts Reservations', 'icon' => 'event_seat', 'category' => 'eat'],
                ['name' => 'Home Delivery', 'icon' => 'delivery_dining', 'category' => 'eat'],
                ['name' => 'Takeaway', 'icon' => 'takeout_dining', 'category' => 'eat'],
                ['name' => 'Cards Accepted', 'icon' => 'credit_card', 'category' => 'eat'],
                ['name' => 'Family Friendly', 'icon' => 'family_restroom', 'category' => 'eat'],
                ['name' => 'Sea View', 'icon' => 'waves', 'category' => 'eat'],
            ];
            foreach ($eatAmenities as $i => $am) {
                Amenity::firstOrCreate(['name' => $am['name']], array_merge($am, ['sort_order' => $i + 1]));
            }
        }

        // ─── Experience / Events / Services ─────────────────────────────────────
        foreach (['experience', 'events', 'services', 'real-estate', 'transport'] as $slug) {
            $cat = Category::where('slug', $slug)->first();
            if (!$cat) continue;

            CategoryAttribute::where('category_id', $cat->id)->delete();

            $commonAttrs = match($slug) {
                'real-estate' => [
                    ['name' => 'Property Type', 'field_type' => 'select', 'is_required' => true, 'sort_order' => 1,
                     'values' => ['Villa', 'Plot', 'Apartment', 'Farmhouse', 'Commercial Space']],
                    ['name' => 'Area (sq ft)', 'field_type' => 'number', 'is_required' => false, 'sort_order' => 2],
                    ['name' => 'Bedrooms', 'field_type' => 'number', 'is_required' => false, 'sort_order' => 3],
                    ['name' => 'Bathrooms', 'field_type' => 'number', 'is_required' => false, 'sort_order' => 4],
                    ['name' => 'Transaction Type', 'field_type' => 'select', 'is_required' => true, 'sort_order' => 5,
                     'values' => ['Sale', 'Rent / Lease', 'Joint Venture']],
                ],
                'experience' => [
                    ['name' => 'Experience Type', 'field_type' => 'select', 'is_required' => true, 'sort_order' => 1,
                     'values' => ['Water Sports', 'Trekking', 'Cycling', 'Camping', 'Cooking Class', 'Fishing', 'Wildlife Tour', 'Heritage Tour', 'Yoga / Wellness']],
                    ['name' => 'Duration', 'field_type' => 'text', 'is_required' => false, 'sort_order' => 2],
                    ['name' => 'Min Group Size', 'field_type' => 'number', 'is_required' => false, 'sort_order' => 3],
                    ['name' => 'Max Group Size', 'field_type' => 'number', 'is_required' => false, 'sort_order' => 4],
                    ['name' => 'Difficulty Level', 'field_type' => 'select', 'is_required' => false, 'sort_order' => 5,
                     'values' => ['Easy', 'Moderate', 'Challenging']],
                ],
                'events' => [
                    ['name' => 'Event Type', 'field_type' => 'select', 'is_required' => true, 'sort_order' => 1,
                     'values' => ['Concert', 'Festival', 'Workshop', 'Exhibition', 'Private Party Venue', 'Wedding Venue', 'Corporate Event']],
                    ['name' => 'Capacity', 'field_type' => 'number', 'is_required' => false, 'sort_order' => 2],
                    ['name' => 'Venue Setting', 'field_type' => 'select', 'is_required' => false, 'sort_order' => 3,
                     'values' => ['Indoor', 'Outdoor', 'Beach', 'Garden', 'Banquet Hall']],
                ],
                'transport' => [
                    ['name' => 'Vehicle Type', 'field_type' => 'select', 'is_required' => true, 'sort_order' => 1,
                     'values' => ['Car / SUV', 'Auto Rickshaw', 'Tempo Traveller', 'Bus', 'Boat / Ferry', 'Bike Rental']],
                    ['name' => 'Available Routes', 'field_type' => 'text', 'is_required' => false, 'sort_order' => 2],
                    ['name' => 'AC Available', 'field_type' => 'select', 'is_required' => false, 'sort_order' => 3,
                     'values' => ['Yes', 'No']],
                ],
                default => [
                    ['name' => 'Service Type', 'field_type' => 'text', 'is_required' => false, 'sort_order' => 1],
                    ['name' => 'Working Hours', 'field_type' => 'text', 'is_required' => false, 'sort_order' => 2],
                ],
            };

            foreach ($commonAttrs as $a) {
                $values = $a['values'] ?? [];
                unset($a['values']);
                $attr = CategoryAttribute::create(array_merge($a, ['category_id' => $cat->id]));
                foreach ($values as $v) {
                    CategoryAttributeValue::create(['category_attribute_id' => $attr->id, 'value' => $v, 'label' => $v]);
                }
            }
        }

        $this->command->info('✅ Category attributes & amenities seeded successfully!');
    }
}
