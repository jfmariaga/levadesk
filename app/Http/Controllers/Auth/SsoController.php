<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Sociedad;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Support\SsoTenants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\Manager\Config;

class SsoController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function redirect()
    {
        abort_unless(SsoTenants::configurado(), 404);

        return $this->driver()->redirect();
    }

    public function callback(Request $request)
    {
        abort_unless(SsoTenants::configurado(), 404);

        if ($request->has('error')) {
            Log::warning('SSO: Entra devolvio error', ['error' => $request->input('error')]);
            return $this->fallo('No se pudo completar el inicio de sesion con Microsoft.');
        }

        try {
            $sso = $this->driver()->user();
        } catch (\Throwable $e) {
            Log::warning('SSO: fallo al obtener el usuario', ['mensaje' => $e->getMessage()]);
            return $this->fallo('No se pudo completar el inicio de sesion con Microsoft. Intenta de nuevo.');
        }

        $raw = $sso->getRaw();
        $email = strtolower(trim($raw['mail'] ?? $raw['userPrincipalName'] ?? ''));
        $oid = $sso->getId();

        // el tenant sale del id_token que Microsoft entrega directo en el intercambio del codigo
        $tenant = SsoTenants::porTid($this->tid($sso->accessTokenResponseBody['id_token'] ?? null));

        // el tenant debe estar permitido y el correo debe ser de un dominio de ESE tenant
        if (!$tenant || !$email || !SsoTenants::codigoSociedad($tenant, $email)) {
            Log::warning('SSO: tenant o dominio no permitido', ['tenant' => $tenant, 'email' => $email]);
            return $this->fallo('Tu cuenta de Microsoft no esta autorizada para ingresar a LevaDesk.');
        }

        $user = User::where('azure_oid', $oid)->first() ?? User::where('email', $email)->first();

        if ($user && $user->estado == 0) {
            return $this->fallo('Tu cuenta esta inactiva. Contacta con el administrador.');
        }

        if (!$user) {
            $user = $this->crear($tenant, $email, $raw);
        }

        $user->forceFill([
            'azure_oid' => $oid,
            'sso_tenant' => $tenant,
            'last_login_at' => now(),
            'email_verified_at' => $user->email_verified_at ?? now(),
        ])->save();

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    private function driver()
    {
        return Socialite::driver('azure')
            ->scopes(['openid', 'profile', 'email'])
            ->setConfig(new Config(
                config('services.sso.client_id'),
                config('services.sso.client_secret'),
                route('sso.callback'),
                ['tenant' => 'organizations'] // cuentas de trabajo de cualquier directorio; se filtra por tid
            ));
    }

    /** Lee el tid del id_token (JWT). No hace falta verificar la firma: llega directo de Microsoft por TLS. */
    private function tid(?string $idToken): ?string
    {
        $partes = explode('.', (string) $idToken);
        if (count($partes) !== 3) {
            return null;
        }
        $payload = json_decode(base64_decode(strtr($partes[1], '-_', '+/')), true);

        return $payload['tid'] ?? null;
    }

    /** Aprovisionamiento JIT: sociedad segun el dominio y rol por defecto. */
    private function crear(string $tenant, string $email, array $raw): User
    {
        $codigo = SsoTenants::codigoSociedad($tenant, $email);
        $nombre = trim($raw['givenName'] ?? '') ?: ($raw['displayName'] ?? Str::before($email, '@'));

        $user = User::create([
            'name' => $nombre,
            'last_name' => $raw['surname'] ?? null,
            'email' => $email,
            'password' => Hash::make(Str::random(40)),
            'estado' => 1,
            'sociedad_id' => $codigo ? Sociedad::where('codigo', $codigo)->value('id') : null,
        ]);

        $user->assignRole(config('services.sso.rol_defecto'));

        return $user;
    }

    private function fallo(string $mensaje)
    {
        return redirect()->route('login')->withErrors(['email' => $mensaje]);
    }
}
