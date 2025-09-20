<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Order;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 「購入する」ボタンを押下すると購入が完了する()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)
            ->post("/purchase/{$item->id}", [
                'payment_method' => 'credit_card',
                'postal_code' => '123-4567',
                'address' => '東京都渋谷区1-2-3',
                'building' => 'ビル101',
            ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'status'  => 'paid',
        ]);
    }

    /** @test */
    public function 購入した商品は商品一覧画面にて「sold」と表示される()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user)->post("/purchase/{$item->id}", [
            'payment_method' => 'credit_card',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-2-3',
            'building' => 'ビル101',
        ]);

        $response = $this->get('/');
        $response->assertSee('sold');
    }

    /** @test */
    public function 「購入した商品一覧」に追加されている()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user)->post("/purchase/{$item->id}", [
            'payment_method' => 'credit_card',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-2-3',
            'building' => 'ビル101',
        ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'status'  => 'paid',
        ]);

        $response = $this->actingAs($user)->get('/mypage?tab=purchase');

        $response->assertStatus(200);

        $response->assertSee($item->name);
    }

    /** @test */
    public function 支払い方法を変更すると小計画面に反映される()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->get("/purchase/{$item->id}");
        $response->assertStatus(200);

        $response = $this->actingAs($user)->post("/purchase/{$item->id}", [
            'payment_method' => 'credit_card',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-2-3',
            'building' => 'ビル101',
        ]);

        $response = $this->actingAs($user)->get("/purchase/{$item->id}");
        $response->assertSee('credit_card');
    }

    /** @test */
    public function 送付先住所変更画面で登録した住所が購入画面に反映される()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user)->post("/purchase/address/{$item->id}", [
            'postal_code' => '987-6543',
            'address' => '東京都新宿区1-2-3',
            'building' => 'ビル202',
        ]);

        $response = $this->actingAs($user)->get("/purchase/{$item->id}");
        $response->assertSee('987-6543');
        $response->assertSee('東京都新宿区1-2-3');
        $response->assertSee('ビル202');
    }

    /** @test */
    public function 購入した商品に送付先住所が紐づいて登録される()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->post("/purchase/{$item->id}", [
            'payment_method' => 'credit_card',
            'postal_code'    => '987-6543',
            'address'        => '東京都新宿区1-2-3',
            'building'       => 'ビル202',
        ]);

        $response->assertRedirect('/');

        $order = Order::where('user_id', $user->id)
            ->where('item_id', $item->id)
            ->first();

        $this->assertNotNull($order, "購入処理で Order が作成されていません");

        $this->assertDatabaseHas('shipping_addresses', [
            'order_id'   => $order->id,
            'postal_code' => '987-6543',
            'address'    => '東京都新宿区1-2-3',
            'building'   => 'ビル202',
        ]);
    }
}
