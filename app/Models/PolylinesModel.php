<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PolylinesModel extends Model
{
    protected $table = 'polylines';

    protected $guarded = ['id'];

    public function geojson_polylines()
    {
        // Perbaikan query untuk menyertakan user_created
        $polylines = $this
            ->select(DB::raw('polylines.id,
            ST_AsGeoJSON(polylines.geom) as geom,
            polylines.name,
            polylines.description,
            polylines.image,
            ST_Length(polylines.geom, true) as length_m,
            ST_Length(polylines.geom, true)/1000 as length_km,
            polylines.created_at,
            polylines.updated_at,
            polylines.user_id,
            users.name as user_created'))
            ->leftJoin('users', 'polylines.user_id', '=', 'users.id')
            ->get();

        // Initialize GeoJSON structure
        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        // Iterate over the fetched polylines to build GeoJSON features
        foreach ($polylines as $p) {
            $feature = [
                'type' => 'Feature',
                'geometry' => json_decode($p->geom),
                'properties' => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'description' => $p->description,
                    'created_at' => $p->created_at,
                    'updated_at' => $p->updated_at,
                    'length_m' => $p->length_m,
                    'length_km' => $p->length_km,
                    'image' => $p->image,
                    'user_created' => $p->user_created,
                    'user_id' => $p->user_id,
                ],
            ];

            array_push($geojson['features'], $feature);
        }

        return $geojson;
    }

    public function geojson_polyline($id)
    {
        // Corrected query to fetch the data
        $polylines = $this
            ->select(DB::raw('id, st_asgeojson(geom) as geom, name, description, image,
            st_length(geom, true) as length_m, st_length(geom, true)/1000 as length_km, created_at, updated_at'))
            ->where('id', $id)
            ->get();

        // Initialize GeoJSON structure
        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        // Iterate over the fetched polylines to build GeoJSON features
        foreach ($polylines as $p) {
            $feature = [
                'type' => 'Feature',
                'geometry' => json_decode($p->geom), // Decode geometry to JSON object
                'properties' => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'description' => $p->description,
                    'created_at' => $p->created_at,
                    'updated_at' => $p->updated_at,
                    'length_m' => $p->length_m,
                    'length_km' => $p->length_km,
                    'image' => $p->image,
                ],
            ];

            // Append the feature to GeoJSON features array
            array_push($geojson['features'], $feature);
        }

        // Return the final GeoJSON object
        return $geojson;
    }
}
