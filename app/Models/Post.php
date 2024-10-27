<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use App\Models\Scopes\PublishedWithinThirtyDaysScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Prunable;

class Post extends Model
{
    use HasFactory, SoftDeletes, Prunable, PostScopes;

    # USING GLOBAL SCOPE
    // protected static function booted(): void
    // {
    //     static::addGlobalScope(new PublishedWithinThirtyDaysScope() );
    // }
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'excerpt',
        'description',
        'min_to_read',
        'is_published'
    ];

    # THIS IS NOT A LOCAL SCOPE
    public function prunable() 
    {
        # Every time a soft deleted row has been deleted almost a month ago.
        return static::where('deleted_at', '<=', now()->subMonth() );
    }


    // # LOCAL SCOPES
    // public function scopePublished(Builder $builder): Builder | QueryBuilder
    // {
    //     return $builder->where('is_published', true);
    // }

    // public function scopeWithUserData(Builder $builder): Builder | QueryBuilder
    // {
    //     return $builder->join('users', 'posts.user_id', '=', 'users.id')
    //                 ->select('posts.*', 'users.name', 'users.email');
    // }

    // # DYNAMIC SCOPES
    // public function scopePublishedByUser(Builder $builder, int $userID): Builder | QueryBuilder
    // {
    //     return $builder->where('user_id', $userID)
    //                     ->whereNotNUll('created_at');
    // }
}
