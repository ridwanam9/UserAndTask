<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('/login', [AuthController::class, 'login']);

// Route yang butuh autentikasi dan status aktif
Route::middleware(['auth:sanctum', 'checkUserStatus', 'logRequest'])->group(function () {
    // Tambahkan rute lain di sini, misalnya:
    // Route::get('/users', [UserController::class, 'index']);
});