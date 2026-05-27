<?php

namespace Database\Seeders;

use App\Models\ClassifiedCategory;
use Illuminate\Database\Seeder;

class ClassifiedCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['Furniture & Home', 'chair', 'Sofas, beds, tables, decor and more'],
            ['Electronics & Appliances', 'devices', 'TVs, fridges, ACs, kitchen appliances'],
            ['Mobiles & Tablets', 'smartphone', 'Phones, tablets and accessories'],
            ['Vehicles', 'directions_car', 'Cars, bikes, scooters and spares'],
            ['Home Decor & Garden', 'yard', 'Plants, decor, outdoor and garden items'],
            ['Sports & Fitness', 'fitness_center', 'Cycles, gym equipment, sports gear'],
            ['Books, Hobbies & Music', 'menu_book', 'Books, instruments, collectibles'],
            ['Kids & Baby', 'stroller', 'Toys, prams, kids furniture and gear'],
            ['Fashion & Accessories', 'checkroom', 'Clothing, bags, watches, jewellery'],
            ['Other', 'category', 'Anything else for sale in Alibaug'],
        ];

        foreach ($categories as $i => [$name, $icon, $description]) {
            ClassifiedCategory::firstOrCreate(
                ['name' => $name],
                ['icon' => $icon, 'description' => $description, 'sort_order' => $i, 'is_active' => true]
            );
        }
    }
}
