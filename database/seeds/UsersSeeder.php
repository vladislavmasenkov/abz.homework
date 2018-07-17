<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create('en_US');
        for ($i = 0; $i < 45; $i++) {
            \App\User::create([
                'name' => $faker->name,
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'avatar' => 'avatar.jpg',
                'password' => str_random(12),
                'api_token' => str_random(60)
            ]);
        }
    }
}
