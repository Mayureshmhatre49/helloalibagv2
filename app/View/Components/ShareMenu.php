<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ShareMenu extends Component
{
    public string $shareUrl;
    public string $shareText;
    public string $whatsappUrl;
    public string $emailUrl;
    public string $twitterUrl;
    public string $facebookUrl;

    public function __construct(
        ?string $url = null,
        ?string $text = null,
        public string $variant = 'button', // button | inline-row
        public string $label = 'Share',
    ) {
        $this->shareUrl = $url ?? request()->fullUrl();
        $this->shareText = $text ?? 'Check this out on Hello Alibaug';

        $encodedUrl = rawurlencode($this->shareUrl);
        $encodedText = rawurlencode($this->shareText);

        $this->whatsappUrl = "https://wa.me/?text={$encodedText}%20{$encodedUrl}";
        $this->emailUrl = 'mailto:?subject=' . rawurlencode($this->shareText) . "&body={$encodedText}%0A%0A{$encodedUrl}";
        $this->twitterUrl = "https://twitter.com/intent/tweet?text={$encodedText}&url={$encodedUrl}";
        $this->facebookUrl = "https://www.facebook.com/sharer/sharer.php?u={$encodedUrl}";
    }

    public function render(): View|Closure|string
    {
        return view('components.share-menu');
    }
}
