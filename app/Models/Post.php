<?php

namespace App\Models;

use App\Models\PostTag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function($query, $search){
            return $query -> where('title', 'like', '%' . request('search') . '%')
                        -> orWhere('body', 'like', '%' . request('search') . '%');
        });
    }

    public function post_tags()
    {
        return $this->hasMany(PostTag::class);
    }
    /**
     * The roles that belong to the Post
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tags', 'post_id', 'tag_id');
    }

    /**
     * Check if post has been edited or not
     */
    public function hasBeenUpdated()
    {
        return $this->created_at != $this->updated_at;
    }

    /**
     * Check if post has attached image
     */
    public function hasImage()
    {
        return $this->image != "images/placeholder.png";
    }
}
