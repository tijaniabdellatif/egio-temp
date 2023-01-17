<?php

namespace Database\Factories;

use App\Models\user_type;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Contracts\Role;
use Spatie\Permission\Models\Role as ModelsRole;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        // get all user_type ids
        $usertypes = user_type::all()->pluck('id')->toArray();

        // get all authtypes ids
        $auth_type = \App\Models\auth_type::all()->pluck('id')->toArray();

        // $this->faker->randomElement($cities)

        return [
            'firstname' => $this->faker->name(),
            'lastname' => $this->faker->lastName(),
            'username' => $this->faker->userName(),
            'email' => $this->faker->unique()->safeEmail(),
            'usertype' => $this->faker->randomElement($usertypes),
            'password' => Hash::make('kkkkk'),
            'authtype' => $this->faker->randomElement($auth_type),
            'assigned_user' => rand(0,30),
            'assigned_ced' => rand(0,30),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
