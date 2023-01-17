<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ContractFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => rand(0,30),
            'assigned_user' => rand(0,30),
            'comment' => $this->faker->text(),
            'price' => $this->faker->randomDigitNotZero(),
            'plan_id' => rand(1,5),
            'ltc_nbr' => $this->faker->randomDigit(),
            'ads_nbr' => $this->faker->randomDigit(),
            'duration' => $this->faker->randomDigitNotZero(),
            'contract_file' => rand(1,10),
            'active' => 1
        ];
    }
}
