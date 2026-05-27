<?php

namespace App\View\Components;

use App\Models\Listing;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class VerifiedBadge extends Component
{
    public function __construct(
        public Listing $listing,
        public string $size = 'md', // sm | md | lg
        public bool $showLabel = true,
    ) {}

    public function shouldRender(): bool
    {
        return (bool) $this->listing->is_verified;
    }

    public function render(): View|Closure|string
    {
        return view('components.verified-badge');
    }
}
