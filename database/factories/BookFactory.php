<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BooksFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title_ar' => $this->faker->sentence(),
            'title_en' => $this->faker->sentence(),
            'description_en' => $this->faker->text(),
            'description_ar' => $this->faker->text(),
            'author_id' => User::factory(),
        ];
    }
}
