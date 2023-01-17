<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class mediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'path' => $this->faker->text(),
            'filename' => $this->faker->text(),
            'media_type' => rand(1,10),
        ];
    }
}
