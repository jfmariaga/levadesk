<?php

namespace App\Support;

/**
 * Genera el enlace firmado con el que LevaDesk entrega un usuario a SAP-IA. Copiar tal cual a LevaDesk
 * (app/Support/EnlaceSapIa.php); no tiene dependencias y funciona desde PHP 7.4.
 *
 * Formato: "v1.<payload>.<firma>"  con payload = JSON en base64url y firma = HMAC-SHA256(payload, secreto) en base64url.
 * SAP-IA lo acepta una sola vez y solo si no pasaron más de 2 minutos; ver docs/INTEGRACION-LEVADESK.md.
 */
final class EnlaceSapIa
{
    /**
     * @param string      $secreto  el mismo SAPIA_ACCESO_SECRETO que tiene SAP-IA (mínimo 32 caracteres)
     * @param string      $uid      id del usuario en LevaDesk
     * @param string      $nombre   nombre que se mostrará y se guardará junto a cada hallazgo
     * @param int|null    $ticket   ticket que se abrirá al entrar
     * @param int         $vigencia segundos de vigencia (SAP-IA no acepta más de 120)
     */
    public static function generar(string $secreto, string $uid, string $nombre, ?string $email = null, ?int $ticket = null, int $vigencia = 60): string
    {
        $ahora = time();
        $payload = [
            'uid' => $uid,
            'nombre' => $nombre,
            'iat' => $ahora,
            'exp' => $ahora + $vigencia,
            'jti' => bin2hex(random_bytes(12)), // identificador único: es lo que impide reutilizar el enlace
        ];
        if ($email !== null && $email !== '') {
            $payload['email'] = $email;
        }
        if ($ticket !== null) {
            $payload['ticket'] = $ticket;
        }

        $cuerpo = self::base64url(json_encode($payload, JSON_UNESCAPED_UNICODE));

        return 'v1.'.$cuerpo.'.'.self::base64url(hash_hmac('sha256', $cuerpo, $secreto, true));
    }

    private static function base64url(string $bytes): string
    {
        return rtrim(strtr(base64_encode($bytes), '+/', '-_'), '=');
    }
}
