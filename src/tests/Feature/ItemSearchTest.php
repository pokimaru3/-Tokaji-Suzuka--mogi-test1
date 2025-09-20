<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Favorite;

class ItemSearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test*/
    public function 「商品名」で部分一致検索ができる()
    {
        $item1 = Item::factory()->create(['name' => 'Nike Shoes']);
        $item2 = Item::factory()->create(['name' => 'Adidas Jacket']);

        $response = $this->get('/?keyword=Nike');
        $response->assertSee($item1->name);
        $response->assertDontSee($item2->name);
    }

    /** @test*/
    public function 検索状態がマイリストでも保持されている()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $item1 = Item::factory()->create(['name' => 'Nike Shoes', 'user_id' => $otherUser->id]);
        $item2 = Item::factory()->create(['name' => 'Adidas Jacket', 'user_id' => $otherUser->id]);

        Favorite::create([
            'user_id' => $user->id,
            'item_id' => $item1->id,
        ]);

        $this->actingAs($user);
        $response = $this->get('/?keyword=Nike');
        $response->assertSee($item1->name);
        $response->assertDontSee($item2->name);

        $response = $this->get('/?tab=mylist&keyword=Nike');
        $response->assertSee($item1->name);
        $response->assertDontSee($item2->name);
    }
}
