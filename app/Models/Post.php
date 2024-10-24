<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    # Definir la tabla
    // protected $table = 'users';

    # Chagin primary key
    // protected $primaryKey = 'slug';

    # Disable or enable incremeting
    // public $incrementing = false;

    # Change type
    // protected $keyType = 'string';

    # Disable timestamps
    // public $timestamp = false;

    # Customize the date format
    // protected $dateFormat = 'U'

    # Customize the names of the timestamps
    // const CREATED_AT = 'date_created_at';
    // const UPDATED_AT = 'date_updated_at';

    # Set the default values for the table
    // protected $attributes = [
    //     "user_id" => 1,
    //     "is_published" => true,
    //     "description" => "Please add your description"
    // ];

    # CHANGE THE DB INTERACTING IN THIS MODEL
    // protected $connection = 'sqlite';


}
