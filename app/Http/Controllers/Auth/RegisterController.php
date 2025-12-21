<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Sociedad;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */

    public function showRegistrationForm()
    {
        // Carga todas las sociedades desde la base de datos
        $sociedades = Sociedad::all();

        // Retorna la vista 'auth.register' y le pasa la variable $sociedades
        return view('auth.register', compact('sociedades'));
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', function ($attribute, $value, $fail) {
                $allowedDomains = ['panalsas.com', 'levapan.com', 'levapan.com.do', 'levapan.com.ec', 'levacolsas.com', 'levapan.com.pe'];
                $emailDomain = substr(strrchr($value, "@"), 1);
                if (!in_array($emailDomain, $allowedDomains)) {
                    $fail('Debes de ingresar un correo corporativo');
                }
            }],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'sociedad_id' => ['required', 'exists:sociedades,id'],  // Validación para sociedad
            'area' => ['required', 'string', 'max:255'],  // Validación para sociedad
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'estado' => true, // Por defecto, el estado es 'activo'
            'sociedad_id' => $data['sociedad_id'], // Asigna la sociedad
            'area' => $data['area'],
        ]);

        // Asigna el rol por defecto de usuario
        $user->assignRole('Usuario');

        return $user;
    }
}
