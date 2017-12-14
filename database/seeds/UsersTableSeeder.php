<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create('zh_CN');

        $uid = DB::connection('user')->table('users')->insertGetId([
            'nickname' => $faker->name('male'),
            'avatar' => 'avatar/10/ca69b29f0e80c13c6bc4524d84657a.jpeg',
            'gender' => 1,
            'status' => 1,
            'registered_at' => date('Y-m-d H:i:s'),
        ]);


        DB::connection('user')->table('user_credentials')->insert([
            'id' => $uid,
            'email' => 'test@52shoucai.com',
            'mobile' => $faker->phoneNumber,
            'password' => app('hash')->make('123456'),
        ]);


        for ($i = 0; $i < 20; $i++) {
            $uid = DB::connection('user')->table('users')->insertGetId([
                'nickname' => $faker->name('female'),
                'avatar' => 'avatar/10/ca69b29f0e80c13c6bc4524d84657a.jpeg',
                'gender' => 1,
                'status' => 1,
                'registered_at' => date('Y-m-d H:i:s'),
            ]);


            DB::connection('user')->table('user_credentials')->insert([
                'id' => $uid,
                'email' => $faker->email,
                'mobile' => $faker->phoneNumber,
                'password' => app('hash')->make('123456'),
            ]);
        }
    }
}
