<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class user_typeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
        'designation' => $this->faker->text(7),
        ];
    }
}
