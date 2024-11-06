<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Helpers\FuncionesHelper;
use App\Models\DiasFestivos;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FuncionesHelperTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_dia_habil_en_horario_laboral()
    {
        // Día hábil dentro del horario laboral (martes, 10 a.m.)
        $fecha = Carbon::create(2024, 11, 5, 10, 0, 0); // Asegúrate de que no sea festivo
        $this->assertTrue(FuncionesHelper::esDiaHabil($fecha));
    }

    /** @test */
    public function test_dia_no_habil_fuera_de_horario_laboral()
    {
        // Día hábil pero fuera del horario laboral (martes, 7 p.m.)
        $fecha = Carbon::create(2024, 11, 5, 19, 0, 0);
        $this->assertFalse(FuncionesHelper::esDiaHabil($fecha));
    }

    /** @test */
    public function test_dia_no_habil_festivo()
    {
        // Inserta un día festivo en la base de datos
        DiasFestivos::create(['fecha' => '2024-11-05']); // Simulamos que es un festivo
        $fecha = Carbon::create(2024, 11, 5, 10, 0, 0); // Festivo en horario laboral
        $this->assertFalse(FuncionesHelper::esDiaHabil($fecha));
    }

    /** @test */
    public function test_dia_no_habil_fin_de_semana()
    {
        // Fin de semana (sábado a las 10 a.m.)
        $fecha = Carbon::create(2024, 11, 9, 10, 0, 0);
        $this->assertFalse(FuncionesHelper::esDiaHabil($fecha));
    }
}
