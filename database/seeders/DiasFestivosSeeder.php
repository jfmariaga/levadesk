<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiasFestivosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('dias_festivos')->insert([
            ['fecha' => '2024-01-01'], // Año Nuevo
            ['fecha' => '2024-01-08'], // Día de los Reyes Magos
            ['fecha' => '2024-03-25'], // Día de San José
            ['fecha' => '2024-03-28'], // Jueves Santo
            ['fecha' => '2024-03-29'], // Viernes Santo
            ['fecha' => '2024-05-01'], // Día del Trabajo
            ['fecha' => '2024-05-13'], // Día de la Ascensión
            ['fecha' => '2024-06-03'], // Corpus Christi
            ['fecha' => '2024-06-10'], // Sagrado Corazón
            ['fecha' => '2024-07-01'], // San Pedro y San Pablo
            ['fecha' => '2024-07-20'], // Día de la Independencia
            ['fecha' => '2024-08-07'], // Batalla de Boyacá
            ['fecha' => '2024-08-19'], // Asunción de la Virgen
            ['fecha' => '2024-10-14'], // Día de la Raza
            ['fecha' => '2024-11-04'], // Día de Todos los Santos
            ['fecha' => '2024-11-11'], // Independencia de Cartagena
            ['fecha' => '2024-12-08'], // Inmaculada Concepción
            ['fecha' => '2024-12-25'], // Navidad
        ]);
    }
}
