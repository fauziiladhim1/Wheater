<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area; // Jika Anda ingin mengambil data area dari database
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Map',
        ];

        $areas = Area::all();
        return view('weather.index', [
            'title' => 'Weather Map',
            'areas' => $areas,
        ]);

    }

    public function getWeatherData(Request $request)
    {
        $lat = $request->input('lat');
        $lon = $request->input('lon');
        $apiKey = config('services.openweather.key');

        $response = Http::get("https://api.openweathermap.org/data/2.5/weather?lat={$lat}&lon={$lon}&appid={$apiKey}&units=metric");

        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json(['error' => 'Gagal mengambil data cuaca'], $response->status());
        }
    }

    // Fungsi untuk menampilkan detail area dengan cuaca di modal
    public function showAreaWeather(Area $area)
    {
        // Data area sudah di-load otomatis oleh Route Model Binding
        $geometry = json_decode($area->geom); // Mengambil geometri dari model

        // Ambil koordinat untuk mendapatkan cuaca. Jika polygon/polyline, ambil centroid/start/end point
        $lat = null;
        $lon = null;

        if ($geometry->type === 'Point') {
            $lon = $geometry->coordinates[0];
            $lat = $geometry->coordinates[1];
        } elseif ($geometry->type === 'LineString') {
            // Ambil titik tengah atau titik awal/akhir untuk polyline
            $midIndex = floor(count($geometry->coordinates) / 2);
            $lon = $geometry->coordinates[$midIndex][0];
            $lat = $geometry->coordinates[$midIndex][1];
        } elseif ($geometry->type === 'Polygon') {
            // Hitung centroid untuk polygon
            $geojsonFeature = [
                'type' => 'Feature',
                'geometry' => $geometry
            ];
            // Menggunakan library seperti `turf.js` di frontend atau implementasi manual centroid di backend
            // Untuk kesederhanaan, kita bisa ambil titik pertama dari koordinat pertama sebagai estimasi
            $lon = $geometry->coordinates[0][0][0];
            $lat = $geometry->coordinates[0][0][1];
        }

        $weatherData = null;
        if ($lat !== null && $lon !== null) {
            $apiKey = config('services.openweather.key');
            $response = Http::get("https://api.openweathermap.org/data/2.5/weather?lat={$lat}&lon={$lon}&appid={$apiKey}&units=metric");
            if ($response->successful()) {
                $weatherData = $response->json();
            }
        }

        return response()->json([
            'area' => $area,
            'geometry' => $geometry, // Mengirim geometri yang sudah di-decode
            'weather' => $weatherData
        ]);
    }

    public function getAreas()
{
    $areas = Area::select('id', 'name', 'geom')->get();
    return response()->json($areas);
}
}
