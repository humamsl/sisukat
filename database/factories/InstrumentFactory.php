<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Instrument>
 */
class InstrumentFactory extends Factory
{
    public function definition(): array
    {
        $title = ucfirst(fake()->words(4, true));

        return [
            'category_id' => null,
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(1, 100000),
            'description' => fake()->paragraph(),
            'file' => 'instruments/placeholder.pdf',
            'file_type' => 'pdf',
            'file_size' => fake()->numberBetween(50_000, 2_000_000),
            'year' => fake()->numberBetween(2020, 2026),
            'download_count' => 0,
            'status' => 'published',
        ];
    }

    public function draft(): static
    {
        return $this->state(fn () => ['status' => 'draft']);
    }
}
