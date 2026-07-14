<?php

namespace App\Http\Controllers;

use App\Models\WilayahDistrict;
use App\Models\WilayahProvince;
use App\Models\WilayahRegency;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class WilayahOptionsController extends Controller
{
    public function regencies(WilayahProvince $province): JsonResponse
    {
        // Cache a plain array, not an Eloquent Collection: this app's cache config
        // (serializable_classes => false) rejects unserializing objects from the
        // database cache store, silently corrupting anything object-shaped on read-back.
        return response()->json(
            Cache::rememberForever(
                "wilayah:regencies:{$province->id}",
                fn () => $province->regencies()->orderBy('name')->get(['id', 'name'])->toArray(),
            )
        );
    }

    public function districts(WilayahRegency $regency): JsonResponse
    {
        return response()->json(
            $regency->districts()->orderBy('name')->get(['id', 'name'])
        );
    }

    public function villages(WilayahDistrict $district): JsonResponse
    {
        return response()->json(
            $district->villages()->orderBy('name')->get(['id', 'name'])
        );
    }
}
