<?php

use Illuminate\Database\Seeder;
use \App\User;
use Cartalyst\Sentinel\Roles\EloquentRole;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel as Sentinel;
use Cartalyst\Sentinel\Activations\EloquentActivation as Activation;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		// \App\User::truncate();

        $superadmin = Sentinel::registerAndActivate([
            'email' => 'superadmin@gmail.com',
            'password' => 'secret',
            'first_name' => 'Super',
            'last_name' => 'Admin'

        ]);

        $role = Sentinel::findRoleBySlug('superadmin');
        $role->users()->attach($superadmin);
    }
}
