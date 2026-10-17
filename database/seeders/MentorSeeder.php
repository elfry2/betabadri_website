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
                'skills' => 'Management, Leadership',
                'image' => 'mentors/arif.jpg',
            ],
            [
                'name' => 'Muhammad Razzaq Akbar, S.T',
                'role' => 'Robotic Teacher',
                'skills' => 'Lego, Logic, Modular',
                'image' => 'mentors/razzaq.jpg',
            ],
            [
                'name' => 'Panji Wirya Pastika S.T',
                'role' => 'Robotic Teacher',
                'skills' => 'IoT, Arduino, Electronic',
                'image' => 'mentors/panji.jpg',
            ],
            [
                'name' => 'Muhammad Elfri Rahmad Juang S.T',
                'role' => 'Programming Teacher',
                'skills' => 'Python, Web, Algorithm',
                'image' => 'mentors/elfry.jpeg',
            ],
        ];

        foreach ($mentors as $mentor) {
            Mentor::updateOrCreate(
                ['name' => $mentor['name']],
                [
                    'role' => $mentor['role'],
                    'skills' => $mentor['skills'] ?? null,
                    'image' => $mentor['image'],
                ]
            );
        }
    }
}
