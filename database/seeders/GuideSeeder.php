<?php

namespace Database\Seeders;

use App\Models\Guide;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Database\Seeder;

class GuideSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::where('email', 'admin@helloalibaug.com')->first()
            ?? User::query()->first();

        if (!$author) {
            $this->command->warn('GuideSeeder: no user found to author guides — skipping.');
            return;
        }

        $guides = [
            [
                'title' => 'The Best Beaches in Alibaug for 2026',
                'slug' => 'best-beaches-in-alibaug',
                'focus_keyword' => 'best beaches in alibaug',
                'is_featured' => true,
                'intro' => 'From the white sand of Kashid to the sheltered cove at Nagaon, here are the seven beaches every Alibaug visitor should know — ranked, with what each is actually best for.',
                'meta_description' => 'Seven best beaches in Alibaug for swimming, sunsets, watersports and quiet escapes — ranked with insider tips on each.',
                'content' => <<<'HTML'
<p>Alibaug isn't one beach — it's a stretch of coastline with very different personalities. Some beaches roar with weekend energy; others are still empty most mornings. Pick the right one and the trip transforms. Pick the wrong one and you've spent two hours trying to find parking.</p>
<p>Here's our honest, locally-informed ranking — what each beach is genuinely good at, what to avoid, and which of our handpicked stays put you within five minutes of the sand.</p>

<h2>1. Kashid Beach — The white-sand favorite</h2>
<p>Kashid is the picture-postcard beach. Wide, white, palm-fringed and far enough from Alibaug town that the crowd thins. Watersports operators line the southern end (jet ski, banana boat, parasailing). The northern stretch stays peaceful — bring a beach mat and a thermos.</p>
<p><strong>Best for:</strong> first-time visitors, watersports, group photos, sunset walks.</p>
<p><strong>Skip if:</strong> you want to swim in calm water (waves can be strong; monsoon swims are unsafe).</p>

<h2>2. Nagaon Beach — Watersports central</h2>
<p>If your group wants to do things, not just look at things, head to Nagaon. Almost every Alibaug watersport happens here, and the casuarina shade makes the long stretch easy to walk.</p>
<p><strong>Best for:</strong> watersports, families with teens, sunsets.</p>
<p><strong>Skip if:</strong> you're after a quiet beach read.</p>

<h2>3. Akshi Beach — The quiet one</h2>
<p>Just a few kilometres south of Alibaug town and yet somehow always missed by weekend tourists. Black sand, gentle slope, lined with casuarina. Locals come here at dawn for runs.</p>
<p><strong>Best for:</strong> early morning walks, photography, escaping crowds.</p>

<h2>4. Alibaug Beach — Convenience over beauty</h2>
<p>Right next to Kolaba Fort. Not the most beautiful beach in the area but the most useful — you can walk to it from the bus stand, get fresh seafood at the beachside stalls, and visit the fort at low tide.</p>
<p><strong>Best for:</strong> day trippers without transport, families with elderly members, fresh fish lunch.</p>

<h2>5. Kihim Beach — Birds, calm, beauty</h2>
<p>A long quiet beach lined with palms and casuarinas, famous among birdwatchers for the migratory species near the marshland behind. Calm waters most of the year.</p>
<p><strong>Best for:</strong> birdwatching, gentle swimming, photography, couples.</p>

<h2>6. Mandwa Beach — Just-arrived energy</h2>
<p>Many visitors land at Mandwa jetty and never walk the 10 minutes to its beach. It's clean, uncommercial, and offers a perfect first glimpse of the Konkan coastline.</p>

<h2>7. Varsoli Beach — Sunrise spot</h2>
<p>Just north of Alibaug town. Black sand, very gentle slope, and the best place in the area to watch the sun come up over the headland.</p>

<h2>What to know before you go</h2>
<p>The Arabian Sea here gets <strong>rough June through September</strong> — lifeguards mark beaches as unsafe and most watersports pause. October to May is the swim window. Always check our <a href="/weather">live Alibaug weather</a> and follow flag signals.</p>
<p>Parking is hardest at Kashid and Nagaon on weekends. If you're driving in from Mumbai, leave before 9 AM Saturday or you'll waste 90 minutes circling.</p>
HTML,
            ],
            [
                'title' => 'Family-Friendly Villas in Alibaug Under ₹20,000 a Night',
                'slug' => 'family-friendly-villas-alibaug-under-20000',
                'focus_keyword' => 'family villas in alibaug',
                'is_featured' => true,
                'intro' => 'You don\'t need a Bollywood budget. Here are the family villas in Alibaug that nail the basics — pool, parking, peace and a kitchen — without the ₹50,000-a-night sticker shock.',
                'meta_description' => 'Hand-picked family villas in Alibaug under ₹20,000 per night — with pool, parking, secure compounds, and Mumbai-friendly drive times.',
                'content' => <<<'HTML'
<p>Alibaug's villa market splits cleanly: the social-media-famous luxury picks at ₹40,000+, and a quieter tier of family-run properties priced for actual weekends with actual children. The second tier is where the best memories come from.</p>
<p>Here's what to look for, and our short list of the ones we'd send our own families to.</p>

<h2>What "family-friendly" actually means here</h2>
<ul>
    <li><strong>A pool that's fenced</strong> or set apart from the main lawn — non-negotiable for toddlers.</li>
    <li><strong>Secure parking inside the compound</strong> — a single weekend on the road is hard enough.</li>
    <li><strong>A working kitchen</strong> for breakfast and chai. Even families that "won't cook" appreciate a kettle.</li>
    <li><strong>At least one common area</strong> that can hold everyone awake at 11 PM.</li>
    <li><strong>Mosquito-screen windows</strong> — coastal evenings are merciless.</li>
</ul>

<h2>How to budget realistically</h2>
<p>Under ₹20,000 a night is achievable for 4–6 person groups if you book outside peak weekends (avoid mid-Dec to mid-Jan and long weekends). Add ₹2,000–₹4,000 for a caretaker-cooked meal — almost every villa offers this, and it's worth the spend.</p>

<h2>Areas to target</h2>
<p><strong>Awas</strong> and <strong>Kihim</strong> hit the family sweet spot — close enough to Alibaug town for groceries and emergencies, far enough that you actually hear the birds at dawn.</p>
<p>Avoid Mandwa-side villas for the first trip with kids — it's a 25-minute drive each way to the beaches you came for.</p>

<h2>Our handpicked stays</h2>
<p>The listings below all clear the family-friendly checklist above, sit under the ₹20,000 mark on most weekends, and have been visited by someone on our team in the last 12 months.</p>
HTML,
            ],
            [
                'title' => 'Monsoon in Alibaug: 12 Things Worth the Drive',
                'slug' => 'monsoon-in-alibaug',
                'focus_keyword' => 'alibaug in monsoon',
                'is_featured' => false,
                'intro' => 'Locals love monsoon Alibaug for a reason. Empty beaches, dramatic skies, hot kanda bhajis, and villa rates 30–50% below winter prices. Here\'s how to do it right.',
                'meta_description' => 'Monsoon in Alibaug — what to do, where to stay, what to skip. Ferry status, indoor picks, and 12 things worth the drive when it\'s pouring.',
                'content' => <<<'HTML'
<p>Most travel writers tell you to skip Alibaug in monsoon. We disagree. June through September is when the coast looks the most alive: deep green hills behind the beaches, dramatic skies over the sea, almost no crowds, and villa rates that drop 30–50% below winter peak.</p>
<p>You just have to know what works and what doesn't.</p>

<h2>The honest tradeoffs</h2>
<p><strong>What you give up:</strong> Sea swimming (lifeguards close beaches), watersports (mostly suspended), and a guaranteed ferry from Mumbai. The M2M ferry usually stops in monsoon; the road takes longer with traffic and patchy stretches.</p>
<p><strong>What you gain:</strong> The view from a villa balcony when a squall rolls in. Discounted rates. Empty roads after dark. Hot food that tastes better than it has any right to.</p>

<h2>How to get there in the rain</h2>
<p>Plan for the road. Check <a href="/how-to-reach">our route guide</a> for the NH66 stretch via Pen — it can flood briefly in heavy storms. Leave early. Carry waterproof footwear and a real umbrella, not a fashion one.</p>

<h2>12 things worth the drive</h2>
<ol>
    <li><strong>Slow morning chai on a villa balcony</strong> watching the rain hit the coconut palms.</li>
    <li><strong>Kanda bhaji at a roadside stall</strong> on the Alibaug–Murud road. Pull over for it.</li>
    <li><strong>Kolaba Fort at low tide</strong> on a dry hour — the moody backdrop is worth it.</li>
    <li><strong>The drive to Kashid</strong> when the road's empty.</li>
    <li><strong>A cooked-in-the-villa seafood meal</strong> — fresh catch is cheaper in monsoon.</li>
    <li><strong>Reading in a hammock</strong> — what every villa balcony is secretly designed for.</li>
    <li><strong>An afternoon at a heritage spice shop</strong> in Alibaug town.</li>
    <li><strong>Bicycle ride in a clear window</strong> between showers.</li>
    <li><strong>Hot Konkani thali at a homestyle restaurant</strong> when the storm hits.</li>
    <li><strong>Watching the ferry come in</strong> at Mandwa jetty between rainbands.</li>
    <li><strong>Birdwatching at Kihim</strong> — migratory species peak just after monsoon.</li>
    <li><strong>Sleep with the windows open</strong> — the sound is the whole point.</li>
</ol>

<h2>What to pack</h2>
<p>Two pairs of footwear (one drying, one dry). A proper raincoat. Power bank — power flickers in heavy storms. Cash for petrol pumps that lose card connectivity. A book.</p>
HTML,
            ],
        ];

        foreach ($guides as $g) {
            $wordCount = str_word_count(strip_tags($g['content']));
            Guide::updateOrCreate(
                ['slug' => $g['slug']],
                array_merge($g, [
                    'author_id' => $author->id,
                    'is_published' => true,
                    'published_at' => now()->subDays(rand(0, 14)),
                    'reading_time' => max(3, (int) ceil($wordCount / 200)),
                ])
            );
        }

        // Attach a few approved listings to each guide for the curated section.
        $stayIds = Listing::approved()->whereHas('category', fn ($q) => $q->where('slug', 'stay'))->pluck('id');
        $eatIds = Listing::approved()->whereHas('category', fn ($q) => $q->where('slug', 'eat'))->pluck('id');

        $beachGuide = Guide::where('slug', 'best-beaches-in-alibaug')->first();
        if ($beachGuide && $stayIds->isNotEmpty()) {
            $sync = [];
            foreach ($stayIds->take(3) as $i => $id) {
                $sync[$id] = ['position' => $i, 'blurb' => null];
            }
            $beachGuide->listings()->sync($sync);
        }

        $familyGuide = Guide::where('slug', 'family-friendly-villas-alibaug-under-20000')->first();
        if ($familyGuide && $stayIds->isNotEmpty()) {
            $sync = [];
            $blurbs = [
                'Set deep in Dhokawade — the pool is fenced and the cook is part of the package.',
                'Kihim location puts you a short drive from Akshi for sunrise walks.',
                'Three bedrooms, a working kitchen, and the kind of garden kids forget their tablets in.',
            ];
            foreach ($stayIds->take(3) as $i => $id) {
                $sync[$id] = ['position' => $i, 'blurb' => $blurbs[$i] ?? null];
            }
            $familyGuide->listings()->sync($sync);
        }

        $monsoonGuide = Guide::where('slug', 'monsoon-in-alibaug')->first();
        if ($monsoonGuide) {
            $sync = [];
            $combined = $stayIds->take(2)->concat($eatIds->take(2));
            foreach ($combined as $i => $id) {
                $sync[$id] = ['position' => $i, 'blurb' => null];
            }
            if (count($sync)) {
                $monsoonGuide->listings()->sync($sync);
            }
        }

        $this->command->info('Seeded ' . count($guides) . ' editorial guides.');
    }
}
