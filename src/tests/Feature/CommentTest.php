<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ログイン済みのユーザーはコメントを送信できる()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user)
            ->post("/item/{$item->id}/comment", [
                'content' => 'テストコメント',
            ])
            ->assertStatus(200)
            ->assertJson([
                'content' => 'テストコメント',
                'user_name' => $user->name,
            ]);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => 'テストコメント',
        ]);
    }

    /** @test */
    public function ログイン前のユーザーはコメントを送信できない()
    {
        $item = Item::factory()->create();

        $this->postJson("/item/{$item->id}/comment", [
            'content' => '未ログインコメント',
        ])->assertStatus(401);
    }

    /** @test */
    public function コメントが入力されていない場合バリデーションメッセージが表示される()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)
            ->postJson("/item/{$item->id}/comment", [
                'content' => '',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['content']);
    }

    /** @test */
    public function コメントが255字以上の場合バリデーションメッセージが表示される()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $longContent = str_repeat('あ', 256);

        $this->actingAs($user)
            ->postJson("/item/{$item->id}/comment", ['content' => $longContent])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['content']);
    }
}

