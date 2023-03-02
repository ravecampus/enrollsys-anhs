<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
        DB::table('users')->insert([
            'id' => 1,
            'role'=>2, 
            'first_name'=>'winsdev', 
            'last_name'=>'2019', 
            'username'=>'admin',
            // 'email'=>'admin@gmail.com',
            'password'=>bcrypt('admin')
        ]);
    }
}
