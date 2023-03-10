<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ResponseController;

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
// Route::get('/login', [ReportController::class, 'login'])->name('login');
// Route::post('/auth', [ReportController::class, 'auth'])->name('auth');
    // Route::get('/', [ReportController::class, 'index'])->name('home');
//     Route::post('/kirim-pengaduan', [ReportController::class, 'store'])->name('kirim_pengaduan');
//     Route::get('/edit/{id}', [ReportController::class, 'edit'])->name('edit');
//     Route::delete('/hapus/{id}', [ReportController::class, 'destroy'])->name('delete');
//     Route::get('/dashboard', [ReportController::class, 'dashboard'])->name('dashboard');
//     Route::get('/logout', [ReportController::class, 'logout'])->name('logout');

Route::get('/', [ReportController::class, 'index'])->name('home');

Route::get('/login', function () {
    return view('login');
})->name ('login');

Route::middleware(['IsLogin' ,'CekRole:petugas'])->group(function(){
    Route::get('/data/petugas', [ReportController::class, 'detailPetugas'])->name('data.petugas');
    Route::get('/response/edit/{report_id}', [ResponseController::class, 'edit'])->name('response.edit');
    Route::patch('/response/update/{report_id}', [ResponseController::class, 'update'])->name('response.update');
    
});

Route::middleware(['IsLogin', 'CekRole:admin,petugas'])->group(function(){
    Route::get('/logout', [ReportController::class, 'logout'])->name('logout');
});

Route::middleware(['IsLogin', 'CekRole:admin'])->group(function(){
Route::get('/data', [ReportController::class, 'data'])->name ('data');
Route::get('/export/pdf', [ReportController::class, 'exportPDF'])->name('export-pdf');
Route::get('/down/pdf/{id}', [ReportController::class, 'downPDF'])->name('down.pdf');
Route::delete('/hapus/{id}', [ReportController::class, 'destroy'])->name('delete');
Route::get('/export/excel', [ReportController::class, 'exportExcel'])->name('export.excel');

});

Route::post('store', [ReportController::class, 'store'])->name('store');
Route::post('/auth', [ReportController::class, 'auth'])->name('auth');