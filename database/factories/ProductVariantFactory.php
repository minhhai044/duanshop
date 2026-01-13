<?php

namespace Database\Factories;

use App\Models\Capacity;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductVariant::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $price = $this->faker->numberBetween(500000, 30000000);
        $priceSale = $this->faker->boolean(50) ? $this->faker->numberBetween(300000, $price - 50000) : 0;
        
        return [
            'product_id' => Product::factory(),
            'color_id' => Color::factory(),
            'capacity_id' => Capacity::factory(),
            'quantity' => $this->faker->numberBetween(0, 100),
            'price' => $price,
            'price_sale' => $priceSale,
        ];
    }

    /**
     * Indicate that the variant is out of stock.
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'quantity' => 0,
        ]);
    }

    /**
     * Indicate that the variant has high stock.
     */
    public function highStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'quantity' => $this->faker->numberBetween(50, 200),
        ]);
    }

    /**
     * Indicate that the variant is on sale.
     */
    public function onSale(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'price_sale' => $this->faker->numberBetween(300000, $attributes['price'] - 50000),
            ];
        });
    }
}