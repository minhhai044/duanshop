<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $productName = $this->faker->randomElement([
            'iPhone 15 Pro Max', 'Samsung Galaxy S24 Ultra', 'iPad Pro 12.9', 'MacBook Air M3',
            'AirPods Pro 2', 'Apple Watch Series 9', 'Samsung Galaxy Tab S9', 'Dell XPS 13',
            'Sony WH-1000XM5', 'Nintendo Switch OLED', 'PlayStation 5', 'Xbox Series X'
        ]);
        
        $priceRegular = $this->faker->numberBetween(1000000, 50000000);
        $priceSale = $this->faker->boolean(60) ? $this->faker->numberBetween(500000, $priceRegular - 100000) : 0;
        
        return [
            'category_id' => Category::factory(),
            'pro_name' => $productName,
            'pro_sku' => 'SKU-' . strtoupper($this->faker->unique()->bothify('???###')),
            'pro_slug' => Str::slug($productName) . '-' . $this->faker->unique()->numberBetween(1000, 9999),
            'pro_description' => $this->faker->paragraphs(3, true),
            'pro_img_thumbnail' => null, // Will be handled separately if needed
            'pro_price_regular' => $priceRegular,
            'pro_price_sale' => $priceSale,
            'pro_views' => $this->faker->numberBetween(0, 10000),
            'pro_featured' => $this->faker->boolean(30), // 30% chance of being featured
            'pro_prating' => $this->faker->randomFloat(1, 0, 10),
            'is_hot' => $this->faker->boolean(20), // 20% chance of being hot
            'is_active' => $this->faker->boolean(90), // 90% chance of being active
        ];
    }

    /**
     * Indicate that the product is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'pro_featured' => true,
        ]);
    }

    /**
     * Indicate that the product is hot.
     */
    public function hot(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_hot' => true,
        ]);
    }

    /**
     * Indicate that the product is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the product has a sale price.
     */
    public function onSale(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'pro_price_sale' => $this->faker->numberBetween(500000, $attributes['pro_price_regular'] - 100000),
            ];
        });
    }
}