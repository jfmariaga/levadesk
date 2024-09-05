<?php

use App\Http\Controllers\Auth\VerificationController;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Livewire\Livewire;

Route::get('/', function () {
    return view('auth.login');
});



// Habilita la autenticación y las rutas de verificación de correo
Auth::routes(['verify' => true]);

// Ruta para mostrar la notificación de verificación de correo
Route::get('/email/verify', function () {
    return view('auth.verify'); // Vista donde se le pedirá al usuario que verifique su correo
})->middleware('auth')->name('verification.notice');

// Ruta para manejar la verificación de correo
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->middleware(['auth', 'signed'])->name('verification.verify');

// Ruta para reenviar el correo de verificación si el usuario no lo ha recibido
Route::post('/email/resend', [VerificationController::class, 'resend'])
    ->middleware(['auth', 'throttle:6,1'])->name('verification.resend');

Route::get('storage-link', function(){
	Artisan::call('cache:clear');
	Artisan::call('config:cache');
	Artisan::call('route:cache');
	Artisan::call('storage:link');
});

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
    ->middleware('verified')
    ->name('home');


Route::view('sociedad', 'admin.sociedad.index')->name('sociedad');
Route::view('tipo-solicitud', 'admin.solicitud.index')->name('solicitud');
Route::view('categorias', 'admin.categoria.index')->name('categoria');
Route::view('subcategorias', 'admin.subcategoria.index')->name('subcategoria');
Route::view('ans', 'admin.ans.index')->name('ans');
Route::view('estados', 'admin.estado.index')->name('estado');
Route::view('cargos', 'admin.cargo.index')->name('cargo');
Route::view('grupo', 'admin.grupo.index')->name('grupo');
Route::view('ticket', 'admin.ticket.index')->name('ticket');
Route::view('urgencia', 'admin.urgencia.index')->name('urgencia');
Route::view('impacto', 'admin.impacto.index')->name('impacto');
Route::view('gestion', 'admin.gestion.index')->name('gestion');
Route::view('gestionar', 'admin.gestionar.index')->name('gestionar');
Route::view('verTicket', 'admin.ticket.verTicket')->name('verTicket');
Route::view('aprobacion', 'admin.aprobacion.index')->name('aprobacion');
Route::view('aprobar', 'admin.aprobacion.aprobar')->name('aprobar');
Route::view('cambios', 'admin.cambios.index')->name('cambios');
Route::view('cambio', 'admin.cambios.cambios')->name('cambio');
Route::view('sociedades', 'admin.sociedad.aplicaciones')->name('sociedades');
Route::view('usuarios', 'admin.usuarios.index')->name('usuarios');


// Route::get('/search-users', function (Request $request) {
//     $query = $request->get('q');
//     $users = User::where('username', 'like', "%$query%")
//                  ->orWhere('name', 'like', "%$query%")
//                  ->get(['id', 'name']);

//     return response()->json($users);
// });
