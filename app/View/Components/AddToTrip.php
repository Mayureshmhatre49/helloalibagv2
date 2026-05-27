<?php

namespace App\View\Components;

use App\Models\Listing;
use App\Models\Trip;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AddToTrip extends Component
{
    /** @var array<int,array{id:int,name:string,has_listing:bool}> */
    public array $trips = [];
    public bool $isAuthenticated;

    public function __construct(
        public Listing $listing,
        public string $variant = 'button', // button | icon
    ) {
        $user = auth()->user();
        $this->isAuthenticated = (bool) $user;

        if ($user) {
            $this->trips = Trip::ownedBy($user->id)
                ->latest()
                ->limit(20)
                ->get(['id', 'name'])
                ->map(fn (Trip $t) => [
                    'id' => $t->id,
                    'name' => $t->name,
                    'has_listing' => $t->listings()->where('listings.id', $listing->id)->exists(),
                ])
                ->all();
        }
    }

    public function render(): View|Closure|string
    {
        return view('components.add-to-trip');
    }
}
