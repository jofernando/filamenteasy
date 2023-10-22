<?php

use App\Models\Evento;
use App\Models\Modalidade;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $eventos = Evento::all();
    return view('eventos.index', compact('eventos'));
})->name('eventos.index');

Route::get('/eventos/{evento}', function (Evento $evento) {
    return view('eventos.show', compact('evento'));
})->name('eventos.show');

Route::get('/eventos/{evento}/modalidade/{modalidade}/trabalhos/create', function (Evento $evento, Modalidade $modalidade) {
    return view('trabalhos.create', compact('evento', 'modalidade'));
})->name('trabalhos.create');
