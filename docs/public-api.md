# Public Listings API (v1)

Read-only feed of **approved** listings for trusted integration partners (currently:
Alibag Tourism, pulling and caching on a schedule). Real-estate listings are only
included once offline payment has been recorded.

## Auth

Every request needs a Sanctum personal access token scoped to the
`read:listings-public` ability, sent as a standard bearer token:

```
Authorization: Bearer <token>
```

A token with any other (or no) ability gets `403`. No token gets `401`. Requests
must be HTTPS in production (`403` otherwise).

### Generating a token

```bash
php artisan integrations:create-token alibag-tourism
```

Prints the plaintext token once — store it immediately (e.g. in the consumer
app's `.env` as `ALIBAUG_API_TOKEN`). Re-running the command rotates the token
for that name.

## Rate limiting

60 requests/minute, keyed per token (not per IP).

## Endpoints

### `GET /api/v1/public/listings`

Paginated, page-based (`?page=`, default 15/page, `?per_page=` capped at 50).

| Filter | Type | Notes |
|---|---|---|
| `category` | string | category slug |
| `area` | string | area slug |
| `updated_since` | ISO 8601 | only listings updated at/after this timestamp — use for incremental sync. URL-encode it (a `+` timezone offset decodes as a space otherwise) — Laravel's `Http::get($url, $query)` does this for you automatically. |
| `is_featured` | bool | `true`/`false` |
| `is_premium` | bool | `true`/`false` |

```bash
curl -s \
  -H "Authorization: Bearer <token>" \
  "https://helloalibag.com/api/v1/public/listings?updated_since=2026-10-01T00:00:00Z&per_page=50"
```

### `GET /api/v1/public/listings/{slug}`

Same approval/payment gating as the index. Returns `404` for a slug that doesn't
exist, is pending/rejected, or is an unpaid real-estate listing — identical
response in all three cases, so existence of non-public listings is never leaked.

```bash
curl -s \
  -H "Authorization: Bearer <token>" \
  "https://helloalibag.com/api/v1/public/listings/some-listing-slug"
```

## Response shape

```json
{
  "data": {
    "title": "...",
    "slug": "...",
    "description": "...",
    "price": 1200.00,
    "category": { "id": 1, "name": "Stays", "slug": "stays" },
    "area": { "id": 1, "name": "Alibag", "slug": "alibag" },
    "images": [
      { "url": "https://.../image.jpg", "is_primary": true, "alt_text": null }
    ],
    "amenities": ["Wi-Fi", "Parking"],
    "tags": ["Family Friendly"],
    "latitude": 18.6414,
    "longitude": 72.8722,
    "phone": "...",
    "whatsapp": "...",
    "email": "...",
    "website": "...",
    "google_business_url": "...",
    "is_featured": false,
    "is_premium": false,
    "is_verified": true,
    "approved_at": "2026-09-01T10:00:00+00:00",
    "updated_at": "2026-09-15T12:00:00+00:00",
    "seo": { "meta_title": "...", "meta_description": "...", "og_image": "..." }
  }
}
```

Internal fields (`created_by`, `approved_by`, `verified_by`, `rejection_reason`,
`verification_note`, `payment_received_at`, `payment_recorded_by`, `payment_note`,
`rejected_by`, `rejected_at`, `views_count`, `subscription_ready`) are never
present in this response.

## Audit log

Every call is logged (token id, IP, endpoint, timestamp) to
`storage/logs/public-api-*.log` (30-day retention) for monitoring a compromised
or misbehaving token.
