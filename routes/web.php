<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\JadwalController;

Route::get('/', [PublicController::class, 'index']);
Route::get('/daftar', [PublicController::class, 'daftar']);
Route::post('/daftar', [PublicController::class, 'storeDaftar'])->middleware('throttle:10,1');
Route::get('/cek-status', [PublicController::class, 'cekStatus']);
Route::post('/cek-status', [PublicController::class, 'cariStatus']);
Route::get('/galeri', [PublicController::class, 'galeri']);
Route::get('/tiket/{id}', [PublicController::class, 'downloadTiket']);
Route::get('/jadwal', [JadwalController::class, 'index']);

Route::get('/admin/login', [AdminController::class, 'showLogin']);
Route::post('/admin/login', [AdminController::class, 'login'])->middleware('throttle:5,1');

Route::middleware(['admin'])->group(function () {
    Route::get('/admin/logout', [AdminController::class, 'logout']);
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/admin/lomba', [AdminController::class, 'lomba']);
    Route::post('/admin/lomba', [AdminController::class, 'storeLomba']);
    Route::post('/admin/lomba/{id}/delete', [AdminController::class, 'deleteLomba']);
    Route::post('/admin/lomba/{id}/randomize', [AdminController::class, 'randomizeSesi']);
    Route::post('/admin/lomba/{id}/reset-sesi', [AdminController::class, 'resetSesi']);
    Route::get('/admin/peserta-lomba', [AdminController::class, 'pesertaLomba']);
    Route::get('/admin/pendaftar', [AdminController::class, 'pendaftar']);
    Route::post('/admin/pendaftar/{id}', [AdminController::class, 'verifikasiPendaftar']);
    Route::post('/admin/pendaftar/{id}/update', [AdminController::class, 'updatePendaftar']);
    Route::post('/admin/pendaftar/{id}/delete', [AdminController::class, 'deletePendaftar']);
    Route::get('/admin/export-excel', [AdminController::class, 'exportExcel']);
    Route::get('/admin/export-pdf', [AdminController::class, 'exportPdf']);
    Route::get('/admin/audit-logs', [AdminController::class, 'auditLogs']);

    Route::get('/admin/jadwal', [JadwalController::class, 'adminIndex']);
    Route::post('/admin/jadwal', [JadwalController::class, 'store']);
    Route::post('/admin/jadwal/{id}/update', [JadwalController::class, 'update']);
    Route::post('/admin/jadwal/{id}/delete', [JadwalController::class, 'destroy']);
});
