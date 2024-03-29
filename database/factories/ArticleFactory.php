<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->words(rand(4, 10), true);

        return [
            'content' => fake()->paragraphs(random_int(15, 35), true),
            'title' => $title,
            'slug' => implode("-", explode(" ", strtolower($title))),
            'created_at' => fake()->dateTimeBetween('-6 months')
        ];
    }
}
