<?php

namespace Database\Factories;

use App\Models\ads;
use Illuminate\Database\Eloquent\Factories\Factory;

class adsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->title(),
            'description' => $this->faker->text(),
            'catid' => rand(1,22),
            // 'parent_project' => 2,
            'id_user' => rand(1,30),
            'price' => $this->faker->randomDigit(),
            'price_curr' => "MAD",
            //'loccountrycode' => 'MA',
            "locdept" => 1,
            "loccity"=> 2,
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
        ];
    }
}
