<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Order;



class ProductListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 商品一覧取得()
    {
        $user = User::factory()->create();

        $myItem = Item::factory()->create([
            'user_id' => $user->id,
            'is_sold' => false,
        ]);

        $otherItem = Item::factory()->create([
            'is_sold' => true,
        ]);

        $soldItem = Item::factory()->create([
            'is_sold' => true,
        ]);

        Order::factory()->create([
            'user_id' => $user->id,
            'item_id' => $soldItem->id,
        ]);

        $response = $this->actingAs($user)->get('/');
        $response->assertStatus(200);
        $response->assertSeeText($otherItem->name);
        $response->assertDontSeeText($myItem->name);
        $response->assertSeeText('Sold');
    }
}
