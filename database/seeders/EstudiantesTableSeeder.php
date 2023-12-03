<?php

namespace Database\Seeders;

use App\Models\Estudiante;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstudiantesTableSeeder extends Seeder
{
   
    public function run(): void
    {
        Estudiante::truncate();
        Estudiante::factory(20)->create();
    }
}
