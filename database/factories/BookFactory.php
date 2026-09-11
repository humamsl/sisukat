<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    public function definition(): array
    {
        $title = ucfirst(fake()->words(4, true));

        return [
            'category_id' => null,
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(1, 100000),
            'description' => fake()->paragraphs(2, true),
            'author' => fake()->name(),
            'year' => fake()->numberBetween(2020, 2026),
            'pages_count' => fake()->numberBetween(20, 150),
            'cover' => null,
            'file' => null,
            'download_count' => 0,
            'status' => 'published',
        ];
    }

    public function draft(): static
    {
        return $this->state(fn () => ['status' => 'draft']);
    }
}
