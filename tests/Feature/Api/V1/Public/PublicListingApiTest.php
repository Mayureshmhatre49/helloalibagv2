<?php

namespace Tests\Feature\Api\V1\Public;

use App\Models\Area;
use App\Models\Category;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicListingApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $creator;
    protected Category $category;
    protected Category $realEstate;
    protected Area $area;

    protected function setUp(): void
    {
        parent::setUp();

        $this->creator = User::factory()->create();
        $this->category = Category::create(['name' => 'Stays', 'is_active' => true]);
        $this->realEstate = Category::create(['name' => 'Real Estate', 'slug' => 'real-estate', 'is_active' => true]);
        $this->area = Area::create(['name' => 'Alibag', 'is_active' => true]);
    }

    protected function makeListing(array $overrides = []): Listing
    {
        return Listing::create(array_merge([
            'title' => 'Test Listing '.uniqid(),
            'category_id' => $this->category->id,
            'area_id' => $this->area->id,
            'description' => 'A lovely place to stay.',
            'status' => 'approved',
            'created_by' => $this->creator->id,
        ], $overrides));
    }

    protected function tokenWithAbility(string $ability = 'read:listings-public'): string
    {
        $integration = User::factory()->create();

        return $integration->createToken('test-token', [$ability])->plainTextToken;
    }

    public function test_no_token_returns_401(): void
    {
        $this->makeListing();

        $response = $this->getJson('/api/v1/public/listings');

        $response->assertStatus(401);
    }

    /**
     * getJson() sets its own Accept header, which would mask a real bug:
     * without ForceJsonResponse running before auth, a client that forgets
     * Accept: application/json gets redirected to /login (302) instead of
     * a clean 401 JSON.
     */
    public function test_no_token_returns_401_even_without_accept_header(): void
    {
        $this->makeListing();

        $response = $this->get('/api/v1/public/listings');

        $response->assertStatus(401);
    }

    public function test_token_without_correct_ability_returns_403(): void
    {
        $token = $this->tokenWithAbility('read:something-else');
        $this->makeListing();

        $response = $this->withToken($token)->getJson('/api/v1/public/listings');

        $response->assertStatus(403);
    }

    public function test_pending_listing_is_hidden_from_index_and_404s_on_show(): void
    {
        $token = $this->tokenWithAbility();
        $listing = $this->makeListing(['status' => 'pending']);

        $index = $this->withToken($token)->getJson('/api/v1/public/listings');
        $index->assertOk()->assertJsonMissing(['slug' => $listing->slug]);

        $show = $this->withToken($token)->getJson("/api/v1/public/listings/{$listing->slug}");
        $show->assertStatus(404);
    }

    public function test_rejected_listing_is_hidden_from_index_and_404s_on_show(): void
    {
        $token = $this->tokenWithAbility();
        $listing = $this->makeListing(['status' => 'rejected']);

        $index = $this->withToken($token)->getJson('/api/v1/public/listings');
        $index->assertOk()->assertJsonMissing(['slug' => $listing->slug]);

        $show = $this->withToken($token)->getJson("/api/v1/public/listings/{$listing->slug}");
        $show->assertStatus(404);
    }

    public function test_real_estate_listing_without_payment_is_excluded(): void
    {
        $token = $this->tokenWithAbility();
        $listing = $this->makeListing([
            'category_id' => $this->realEstate->id,
            'status' => 'approved',
            'payment_received_at' => null,
        ]);

        $index = $this->withToken($token)->getJson('/api/v1/public/listings');
        $index->assertOk()->assertJsonMissing(['slug' => $listing->slug]);

        $show = $this->withToken($token)->getJson("/api/v1/public/listings/{$listing->slug}");
        $show->assertStatus(404);
    }

    public function test_real_estate_listing_with_payment_is_visible(): void
    {
        $token = $this->tokenWithAbility();
        $listing = $this->makeListing([
            'category_id' => $this->realEstate->id,
            'status' => 'approved',
            'payment_received_at' => now(),
        ]);

        $show = $this->withToken($token)->getJson("/api/v1/public/listings/{$listing->slug}");
        $show->assertOk()->assertJsonPath('data.slug', $listing->slug);
    }

    public function test_updated_since_filter_only_returns_newer_listings(): void
    {
        $token = $this->tokenWithAbility();

        $old = $this->makeListing();
        $old->timestamps = false;
        $old->updated_at = now()->subDays(5);
        $old->save();

        $recent = $this->makeListing();

        $response = $this->withToken($token)->getJson(
            '/api/v1/public/listings?updated_since='.urlencode(now()->subDay()->toIso8601String())
        );

        $response->assertOk()
            ->assertJsonMissing(['slug' => $old->slug])
            ->assertJsonPath('data.0.slug', $recent->slug);
    }

    public function test_resource_excludes_internal_fields(): void
    {
        $token = $this->tokenWithAbility();
        $listing = $this->makeListing([
            'rejection_reason' => 'should never appear',
            'views_count' => 42,
            'approved_by' => $this->creator->id,
        ]);

        $response = $this->withToken($token)->getJson("/api/v1/public/listings/{$listing->slug}");

        $response->assertOk();
        $json = $response->json('data');

        foreach ([
            'created_by', 'approved_by', 'verified_by', 'rejection_reason',
            'verification_note', 'payment_received_at', 'payment_recorded_by',
            'payment_note', 'rejected_by', 'rejected_at', 'views_count', 'subscription_ready',
        ] as $forbidden) {
            $this->assertArrayNotHasKey($forbidden, $json);
        }
    }

    public function test_rate_limit_triggers_after_threshold(): void
    {
        $token = $this->tokenWithAbility();
        $this->makeListing();

        for ($i = 0; $i < 60; $i++) {
            $this->withToken($token)->getJson('/api/v1/public/listings')->assertOk();
        }

        $this->withToken($token)->getJson('/api/v1/public/listings')->assertStatus(429);
    }
}
