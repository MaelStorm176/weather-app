<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * @todo IL FAUT PENSER AUSSI A WHITELIST IP CETTE URL
 */
Route::get('weather', function (){
    $apiKey = config('services.tomorrow.key');

    $lat = \request('lat');
    $lng = \request('lng');
    $response = Http::get("https://api.tomorrow.io/v4/timelines",[
        'location' => $lat.','.$lng,
        'fields' => 'temperature,temperatureApparent,weatherCodeFullDay',
        'timesteps' => '1d',
        'units' => 'metric',
        'apikey' => $apiKey
    ]);
    return $response->json();
});
