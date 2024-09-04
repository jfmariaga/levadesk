<?php

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

Auth::routes();

Route::get('storage-link', function(){
	Artisan::call('cache:clear');
	Artisan::call('config:cache');
	Artisan::call('route:cache');
	Artisan::call('storage:link');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

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


// Route::get('/search-users', function (Request $request) {
//     $query = $request->get('q');
//     $users = User::where('username', 'like', "%$query%")
//                  ->orWhere('name', 'like', "%$query%")
//                  ->get(['id', 'name']);

//     return response()->json($users);
// });
