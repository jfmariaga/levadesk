<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'sapia' => [
        'url' => env('SAPIA_URL'),
        'secreto' => env('SAPIA_SECRETO'),
    ],

    // Entrada exigida por el proveedor de Socialite; las credenciales reales se fijan por tenant en SsoController
    'azure' => [
        'client_id' => 'definido-por-tenant',
        'client_secret' => 'definido-por-tenant',
        'redirect' => 'definido-por-tenant',
    ],

    // SSO Microsoft Entra ID: una sola app multi-tenant. El tenant del usuario se valida con el 'tid' del token.
    'sso' => [
        'activo' => env('SSO_ACTIVO', true),
        'solo' => env('SSO_SOLO', false), // true oculta el formulario de contrasena
        'rol_defecto' => env('SSO_ROL_DEFECTO', 'Usuario'),
        'client_id' => env('SSO_CLIENT_ID', env('SSO_LEVAPAN_CLIENT_ID')),
        'client_secret' => env('SSO_CLIENT_SECRET', env('SSO_LEVAPAN_SECRET')),
        // tenants permitidos y, por cada uno, los dominios de correo que pueden entrar con su codigo de sociedad
        'tenants' => [
            'panal' => [
                'tenant_id' => env('SSO_PANAL_TENANT'),
                'dominios' => [
                    'panalsas.com' => 'PN',
                ],
            ],
            'levapan' => [
                'tenant_id' => env('SSO_LEVAPAN_TENANT'),
                'dominios' => [
                    'levapan.com' => 'LP',
                    'levacolsas.com' => 'LC',
                    'levapan.com.ec' => 'EC',
                    'levapan.com.do' => 'RD',
                ],
            ],
        ],
    ],

];
