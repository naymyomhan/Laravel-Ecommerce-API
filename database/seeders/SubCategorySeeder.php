<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subCategories = [
            ['name' => 'Smartphones', 'category_id' => 1, 'description' => 'Latest smartphones from top brands'],
            ['name' => 'Laptops', 'category_id' => 1, 'description' => 'Powerful laptops for work and play'],
            ['name' => 'Headphones', 'category_id' => 2, 'description' => 'High-quality headphones for music and calls'],
            ['name' => 'Speakers', 'category_id' => 2, 'description' => 'Wireless and Bluetooth speakers for immersive sound'],
        ];

        DB::table('sub_categories')->insert($subCategories);
    }
}
