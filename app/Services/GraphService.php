<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class GraphService
{
    protected $tenant;
    protected $clientId;
    protected $clientSecret;
    protected $user;

    public function __construct()
    {
        $this->tenant       = env('GRAPH_TENANT_ID');
        $this->clientId     = env('GRAPH_CLIENT_ID');
        $this->clientSecret = env('GRAPH_CLIENT_SECRET');
        $this->user         = env('GRAPH_USER'); // ej: analista2.tecnologia@panalsas.com
    }

    /**
     * Obtiene y cachea el access token (1 hora).
     */
    public function getAccessToken()
    {
        return Cache::remember('graph_token', 3600, function () {
            $response = Http::asForm()->post(
                "https://login.microsoftonline.com/{$this->tenant}/oauth2/v2.0/token",
                [
                    'client_id'     => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'scope'         => 'https://graph.microsoft.com/.default',
                    'grant_type'    => 'client_credentials',
                ]
            );

            if ($response->failed()) {
                throw new \Exception("Error obteniendo token: " . $response->body());
            }

            return $response->json()['access_token'];
        });
    }

    /**
     * Lee correos de la bandeja de entrada
     */
    public function getInboxMessages($top = 10)
    {
        $accessToken = $this->getAccessToken();

        $url = "https://graph.microsoft.com/v1.0/users/{$this->user}/mailFolders/inbox/messages";
        $response = Http::withToken($accessToken)->get($url, [
            // solo correos no leídos
            '$filter' => 'isRead eq false',
            // ordenar por fecha de llegada (nuevos primero)
            '$orderby' => 'receivedDateTime desc',
            // máximo de correos a traer
            '$top' => $top,
            // campos a incluir
            '$select' => 'id,subject,from,body,uniqueBody,hasAttachments,receivedDateTime',
        ]);

        if ($response->failed()) {
            throw new \Exception("Error obteniendo correos: " . $response->body());
        }

        return $response->json()['value'];
    }

    /**
     * Obtiene adjuntos de un correo
     */
    public function getAttachments($messageId)
    {
        $accessToken = $this->getAccessToken();

        $url = "https://graph.microsoft.com/v1.0/users/{$this->user}/messages/{$messageId}/attachments";
        $response = Http::withToken($accessToken)->get($url);

        if ($response->failed()) {
            throw new \Exception("Error obteniendo adjuntos: " . $response->body());
        }

        return $response->json()['value'];
    }

    /**
     * Marca un correo como leído
     */
    public function markAsRead($messageId)
    {
        $accessToken = $this->getAccessToken();

        $url = "https://graph.microsoft.com/v1.0/users/{$this->user}/messages/{$messageId}";
        $response = Http::withToken($accessToken)->patch($url, [
            'isRead' => true,
        ]);

        return $response->successful();
    }
}
