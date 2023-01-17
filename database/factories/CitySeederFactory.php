<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CitySeederFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            'name' => $this->faker->name(),
            'lat' => $this->faker->latitude(),
            'lng' => $this->faker->longitude(),
            'province_id' => rand(1,30),
        ];

    }
}
