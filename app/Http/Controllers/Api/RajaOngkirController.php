<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class RajaOngkirController extends Controller
{
    protected $apiKey;
    protected $baseUrl;
    protected $originCityId; // Kediri city ID

    public function __construct()
    {
        $this->baseUrl = config('services.rajaongkir.base_url', 'https://api.rajaongkir.com/starter');
        $this->originCityId = 174; // ID for Kediri city
    }

    /**
     * Get all provinces from Raja Ongkir API
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProvinces()
    {
        // dd('masuk ke getProvinces');
        try {

            $data = Cache::remember('rajaongkir_provinces', 86400, function () {
                $response = Http::withHeaders([
                    'key' => 'bfc73a5ac233d6ea88fb80d6b59baeab'
                ])->get($this->baseUrl . '/province');
                // dd($response->body());


                $data = $response->json();

                if ($response->successful() && isset($data['rajaongkir']['status']['code']) && $data['rajaongkir']['status']['code'] == 200) {
                    return $data['rajaongkir']['results'];
                }

                return null;
            });

            if ($data === null) {
                return response()->json(['error' => 'Failed to fetch provincess'], 500);
            }

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    /**
     * Get cities by province ID from Raja Ongkir API
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCities(Request $request)
    {
        $provinceId = $request->input('province');

        // Cache cities by province for 24 hours
        $cacheKey = 'rajaongkir_cities_' . $provinceId;

        return Cache::remember($cacheKey, 86400, function () use ($provinceId) {
            try {
                $response = Http::withHeaders([
                    'key' => 'bfc73a5ac233d6ea88fb80d6b59baeab'
                ])->get($this->baseUrl . '/city', [
                    'province' => $provinceId
                ]);

                $data = $response->json();

                if ($response->successful() && isset($data['rajaongkir']['status']['code']) && $data['rajaongkir']['status']['code'] == 200) {
                    return response()->json($data['rajaongkir']['results']);
                }

                return response()->json(['error' => 'Failed to fetch cities'], 500);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        });
    }

    /**
     * Get districts by city ID from Raja Ongkir API
     * Note: This requires Raja Ongkir Pro subscription
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDistricts(Request $request)
    {
        $cityId = $request->input('city');

        // Cache districts by city for 24 hours
        $cacheKey = 'rajaongkir_districts_' . $cityId;

        return Cache::remember($cacheKey, 86400, function () use ($cityId) {
            try {
                // Use Pro API for subdistricts if available, fallback to mocked data if not
                $proBaseUrl = str_replace('starter', 'pro', $this->baseUrl);

                $response = Http::withHeaders([
                    'key' => 'bfc73a5ac233d6ea88fb80d6b59baeab'
                ])->get($proBaseUrl . '/subdistrict', [
                    'city' => $cityId
                ]);

                $data = $response->json();

                if ($response->successful() && isset($data['rajaongkir']['status']['code']) && $data['rajaongkir']['status']['code'] == 200) {
                    return response()->json($data['rajaongkir']['results']);
                } else {
                    // If the API fails (possibly because we don't have Pro subscription),
                    // return mock data or an error message
                    return response()->json(['error' => 'Could not fetch districts. Subscription may be required.'], 500);
                }
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        });
    }

    /**
     * Calculate shipping costs between Kediri (origin) and destination
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCost(Request $request)
    {
        try {
            $request->validate([
                'destination' => 'required',
                'weight' => 'required|numeric|min:1',
                'courier' => 'required|in:jne,pos,tiki'
            ]);

            $response = Http::withHeaders([
                'key' => '2472843d6a402ff2319489c07cc5cf73'
            ])->post($this->baseUrl . '/cost', [
                'origin' => $this->originCityId, // Kediri city ID
                'destination' => $request->destination,
                'weight' => $request->weight,
                'courier' => $request->courier
            ]);

            $data = $response->json();

            if ($response->successful() && isset($data['rajaongkir']['status']['code']) && $data['rajaongkir']['status']['code'] == 200) {
                return response()->json($data['rajaongkir']['results']);
            }

            return response()->json(['error' => 'Failed to calculate shipping cost'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get origin city details (Kediri)
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOriginCity()
    {
        return response()->json([
            'city_id' => $this->originCityId,
            'city_name' => 'Kediri',
            'province' => 'Jawa Timur',
            'province_id' => 11,
            'type' => 'Kota'
        ]);
    }
}
