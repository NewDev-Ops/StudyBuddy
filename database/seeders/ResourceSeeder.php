<?php

namespace Database\Seeders;

use App\Models\Resource;
use App\Services\SubjectNormalizer;
use Illuminate\Database\Seeder;

class ResourceSeeder extends Seeder
{
    public function run(): void
    {
        $resources = [
            ['title' => 'Khan Academy — Chemistry',  'url' => 'https://www.khanacademy.org/science/chemistry',        'subject_tag' => 'Chemistry'],
            ['title' => 'Periodic Videos (YouTube)',  'url' => 'https://www.youtube.com/user/periodicvideos',           'subject_tag' => 'Chemistry'],
            ['title' => 'Khan Academy — Physics',     'url' => 'https://www.khanacademy.org/science/physics',           'subject_tag' => 'Physics'],
            ['title' => 'MIT OpenCourseWare — Physics','url' => 'https://ocw.mit.edu/courses/physics/',                'subject_tag' => 'Physics'],
            ['title' => 'Khan Academy — Biology',     'url' => 'https://www.khanacademy.org/science/biology',           'subject_tag' => 'Biology'],
            ['title' => 'Bozeman Science (YouTube)',  'url' => 'https://www.youtube.com/user/bozemanbiology',            'subject_tag' => 'Biology'],
            ['title' => 'Khan Academy — English',     'url' => 'https://www.khanacademy.org/humanities/grammar',        'subject_tag' => 'English'],
            ['title' => '3Blue1Brown — Mathematics',  'url' => 'https://www.youtube.com/3blue1brown',                  'subject_tag' => 'Maths'],
            ['title' => 'Crash Course — Computer Science','url' => 'https://www.youtube.com/playlist?list=PL8dPuuaLjXtNlUrzyH5r6jN9ulIgZBpdo', 'subject_tag' => 'Computer Science'],
            ['title' => 'freeCodeCamp — CS',           'url' => 'https://www.freecodecamp.org/',                       'subject_tag' => 'Computer Science'],
        ];

        foreach ($resources as $data) {
            Resource::create($data);
        }

        $this->command->info('Seeded ' . count($resources) . ' resources.');
    }
}
