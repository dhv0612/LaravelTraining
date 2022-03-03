<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataRole = [
            [
                'id' => 1,
                'name' => 'admin',
            ],
            [
                'id' => 2,
                'name' => 'user',
            ],
        ];
        DB::table('roles')->insert($dataRole);

        $dataUser = [
            [
                'id' => 1,
                'name' => Str::random(10),
                'email' => 'admin@gmail.com',
                'password' => Hash::make('123456'),
                'role_id' => 1,
            ],
            [
                'id' => 2,
                'name' => Str::random(10),
                'email' => 'user@gmail.com',
                'password' => Hash::make('123456'),
                'role_id' => 2,
            ],
        ];
        DB::table('users')->insert($dataUser);
    }
}
