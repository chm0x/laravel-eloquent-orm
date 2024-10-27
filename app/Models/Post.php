<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Prunable;

class Post extends Model
{
    use HasFactory, SoftDeletes, Prunable;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'excerpt',
        'description',
        'min_to_read',
        'is_published'
    ];

    
    public function prunable() 
    {
        # Every time a soft deleted row has been deleted almost a month ago.
        return static::where('deleted_at', '<=', now()->subMonth() );
    }

}
