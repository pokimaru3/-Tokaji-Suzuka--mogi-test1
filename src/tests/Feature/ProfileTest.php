<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Order;


class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 必要な情報が取得できる（プロフィール画像、ユーザー名、出品した商品一覧、購入した商品一覧）()
    {
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'image' => 'profile/test.png',
        ]);

        $sellItem = Item::factory()->create([
            'user_id' => $user->id,
            'name' => '出品商品A',
            'image' => 'default.png',
        ]);

        $seller = User::factory()->create();
        $purchaseItem = Item::factory()->create([
            'user_id' => $seller->id,
            'name' => '購入商品B',
            'image' => 'default.png',
        ]);
        Order::factory()->create([
            'user_id' => $user->id,
            'item_id' => $purchaseItem->id,
        ]);

        $response = $this->actingAs($user)->get('/mypage');

        $response->assertSee($user->name);
        $response->assertSee('profile/test.png');

        $response->assertSee('出品商品A');

        $response = $this->actingAs($user)->get('/mypage?tab=purchase');
        $response->assertSee('購入商品B');
    }

    /** @test */
    public function 変更項目が初期値として過去設定されていること（プロフィール画像、ユーザー名、郵便番号、住所）()
    {
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'image' => 'profile/test.png',
        ]);

        $user->address()->create([
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-2-3',
            'building' => 'ビル101',
        ]);

        $response = $this->actingAs($user)->get('/mypage/profile');

        $response->assertStatus(200);
        $response->assertSee('テストユーザー');
        $response->assertSee('profile/test.png');
        $response->assertSee('123-4567');
        $response->assertSee('東京都渋谷区1-2-3');
        $response->assertSee('ビル101');
    }
}
