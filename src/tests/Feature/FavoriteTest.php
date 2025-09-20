<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Favorite;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function いいねアイコンを押下するといいねした商品として登録することができる()
    {
        $user = User::factory()->create();
        $owner = User::factory()->create();
        $item = Item::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($user)
            ->post("/item/{$item->id}/favorite")
            ->assertStatus(200);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    /** @test */
    public function 再度いいねアイコンを押下するといいねを解除できる()
    {
        $user = User::factory()->create();
        $owner = User::factory()->create();
        $item = Item::factory()->create(['user_id' => $owner->id]);

        $user->favorites()->attach(['item_id' => $item->id]);

        $this->actingAs($user)
            ->post("/item/{$item->id}/favorite")
            ->assertStatus(200);

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    /** @test */
    public function 追加済みのアイコンは色が変化する()
    {
        $user = User::factory()->create();
        $owner = User::factory()->create();
        $item = Item::factory()->create(['user_id' => $owner->id]);

        $user->favorites()->attach($item->id);

        $response = $this->actingAs($user)->get("/item/{$item->id}");

        $response->assertSee('class="favorite-icon liked"', false);
    }
}
