<?php

namespace Database\Factories;

use App\Models\Color;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Color>
 */
class ColorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Color::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $colorName = $this->faker->randomElement([
            'Đỏ', 'Xanh dương', 'Xanh lá', 'Vàng', 'Cam', 'Tím', 'Hồng', 'Đen', 'Trắng', 'Xám',
            'Nâu', 'Bạc', 'Vàng gold', 'Xanh navy', 'Xanh mint', 'Đỏ đô', 'Tím lavender'
        ]);
        
        $colorCode = $this->faker->hexColor();
        
        return [
            'color_name' => $colorName,
            'slug' => Str::slug($colorName),
            'is_active' => $this->faker->boolean(80), // 80% chance of being active
            'color_code' => $colorCode,
            'color_text' => $this->getContrastColor($colorCode), // Auto-generate contrasting text color
        ];
    }

    /**
     * Generate a contrasting text color based on background color
     */
    private function getContrastColor(string $hexColor): string
    {
        // Remove # if present
        $hex = ltrim($hexColor, '#');
        
        // Convert to RGB
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        // Calculate luminance
        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;
        
        // Return black for light backgrounds, white for dark backgrounds
        return $luminance > 0.5 ? '#000000' : '#ffffff';
    }

    /**
     * Indicate that the color is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the color is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}