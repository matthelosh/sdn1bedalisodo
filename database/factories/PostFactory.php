<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

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
        $authors = [];
        $users = User::all();
        foreach($users as $user) {
            array_push($authors, $user->name);
        }

        $images = Storage::allFiles('/');

        return [
            'author_id' => fake()->randomElement($authors),
            'category_id' => fake()->randomElement(['art','inf','brt','crt']),
            'slug' => fake()->slug(),
            'title' => fake()->text(60),
            'featured_image' => fake()->randomElement($images),
            'content' => fake()->text(1000),
            'status' => 'published',
            'starred' => true
        ];
    }
}
