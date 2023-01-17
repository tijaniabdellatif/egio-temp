<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class user_infoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => rand(1,10),
            'gender' => $this->faker->randomElement($array = array ('male', 'female')) ,
            'bio' => $this->faker->text(),
        ];
    }
}
