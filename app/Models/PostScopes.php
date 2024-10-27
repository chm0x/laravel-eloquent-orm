<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
trait PostScopes
{
    # LOCAL SCOPES
    public function scopePublished(Builder $builder): Builder | QueryBuilder
    {
        return $builder->where('is_published', true);
    }

    public function scopeWithUserData(Builder $builder): Builder | QueryBuilder
    {
        return $builder->join('users', 'posts.user_id', '=', 'users.id')
                    ->select('posts.*', 'users.name', 'users.email');
    }

    # DYNAMIC SCOPES
    public function scopePublishedByUser(Builder $builder, int $userID): Builder | QueryBuilder
    {
        return $builder->where('user_id', $userID)
                        ->whereNotNUll('created_at');
    }
}