<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class countriesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->country(),
            'code' => $this->faker->countryCode()
        ];
    }
}
