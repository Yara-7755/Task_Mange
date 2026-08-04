<?php

namespace Database\Seeders;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Category::create(['name' => 'Personal']);
        Category::create(['name' => 'Study']);
        Category::create(['name' => 'Home']);
        Category::create(['name' => 'Shopping']);
        Category::create(['name' => 'Sport']);
        Category::create(['name' => 'Family']);
    }

}
