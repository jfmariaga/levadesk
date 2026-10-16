<?php

namespace App\Http\Controllers;

use App\Support\EnlaceSapIa;

class SapIaController extends Controller
{
    /** Entrega al usuario autenticado a SAP-IA con un enlace firmado de un solo uso. */
    public function ir(?int $ticket = null)
    {
        $url = config('services.sapia.url');
        $secreto = config('services.sapia.secreto');
        abort_if(empty($url) || empty($secreto), 503, 'SAP-IA no está configurada.');

        $u = auth()->user();
        $token = EnlaceSapIa::generar($secreto, (string) $u->id, trim($u->name.' '.($u->apellido ?? '')), $u->email, $ticket);

        return redirect()->away(rtrim($url, '/').'/acceso?token='.$token);
    }
}
