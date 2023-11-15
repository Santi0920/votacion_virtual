<?php

use App\Http\Controllers\AgencyEntrance;
use App\Http\Controllers\RegistroCC;
use App\Http\Controllers\SessionsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/




//LOGIN
Route::get('/login', [SessionsController::class, 'login'])
//->middleware('guest')
->name('login.index');

Route::post('/login', [SessionsController::class, 'login_post'])
->name('login');

Route::get('logout', [SessionsController::class, 'destroy'])
->middleware('auth')
->name('login.destroy');

//ADMIN
//entrada
Route::get('/', function () {
    return view('admin/registrocc');
})->middleware('auth.admin');

Route::post('/crear', [RegistroCC::class, 'create'])
->middleware('auth.admin')
->name('cc.create');


Route::get('/datatable', [RegistroCC::class, 'data'])
->middleware('auth.admin')
->name('datatable.cc');

Route::get('/admininfo', [RegistroCC::class, 'data2'])
->middleware('auth.admin')
->name('usuarios');

Route::post('admin/registrar_rol', [RegistroCC::class, 'store'])
->name('rol.store');

Route::get('/delegados', function () {
    return view('admin/delegados');
})->middleware('auth.admin');

Route::get('/datatabledelegate', [RegistroCC::class, 'datadelegate'])
->middleware('auth.admin')
->name('datatable.delegate');

Route::post('/createdelegate', [RegistroCC::class, 'createdelegate'])
->middleware('auth.admin')
->name('create.delegate');

Route::post('updatevotes-{id}', [RegistroCC::class, 'updatedelegate'])
->middleware('auth.admin')
->name('update.delegatevotes');

Route::get('/eliminate-{id}', [RegistroCC::class, 'delete'])
->middleware('auth.admin')
->name('delegate.delete');



Route::get('¡PDF-GENERADO-1!', [RegistroCC::class, 'imprimir2'])
->middleware('auth.admin')
->name('imprimir.1');

Route::get('¡PDF-GENERADO-2!', [RegistroCC::class, 'imprimir3'])
->middleware('auth.admin')
->name('imprimir.2');

Route::get('¡PDF-GENERADO-3!', [RegistroCC::class, 'imprimir4'])
->middleware('auth.admin')
->name('imprimir.3');

Route::get('¡PDF-GENERADO-4!', [RegistroCC::class, 'imprimir5'])
->middleware('auth.admin')
->name('imprimir.4');

Route::get('¡PDF-GENERADO-5!', [RegistroCC::class, 'imprimir'])
->middleware('auth.admin')
->name('imprimir.5');



//AGENCIAS
Route::get('/entrance', function () {
    return view('agencias/registrocc2');
})->middleware('auth.agency');

Route::post('/register', [AgencyEntrance::class, 'create'])
->middleware('auth.agency')
->name('cc.createagency');


Route::get('/datatableagency', [AgencyEntrance::class, 'data'])
->middleware('auth.agency')
->name('datatable.agency');

Route::post('/votos-{id}', [AgencyEntrance::class, 'mostrarcandidato'])
->middleware('auth.agency')
->name('mostrarcandidato.agency');

// Votos
Route::get('/votos', function () {
    return view('agencias/votos');
});


Route::get('/candidato', function () {
    return view('agencias/candidato');
});

Route::get('¡PDF-GENERADO-AGENCIA!', [AgencyEntrance::class, 'imprimir'])
->middleware('auth.agency')
->name('imprimir2');

