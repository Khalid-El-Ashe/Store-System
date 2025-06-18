<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Store;
use Bezhanov\Faker\Provider\Commerce;
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
        $this->faker->addProvider(new Commerce($this->faker));

        $name = $this->faker->unique()->productName;
        return [
            'name' => $name,
            'slug' => Str::slug($name . '-' . uniqid()),
            'description' => $this->faker->sentence(20),
            'image' => 'https://picsum.photos/seed/' . uniqid() . '/800/600',
            'price' => $this->faker->randomFloat(2, 2, 499),
            'compare_price' => $this->faker->randomFloat(2, 200, 1000),
            'category_id' => Category::inRandomOrder()->first()->id, //Category::factory(),
            'store_id' => Store::inRandomOrder()->first()->id, //Store::factory(),
            'featured' => $this->faker->boolean(),
            'rating' => $this->faker->randomFloat(1, 0, 5),
            'status' => $this->faker->randomElement(['active', 'draft', 'archived']),
        ];
    }
}
