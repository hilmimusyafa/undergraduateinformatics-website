<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackLink extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function scopeConfigured(Builder $query): Builder
    {
        return $query->whereNotNull('link')->where('link', '!=', '');
    }
}
