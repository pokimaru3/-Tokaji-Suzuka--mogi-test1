<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Models\User;
use App\Models\Item;
use App\Models\Address;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'user_id'    => User::factory(),
            'item_id'    => Item::factory(),
            'status'     => 'pending',
        ];
    }
}
