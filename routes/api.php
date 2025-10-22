<?php

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CRM\TicketController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/tickets/export', [AdminController::class, 'exportTickets']);
Route::get('/tickets', [AdminController::class, 'ticket']);
Route::get('/tickets/{id}', [AdminController::class, 'showTicket']);
Route::delete('/tickets/{id}', [AdminController::class, 'destroyTicket']);




//add agent
Route::post('/agents', [AdminController::class, 'addAgent']);
Route::get('/agents', [AdminController::class, 'agents']);
Route::get('/agents/export', [AdminController::class, 'exportAgents']);
Route::post('/agents/import', [AdminController::class, 'importAgents']);
Route::delete('/agents/{id}', [AdminController::class, 'destroyAgent']);
Route::put('/agents/{id}', [AdminController::class, 'updateAgent']);
Route::get('/agents/{id}', [AdminController::class, 'getAgentById']);



