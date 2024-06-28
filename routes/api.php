<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get("/app", [App\Http\Controllers\AppController::class, "app"])->name("app");
Route::get("/call", [App\Http\Controllers\CallController::class, "call"])->name("call");
Route::get("/email", [App\Http\Controllers\EmailController::class, "email"])->name("email");
Route::get("/advanced", [App\Http\Controllers\AdvancedController::class, "advanced"])->name("advanced");
Route::get("/accueil", [App\Http\Controllers\AcceuilController::class, "acceuil"])->name("accueil");

Route::get("mainMenu", [App\Http\Controllers\MainMenuController::class, "getAll"]);
Route::get("secondaryMenu", [App\Http\Controllers\SecondaryMenuController::class, "getAll"]);
Route::get("group", [App\Http\Controllers\GroupController::class, "getAll"]);
Route::get("entryPoint", [App\Http\Controllers\EntryPointController::class, "getAll"]);
Route::put("entryPoint", [App\Http\Controllers\EntryPointController::class, "update"]);
Route::get("entryPointType", [App\Http\Controllers\EntryPointTypeController::class, "getAll"]);
Route::get("queueCallWebService", [App\Http\Controllers\QueueCallController::class, "getAllQueueWebServices"]);
Route::get("svis", [App\Http\Controllers\SvisController::class, "getAll"]);


