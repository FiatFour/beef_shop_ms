<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->name();
        $slug = Str::slug($title);

        $subCategories = [7,9];
        $subCategoriesRandKey = array_rand($subCategories);

        $cowGenes = [5,6];
        $cowGenesRandKey = array_rand($cowGenes);

        return [
            'title' => $title,
            'slug' => $slug,
            'category_id' => 162,
            'sub_category_id' => $subCategories[$subCategoriesRandKey],
            'cow_gene_id' => $cowGenes[$cowGenesRandKey],
            'price' => rand(10, 1000),
            'sku' => rand(1000, 10000),
            'track_qty' => 'Yes',
            'qty' => 10,
            'is_featured' => 'Yes',
            'status' => 1
        ];
    }
}
