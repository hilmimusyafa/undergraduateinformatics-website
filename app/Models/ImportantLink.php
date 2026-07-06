<?php

namespace App\Models;

use App\Models\ImportantSection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImportantLink extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function important_section()
    {
        return $this->belongsTo(ImportantSection::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function($query, $search){
            return $query -> where('name', 'like', '%' . request('search') . '%')
                -> orWhereHas('important_section', function($q) {
                    $q->where('name', 'like', '%' . request('search') . '%');
                });
        });
    }
}
