<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'author_id' => fake()->name(),
            'category_id' => fake()->randomElement(['art','inf','brt','crt']),
            'slug' => fake()->slug(),
            'title' => fake()->text(60),
            'featured_image' => fake()->imageUrl(),
            'content' => fake()->text(1000),
            'status' => 'published',
            'starred' => true
        ];
    }
}
