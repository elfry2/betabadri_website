<?php

namespace Database\Seeders;

use App\Models\Mentor;
use Illuminate\Database\Seeder;

class MentorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mentors = [
            [
                'name' => 'Arif Sandira, S.T',
                'role' => 'Team Management',
                'image' => 'mentors/arif.jpg',
            ],
            [
                'name' => 'Muhammad Razzaq Akbar, S.T',
                'role' => 'Robotic Teacher',
                'image' => 'mentors/razzaq.jpg',
            ],
            [
                'name' => 'Panji Wirya Pastika S.T',
                'role' => 'Robotic Teacher',
                'image' => 'mentors/panji.jpg',
            ],
            [
                'name' => 'Muhammad Elfri Rahmad Juang S.T',
                'role' => 'Programming Teacher',
                'image' => 'mentors/elfry.jpeg',
            ],
        ];

        foreach ($mentors as $mentor) {
            Mentor::updateOrCreate(
                ['name' => $mentor['name']],
                [
                    'role' => $mentor['role'],
                    'image' => $mentor['image'],
                ]
            );
        }
    }
}
