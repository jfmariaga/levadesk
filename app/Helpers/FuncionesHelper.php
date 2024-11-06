<?php
namespace App\Helpers;

use Carbon\Carbon;
use App\Models\DiasFestivos;

class FuncionesHelper
{
    public static function esDiaHabil($fecha)
    {
        $esFestivo = DiasFestivos::where('fecha', $fecha->format('Y-m-d'))->exists();
        $esHabil = !$esFestivo && $fecha->isWeekday() && $fecha->hour >= 8 && $fecha->hour < 21;
        return $esHabil;
    }
}
