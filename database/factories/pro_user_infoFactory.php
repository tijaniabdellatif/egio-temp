<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class pro_user_infoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        // get all cities ids
        $cities = \App\Models\cities::all()->pluck('id')->toArray();

        return [
            'user_id' => rand(1,10),
            'city' => $this->faker->randomElement($cities),
            'company' => $this->faker->company(),
            'probannerimg' => rand(1,10),
        ];
    }
}
