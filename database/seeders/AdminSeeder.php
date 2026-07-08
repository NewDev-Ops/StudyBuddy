<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admins = [
            ['email' => 'jeremy.sebudde@strathmore.edu',  'name' => 'Jeremy Sebudde'],
            ['email' => 'travis.mutungi@strathmore.edu',  'name' => 'Mutungi Travis'],
        ];

        foreach ($admins as $admin) {
            User::updateOrCreate(
                ['email' => $admin['email']],
                [
                    'name' => $admin['name'],
                    'role' => 'admin',
                ]
            );
        }
    }
}
