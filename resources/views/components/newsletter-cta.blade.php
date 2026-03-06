<div class="my-10 p-8 rounded-3xl bg-charcoal text-white text-center relative overflow-hidden shadow-xl shadow-black/5">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 24px 24px;"></div>
    
    <div class="relative z-10 max-w-md mx-auto">
        <span class="inline-block p-3 rounded-2xl bg-white/10 text-primary mb-4 backdrop-blur-sm">
            <span class="material-symbols-outlined text-[24px] leading-none">mail</span>
        </span>
        <h3 class="font-display text-2xl font-bold mb-2">Get Alibaug Insights</h3>
        <p class="text-white/70 text-sm mb-6">Join 5,000+ others receiving our best travel guides, hidden gems, and exclusive villa deals.</p>
        
        <form onsubmit="event.preventDefault(); alert('Newsletter subscription submitted! (Demo)');" class="flex flex-col sm:flex-row gap-2">
            <input type="email" placeholder="Your email address" required class="flex-1 px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder:text-white/50 focus:outline-none focus:border-primary focus:bg-white/20 transition-all text-sm">
            <button type="submit" class="px-6 py-3 bg-primary text-white font-bold rounded-xl hover:bg-white hover:text-charcoal transition-colors text-sm whitespace-nowrap shadow-lg shadow-primary/20">
                Subscribe
            </button>
        </form>
        <p class="text-[10px] text-white/40 mt-3">No spam. Unsubscribe anytime.</p>
    </div>
</div>
