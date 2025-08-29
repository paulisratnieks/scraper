<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Article>
 */
class ArticleFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => fake()->unique()->numberBetween(1, 100),
            'title' => fake()->sentence(),
            'points' => fake()->numberBetween(),
            'link' => fake()->url(),
            'created_at' => Carbon::instance(fake()->dateTimeBetween())->toDateTimeString(),
        ];
    }
}
