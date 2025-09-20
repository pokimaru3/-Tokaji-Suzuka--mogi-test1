<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                'user_id' => 1,
                'name' => '腕時計',
                'price' => 15000,
                'brand_name' => 'Rolax',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'image' => 'images/Armani+Mens+Clock.jpg',
                'condition' => '良好',
            ],
            [
                'user_id' => 1,
                'name' => 'HDD',
                'price' => 5000,
                'brand_name' => '西芝',
                'description' => '高速で信頼性の高いハードディスク',
                'image' => 'images/HardDisk.jpg',
                'condition' => '目立った傷や汚れなし',
            ],
            [
                'user_id' => 1,
                'name' => '玉ねぎ3束',
                'price' => 300,
                'brand_name' => 'なし',
                'description' => '新鮮な玉ねぎ3束のセット',
                'image' => 'images/onion.jpg',
                'condition' => 'やや傷や汚れあり',
            ],
            [
                'user_id' => 1,
                'name' => '革靴',
                'price' => 4000,
                'brand_name' => '',
                'description' => 'クラシックなデザインの革靴',
                'image' => 'images/LeatherShoes.jpg',
                'condition' => '状態が悪い',
            ],
            [
                'user_id' => 1,
                'name' => 'ノートPC',
                'price' => 45000,
                'brand_name' => '',
                'description' => '高性能なノートパソコン',
                'image' => 'images/PC.jpg',
                'condition' => '良好',
            ],
            [
                'user_id' => 1,
                'name' => 'マイク',
                'price' => 8000,
                'brand_name' => 'なし',
                'description' => '高音質のレコーディング用マイク',
                'image' => 'images/MusicMic.jpg',
                'condition' => '目立った傷や汚れなし',
            ],
            [
                'user_id' => 1,
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'brand_name' => '',
                'description' => 'おしゃれなショルダーバッグ',
                'image' => 'images/Bug.jpg',
                'condition' => 'やや傷や汚れあり',
            ],
            [
                'user_id' => 1,
                'name' => 'タンブラー',
                'price' => 500,
                'brand_name' => 'なし',
                'description' => '使いやすいタンブラー',
                'image' => 'images/Tumbler.jpg',
                'condition' => '状態が悪い',
            ],
            [
                'user_id' => 1,
                'name' => 'コーヒーミル',
                'price' => 4000,
                'brand_name' => 'Starbacks',
                'description' => '手動のコーヒーミル',
                'image' => 'images/CoffeeGrinder.jpg',
                'condition' => '良好',
            ],
            [
                'user_id' => 1,
                'name' => 'メイクセット',
                'price' => 2500,
                'brand_name' => '',
                'description' => '便利なメイクアップセット',
                'image' => 'images/MakeSet.jpg',
                'condition' => '目立った傷や汚れなし',
            ],
        ];
        DB::table('items')->insert($items);
    }
}
