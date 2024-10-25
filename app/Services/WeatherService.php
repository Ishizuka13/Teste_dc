<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class WeatherService
{
    protected $apiKey;
    protected $apiUrl;

    public function __construct()
    {
        $this->apiKey = env('WEATHER_API_KEY');
        $this->apiUrl = env('WEATHER_API_URL', 'https://api.openweathermap.org/data/2.5/forecast');
    }

    public function getWeatherForecast($city, $checkInDate)
    {
        try {
            $response = Http::get($this->apiUrl, [
                'q' => $city,
                'appid' => $this->apiKey,
                'units' => 'metric',
                'cnt' => 40
            ]);

            if ($response->failed()) {
                Log::warning("Weather API request failed for city: $city");
                return null;
            }

            $forecastData = $response->json();
            $targetDate = Carbon::parse($checkInDate)->format('Y-m-d');

            if (isset($forecastData['list'])) {
                $filteredForecast = collect($forecastData['list'])->filter(function ($forecast) use ($targetDate) {
                    return Carbon::createFromTimestamp($forecast['dt'])->format('Y-m-d') === $targetDate;
                })->first();

                return $filteredForecast;
            }

            Log::warning("Unexpected forecast data format for city: $city");
            return null;
        } catch (Exception $e) {
            Log::error('Erro ao obter previsão do tempo: ' . $e->getMessage());
            return null;
        }
    }
}
