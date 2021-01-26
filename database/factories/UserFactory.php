<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->name;
        $words = explode(" ", $name);
        $acronym = "";

        foreach ($words as $w) {
            $acronym .= $w[0];
        }
        return [
            'name' => $name,
            'mobile' => $this->faker->unique()->regexify('\09(1[0-9]|3[1-9]|2[1-9])[0-9]{3}[0-9]{4}$/') ,
            'avatar' => $this->faker->imageUrl(256,256,null,true, $acronym),
            'password' => Hash::make('password'),
            'active' => $this->faker->boolean
        ];
    }
}
