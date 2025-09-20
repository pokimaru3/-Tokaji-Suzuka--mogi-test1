<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class AddressesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('ja_JP');

        foreach (range(1, 10) as $userId) {
            DB::table('addresses')->insert([
                'user_id'     => $userId,
                'postal_code' => $faker->postcode,
                'address'     => $faker->address,
                'building'    => $faker->optional()->secondaryAddress,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
    }
    }
}
