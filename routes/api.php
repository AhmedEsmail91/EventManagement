<?php
use App\Http\Controllers\Api\AttendeeController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
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
// access the pages using the routes just write middleware('auth:sanctum')->http_method.......
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/login',[AuthController::class,'login']);

Route::middleware('auth:sanctum')->post('/logout',[AuthController::class,'logout']);
Route::apiResource('events',EventController::class);

Route::apiResource('events.attendees',AttendeeController::class)
    ->except(['update']) // --> we don't need to edit the attendees so exclude it from the route:list
    // ->scoped(['attendee'=>'event']) // attendee resources is always part of an event.
; 
