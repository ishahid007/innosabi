<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SuggestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->resource['data'] ?? [],
            'meta' => [
                'language' => $this->resource['meta']['language'] ?? 'en',
                'total_pages' => $this->resource['totalPages'] ?? 0,
                'current_page' => $this->resource['page'] ?? 1,
                'page_size' => $this->resource['pageSize'] ?? 30,
                'items_found' => $this->resource['meta']['numberOfItemsFound'] ?? 0,
            ],
        ];
    }
}
