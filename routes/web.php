<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TableController;
use App\Http\Controllers\PointsController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\PolygonsController;
use App\Http\Controllers\PolylinesController;

Route::get('/', [PublicController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/map', [PointsController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('map');
Route::get('/table', [TableController::class, 'index'])->name('table');

Route::resource('points', PointsController::class);
Route::resource('polylines', PolylinesController::class);
Route::resource('polygons', PolygonsController::class);

Route::get('/weather-map', [WeatherController::class, 'index'])->name('weather.map');
Route::get('/api/areas', [WeatherController::class,'getAreas'])->name('api.areas');
Route::get('/api/weather', [WeatherController::class, 'getWeatherData'])->name('api.weather');
Route::get('/api/area-weather/{area}', [WeatherController::class, 'showAreaWeather'])->name('api.area-weather');

require __DIR__ . '/auth.php';
