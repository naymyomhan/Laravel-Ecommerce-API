<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            ['name' => 'Apple', 'description' => 'American multinational technology company', 'image' => '/images/apple.png'],
            ['name' => 'Samsung', 'description' => 'South Korean multinational electronics corporation', 'image' => '/images/samsung.png'],
            ['name' => 'Nike', 'description' => 'American multinational corporation that is engaged in the design, development, manufacturing, and worldwide marketing and sales of footwear, apparel, equipment, accessories, and services', 'image' => '/images/nike.png'],
            ['name' => 'Google', 'description' => 'American multinational technology company that specializes in Internet-related services and products', 'image' => '/images/google.png'],
            ['name' => 'Microsoft', 'description' => 'American multinational technology corporation that produces computer software, consumer electronics, personal computers, and related services', 'image' => '/images/microsoft.png'],
        ];

        DB::table('brands')->insert($brands);
    }
}
