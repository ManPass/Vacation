<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            "login"=>"admin@gmail.com",
            "password"=>Hash::make("root")
        ]);
        $user->save();

        $user->roles()->save(Role::getRoleByName("admin"));

    }
}
