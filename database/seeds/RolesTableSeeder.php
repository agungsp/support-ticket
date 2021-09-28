<?php

use App\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'id'    => 1,
                'title' => 'Admin',
            ],
            [
                'id'    => 2,
                'title' => 'Agent',
            ],
            [
                'id'    => 3,
                'title' => 'Client',
            ],
        ];

        Role::insert($roles);
    }
}
