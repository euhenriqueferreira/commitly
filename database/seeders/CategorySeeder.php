<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Saúde e Fitness',
                'slug' => 'health-and-fitness',
                'icon' => 'dumbbell',
                'color' => 'category-health',
            ],
            [
                'name' => 'Produtividade',
                'slug' => 'productivity',
                'icon' => 'briefcase',
                'color' => 'category-productivity',
            ],
            [
                'name' => 'Desenvolvimento',
                'slug' => 'personal-growth',
                'icon' => 'sprout',
                'color' => 'category-growth',
            ],
            [
                'name' => 'Finanças',
                'slug' => 'finance',
                'icon' => 'wallet',
                'color' => 'category-finance',
            ],
            [
                'name' => 'Higiene/Cuidados',
                'slug' => 'self-care',
                'icon' => 'heart',
                'color' => 'category-selfcare',
            ],
            [
                'name' => 'Outro',
                'slug' => 'other',
                'icon' => 'more-horizontal',
                'color' => 'category-other',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}
