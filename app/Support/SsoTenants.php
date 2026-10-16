<?php

namespace App\Support;

/**
 * Tenants de Entra ID permitidos y los dominios de correo de cada uno (config/services.php => sso.tenants).
 */
final class SsoTenants
{
    public static function dominio(string $email): string
    {
        return strtolower(substr(strrchr($email, '@') ?: '', 1));
    }

    public static function configurado(): bool
    {
        return config('services.sso.activo')
            && config('services.sso.client_id')
            && config('services.sso.client_secret');
    }

    /** Clave del tenant ('panal', 'levapan') cuyo tenant_id es el $tid del token, o null. */
    public static function porTid(?string $tid): ?string
    {
        if (!$tid) {
            return null;
        }
        foreach (config('services.sso.tenants') as $clave => $tenant) {
            if ($tenant['tenant_id'] && strcasecmp($tenant['tenant_id'], $tid) === 0) {
                return $clave;
            }
        }
        return null;
    }

    /** Codigo de sociedad (PN, LP, ...) si el dominio del correo pertenece a ese tenant, o null. */
    public static function codigoSociedad(string $clave, string $email): ?string
    {
        // los dominios llevan puntos, no se pueden usar en la notacion de config()
        return config("services.sso.tenants.$clave.dominios", [])[self::dominio($email)] ?? null;
    }
}
