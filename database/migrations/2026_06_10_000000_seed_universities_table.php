<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $universities = [
            ['name' => 'University of Nairobi', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Moi University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kenyatta University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Egerton University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Jomo Kenyatta University of Agriculture and Technology', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Maseno University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Masinde Muliro University of Science and Technology', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Dedan Kimathi University of Technology', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Chuka University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Technical University of Kenya', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Technical University of Mombasa', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pwani University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kisii University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'University of Eldoret', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Maasai Mara University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Jaramogi Oginga Odinga University of Science and Technology', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Laikipia University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'South Eastern Kenya University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Meru University of Science and Technology', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Multimedia University of Kenya', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'University of Kabianga', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Karatina University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kibabii University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Rongo University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'The Co-operative University of Kenya', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Taita Taveta University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => "Murang'a University of Technology", 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'University of Embu', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Machakos University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kirinyaga University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Garissa University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Alupe University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kaimosi Friends University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tom Mboya University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tharaka University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'National Defence University-Kenya', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Turkana University College', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bomet University College', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Koitaleel Samoei University College', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'National Intelligence Research University College', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Mama Ngina University College', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'University of Eastern Africa, Baraton', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Catholic University of Eastern Africa', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Daystar University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Scott Christian University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'United States International University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Africa Nazarene University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kenya Methodist University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => "St. Paul's University", 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pan Africa Christian University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Strathmore University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kabarak University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Mount Kenya University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Africa International University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kenya Highlands Evangelical University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Great Lakes University of Kisumu', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'KCA University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Adventist University of Africa', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'KAG EAST University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Umma University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Presbyterian University of East Africa', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Aga Khan University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => "Kiriri Women's University of Science and Technology", 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'The East African University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Zetech University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lukenya University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hekima University College', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tangaza University College', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Marist International University College', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'GRETSA University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Management University of Africa', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Riara University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pioneer International University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'International Leadership University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'RAF International University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'AMREF International University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Uzima University', 'location' => 'Kenya', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('universities')->insert($universities);
    }

    public function down(): void
    {
        DB::table('universities')->truncate();
    }
};
