<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use App\Models\SeoMeta;

class SeoService
{
    public function getForModel(Model $model): array
    {
        $meta = $model->seoMeta ?? null;

        $defaults = $this->generateDefaults($model);

        return [
            'title' => $meta->meta_title ?? $defaults['title'],
            'description' => $meta->meta_description ?? $defaults['description'],
            'keywords' => $meta->meta_keywords ?? $defaults['keywords'] ?? '',
            'og_image' => $meta->og_image ?? $defaults['og_image'] ?? '',
            'canonical' => $meta->canonical_url ?? request()->url(),
        ];
    }

    public function setForModel(Model $model, array $data): SeoMeta
    {
        return $model->seoMeta()->updateOrCreate([], [
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'meta_keywords' => $data['meta_keywords'] ?? null,
            'og_image' => $data['og_image'] ?? null,
            'canonical_url' => $data['canonical_url'] ?? null,
        ]);
    }

    protected function generateDefaults(Model $model): array
    {
        $title = config('app.name') . ' — ';
        $description = '';
        $ogImage = '';

        if (method_exists($model, 'getTable')) {
            switch ($model->getTable()) {
                case 'listings':
                    $title .= $model->title;
                    $description = \Illuminate\Support\Str::limit(strip_tags($model->description), 160);
                    $ogImage = $model->getPrimaryImageUrl() ?? '';
                    break;
                case 'categories':
                    $title .= $model->name . ' in Alibaug';
                    $description = $model->description ?? "Explore {$model->name} in Alibaug";
                    break;
                case 'areas':
                    $title .= $model->name . ' — Alibaug';
                    $description = $model->description ?? "Discover {$model->name}, Alibaug";
                    break;
                default:
                    $title .= $model->title ?? $model->name ?? 'Page';
                    break;
            }
        }

        return [
            'title' => $title,
            'description' => $description,
            'keywords' => '',
            'og_image' => $ogImage,
        ];
    }
}
