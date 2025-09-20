<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Item;

class ItemFactory extends Factory
{

    protected $model = Item::class;
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->word(),
            'brand_name' => $this->faker->company(),
            'price' => $this->faker->numberBetween(1000, 10000),
            'description' => $this->faker->sentence(),
            'image' => 'default.png',
            'condition'   => $this->faker->randomElement(['良好', '状態が悪い']),
            'is_sold'     => false,
        ];
    }
}
