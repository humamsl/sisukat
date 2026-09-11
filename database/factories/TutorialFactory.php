<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tutorial>
 */
class TutorialFactory extends Factory
{
    public function definition(): array
    {
        $title = ucfirst(fake()->words(5, true));

        return [
            'category_id' => null,
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(1, 100000),
            'description' => fake()->paragraph(),
            'content' => fake()->paragraphs(3, true),
            'thumbnail' => null,
            'video_url' => null,
            'file' => null,
            'type' => 'article',
            'external_url' => null,
            'status' => 'published',
        ];
    }

    public function video(): static
    {
        return $this->state(fn () => [
            'type' => 'video',
            'content' => null,
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn () => ['status' => 'draft']);
    }
}
