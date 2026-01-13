<?php

namespace Database\Factories;

use App\Models\Gallery;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Gallery>
 */
class GalleryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Gallery::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'image' => null, // Will be handled separately if needed for actual image files
        ];
    }

    /**
     * Create a gallery with a fake image path.
     */
    public function withImage(): static
    {
        return $this->state(fn (array $attributes) => [
            'image' => 'galleries/' . $this->faker->uuid() . '.jpg',
        ]);
    }
}