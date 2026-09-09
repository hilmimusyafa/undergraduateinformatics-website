<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardDatasetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $items = $this->items->sortBy('sort_order');

        return [
            'id' => $this->id,
            'title' => $this->title,
            'chart_type' => $this->chart_type,
            'x_label' => $this->x_label,
            'y_label' => $this->y_label,
            'labels' => $items->pluck('label')->values()->all(),
            'values' => $items->pluck('value')->values()->all(),
        ];
    }
}
