<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\EquiposController;
use App\Http\Controllers\TicketsController;

Route::middleware(['auth:usuarios', 'check.user.type:0'])->group(function () {
    Route::get('/usuarios', [UsuariosController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/{id}/editar', [UsuariosController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{id}', [UsuariosController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{id}', [UsuariosController::class, 'destroy'])->name('usuarios.destroy');


    Route::get('/equipos', [EquiposController::class, 'indexPC'])->name('equipos.index');
    Route::get('/impresoras', [EquiposController::class, 'indexImpresoras'])->name('impresoras.index');

    Route::post('/equipos', [EquiposController::class, 'store'])->name('equipos.store');
    Route::put('/equipos/{id}', [EquiposController::class, 'update'])->name('equipos.update');
    Route::delete('/equipos/{id}', [EquiposController::class, 'destroy'])->name('equipos.destroy');
    Route::get('/equipos/{id}/historial-reparaciones', [EquiposController::class, 'historialReparaciones'])->name('equipos.historialReparaciones');
    Route::get('/equipos/reporte-pdf', [EquiposController::class, 'reportePdf'])->name('equipos.reportePdf');
    Route::get('/equipos/reporte-excel', [EquiposController::class, 'reporteExcel'])->name('equipos.reporteExcel');
    Route::get('/equipos/{id}/reporte-pdf', [EquiposController::class, 'reportePdfIndividual'])->name('equipos.reportePdfIndividual');
    Route::get('/equipos/{id}/exportar-excel', [EquiposController::class, 'exportarExcelIndividual'])->name('equipos.exportarExcelIndividual');
    Route::get('/equipos/check-numero-serie', [EquiposController::class, 'checkNumeroSerie']);
    Route::get('/impresoras/reporte-pdf', [EquiposController::class, 'reportePdfImpresora'])->name('impresoras.reportePdf');
    Route::get('/impresoras/reporte-excel', [EquiposController::class, 'reporteExcelImpresora'])->name('impresoras.reporteExcel');
    Route::get('/impresoras/{id}/reporte-pdf', [EquiposController::class, 'reportePdfIndividualImpresora'])->name('impresoras.reportePdfIndividual');
    Route::get('/impresoras/{id}/exportar-excel', [EquiposController::class, 'exportarExcelIndividualImpresora'])->name('impresoras.exportarExcelIndividual');
    Route::get('/tickets', [TicketsController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/tecnico', [TicketsController::class, 'index'])->name('tickets.tecnico');
    Route::get('/tickets/{ticket}', [TicketsController::class, 'showForTecnico'])->name('tickets.show.tecnico');
    Route::patch('/tickets/{ticket}/solucionar', [TicketsController::class, 'solucionar'])->name('tickets.solucionar');
    Route::get('/tickets/filtros', [TicketsController::class, 'filtrarTickets']);
});

Route::middleware(['auth:usuarios'])->group(function () {
    Route::get('/inicio', [InicioController::class, 'inicio'])->name('menus.inicio');
    Route::get('/tickets', [TicketsController::class, 'index'])->name('tickets.index');
    Route::post('/tickets', [TicketsController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [TicketsController::class, 'show'])->name('tickets.show');
    Route::put('/tickets/{id}', [TicketsController::class, 'update'])->name('tickets.update');
    Route::delete('/tickets/{id}', [TicketsController::class, 'destroy'])->name('tickets.destroy');
    Route::get('/equipos/sede/{id}', [TicketsController::class, 'getEquiposPorSede']);
    Route::get('/equipos/search', [EquiposController::class, 'search'])->name('equipos.search');
});

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::get('register', [RegisterController::class, 'showRegisterForm'])->name('register.show');
Route::post('register', [RegisterController::class, 'register'])->name('register');
Route::redirect('/', '/inicio');
