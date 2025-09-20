<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Category;
use App\Models\Item;

class SellItemTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 商品出品画面にて必要な情報が保存できる（カテゴリ、商品の状態、商品名、ブランド名、商品の説明、販売価格）()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $category = Category::factory()->create();
        $file = UploadedFile::fake()->create('item.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($user)->post('/sell', [
            'image' => $file,
            'categories' => [$category->id],
            'condition' => '良好',
            'name' => 'テスト商品',
            'brand_name' => 'テストブランド',
            'description' => 'これはテスト用の商品説明です',
            'price' => 12345,
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'condition' => '良好',
            'name' => 'テスト商品',
            'brand_name' => 'テストブランド',
            'description' => 'これはテスト用の商品説明です',
            'price' => 12345,
        ]);

        $item = Item::first()->load('categories');
        $this->assertTrue(
            $item->categories->contains(function ($c) use ($category) {
                return $c->name === $category->name;
            })
        );
        Storage::disk('public')->assertExists('images/' . $file->hashName());
    }
}
