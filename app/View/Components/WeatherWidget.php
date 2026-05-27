<?php

namespace App\View\Components;

use App\Services\WeatherService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class WeatherWidget extends Component
{
    public array $forecast;

    public function __construct(
        WeatherService $weatherService,
        public string $variant = 'compact', // compact | hero | inline
    ) {
        $this->forecast = $weatherService->getForecast();
    }

    public function render(): View|Closure|string
    {
        return view('components.weather-widget');
    }
}
