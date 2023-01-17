<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class media_typeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'designation' => $this->faker->text()
        ];
    }
}
