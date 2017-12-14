<?php

use Illuminate\Database\Seeder;

class AdministratorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create('zh_CN');

        DB::connection('system')->table('administrator')->insertGetId([
            'admin_name' => 'admin',
            'password' => app('hash')->make('admin'),
            'email' => 'admin@52shoucai.com',
            'role_id' => 1,
            'nickname' => $faker->name,
            'realname' => $faker->name,
            'phone' => $faker->phoneNumber,
            'status' => 1,
            'last_login_time' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
