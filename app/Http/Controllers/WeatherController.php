<?php

namespace App\Http\Controllers;

use App\Services\WeatherService;

class WeatherController extends Controller
{
    public function __construct(protected WeatherService $weather) {}

    public function index()
    {
        $forecast = $this->weather->getForecast();
        $seasons = $this->weather->seasonalGuide();

        return view('weather.index', compact('forecast', 'seasons'));
    }
}
