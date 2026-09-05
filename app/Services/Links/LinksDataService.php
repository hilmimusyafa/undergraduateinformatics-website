<?php

namespace App\Services\Links;

use App\Http\Resources\ImportantSectionResource;
use App\Models\ImportantSection;

class LinksDataService
{
    public function getSectionsWithLinks(): array
    {
        $sections = ImportantSection::query()
            ->with(['important_links' => function ($query) {
                $query->orderByDesc('updated_at')->orderByDesc('id');
            }])
            ->orderBy('order_number')
            ->get();

        return [
            'status' => 'success',
            'data' => ImportantSectionResource::collection($sections)->resolve(),
        ];
    }
}
