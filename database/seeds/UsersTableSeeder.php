<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(
            [
                [
                    'firstname' => 'blueikhdbackend',
                    'lastname' => 'Admin',
                    'email' => 'blueikhdbackend@stream.com',
                    'username' => 'admin',
                    'role'=>'ADMIN',
                    'password' =>  bcrypt('admin'),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'email_verified_at'=> Carbon::now()
                ]
            ]
        );
    }
}
