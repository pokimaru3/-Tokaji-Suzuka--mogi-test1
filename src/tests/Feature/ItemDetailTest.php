<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Comment;


class ItemDetailTest extends TestCase
{
    /** @test */
    public function 必要な情報が表示される()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $user->id,
            'name' => 'テスト商品',
            'brand_name' => 'テストブランド',
            'price' => 5000,
            'description' => 'これはテスト商品です',
            'condition' => '良好',
            'image' => 'test_image.jpg',
        ]);

        $categories = Category::factory()->count(2)->create();
        $item->categories()->attach($categories->pluck('id'));

        $commentUser = User::factory()->create();
        Comment::factory()->create([
            'user_id' => $commentUser->id,
            'item_id' => $item->id,
            'content' => 'テストコメント',
        ]);

        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);
        $response->assertSee('storage/' . $item->image, false);
        $response->assertSee($item->name);
        $response->assertSee($item->brand_name);
        $response->assertSee(number_format($item->price));
        $response->assertSee($item->description);
        $response->assertSee($item->condition);

        foreach ($categories as $category) {
            $response->assertSee($category->name);
        }

        $response->assertSee((string) $item->comments()->count());
        $response->assertSee('テストコメント');
        $response->assertSee($commentUser->name);

        $response->assertSee((string) $item->favorites()->count());
    }
}
