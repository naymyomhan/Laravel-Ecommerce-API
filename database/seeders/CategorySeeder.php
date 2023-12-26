<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Electronics', 'description' => 'A wide range of electronic devices and gadgets', 'image' => '/images/electronics.png'],
            ['name' => 'Fashion', 'description' => 'Clothing, footwear, and accessories for all styles', 'image' => '/images/fashion.png'],
            ['name' => 'Home & Kitchen', 'description' => 'Everything for your home, from appliances to décor', 'image' => '/images/home-kitchen.png'],
            ['name' => 'Books', 'description' => 'Novels, non-fiction, textbooks, and more', 'image' => '/images/books.png'],
            ['name' => 'Sports & Outdoors', 'description' => 'Gear and equipment for all your sporting activities', 'image' => '/images/sports-outdoors.png'],
        ];

        DB::table('categories')->insert($categories);
    }
}
