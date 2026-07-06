<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function post_tags()
    {
        return $this->hasMany(PostTag::class);
    }

    /**
     * The posts that belong to the Tag
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_tags', 'tag_id', 'post_id');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function($query, $search){
            return $query -> where('name', 'like', '%' . request('search') . '%')
                        -> orWhere('description', 'like', '%' . request('search') . '%');
        });
    }
}
