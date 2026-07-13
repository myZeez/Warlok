<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        collect([
            ['name' => 'Makanan', 'icon' => 'heroicon-o-cake'],
            ['name' => 'Minuman', 'icon' => 'heroicon-o-beaker'],
            ['name' => 'Jasa', 'icon' => 'heroicon-o-wrench-screwdriver'],
            ['name' => 'Kerajinan', 'icon' => 'heroicon-o-paint-brush'],
            ['name' => 'Sembako', 'icon' => 'heroicon-o-shopping-bag'],
        ])->each(fn (array $category) => Category::create($category));
    }
}
