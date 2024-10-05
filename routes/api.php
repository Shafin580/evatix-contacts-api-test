<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Contacts\ContactController;

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

Route::group(['prefix' => 'v1'], function () {
    // User management
    Route::post('users', [AuthController::class, 'register']);
    Route::post('users/activate', [AuthController::class, 'activate']);
    Route::post('token/auth', [AuthController::class, 'login']);

    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('token/logout', [AuthController::class, 'logout']);

        // Contacts management
        Route::group(
            ['prefix' => 'contacts'],
            function () {
                Route::get('', [ContactController::class, 'index']);
                Route::post('', [ContactController::class, 'store']);
                Route::get('{id}', [ContactController::class, 'show']);
                Route::patch('{id}', [ContactController::class, 'update']);
                Route::delete('{id}', [ContactController::class, 'destroy']);
                Route::group(
                    ['prefix' => 'csv'],
                    function () {
                        Route::get('export', [ContactController::class, 'exportCsv']);
                        Route::post('import', [ContactController::class, 'importCsv']);
                    }
                );
            }
        );
    });
});
