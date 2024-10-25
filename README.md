# ELOQUENT ORM

ORM stands for Object Realtional Mapper, which is a programming technique that allows developers to work with databases using object oriented syntax. 

Eloquent is a powerful and flexible tool that allows developers to work with databases using object oriented syntax.

It provideds a lot of built in features that makes it easy to work with databases such as automatic timestamps, soft deleting and eager loading. 

Advantage: Powerful Query Builder that allows you to build up complex queries using a fluent chainable syntax. Useful for advanced queries.

Disadvantages: Slower than using raw SQL when working with large datasets. Difficult to debug. Difficult to use than raw SQL. 

Use `tinkerwell`. Allows you to interact with your app and database using interface. 



## WHEN SHOULD WE USE ELOQUENT?

Eloquent is a good choice when you need to work with databases using object oriented syntax. 

Useful when working with smaller datasets or where you need to work with complex relationships between tables.

***If you work with large datasets, use Query Builder.***

Query BUilder provides more control over the SQL queries. 


## MODELS

A model represents a database table.

The purpose of a model is to abstract away the database operations and provide a clean interface for the app to interact with data. 

Eloquent offers a set of model conventions, including naming conventions for tables and columns, using accessors and mutators to modify data defining relationships between table and more.

```
# m: migration, f:factory, s: seeder, --resource:create PostController with resources
> php artisan make:model Post --resource -mfs
```

## CHANGING NAMING CONVENTION FROM ELOQUENT

By default, Eloquent assumes that the table name of the model is the Plural form of the model class name with an underscore separating each word. 

Example, we have `Post` model will work with a table named `posts` and the `User` model will work with a table named `users` .

However, eloquent allows you to change the table name that a model represents by defining a table property within the model class.


### TABLE NAME CONVENTION
`app/Models/Post.php`
```
class Post extends Model
{
    ...
    protected $table = 'table_name';
    ...
}
```

### PRIMARY KEY CONVENTION

By default, eloquent assumes the primary key for a model is an autoincrement integer named ID.

You can change:
`app/Models/Post.php`
```
class Post extends Model
{
    ...
    # protected $primaryKey = 'column_name';
    # Example
    protected $primaryKey = 'slug';
    ...
}
```
It can use with `Post::find(a_slug)` method, that match the primary key.

### AUTO INCREMENT FOR THE PRIMARY KEY

It can disable the primary key's increment behavior. This will Eloquent not to automatically increment the primary key a new record is inserted into the DB.

**Advice: Do not touch.**
`app/Models/Post.php`
```
class Post extends Model
{
    ...
    public $incrementing = false;
    ...
}
```

### CHANGING THE DATA TYPE OF THE PRIMARY KEY

`app/Models/Post.php`
```
class Post extends Model
{
    ...
    protected $keyType = 'string';
    ...
}
```

When you changes, it need to update any relationship.

### disable created_at and updated_at columns

`app/Models/Post.php`
```
class Post extends Model
{
    ...
    public $timestamp = false;
    ...
}
```

You can customize the format of the timestamp

Default: Y-m-d H:i:s
`app/Models/Post.php`
```
class Post extends Model
{
    ...
    protected $dateFormat = 'U'
    ...
}
```

Customize the names of the timestamps columns
`app/Models/Post.php`
```
class Post extends Model
{
    ...
    const CREATED_AT = 'date_created_at';
    const UPDATED_AT = 'date_updated_at';
    ...
}
```

### Set Default attributes values for table
`app/Models/Post.php`
```
class Post extends Model
{
    ...
    protected $attributes = [
        "user_id" => 1,
        "is_published" => true,
        "description" => "Please add your description"
    ];
    ...
}
```

### change the DB interacting with a particular model
Useful when working with multiples databases.

`app/Models/Post.php`
```
class Post extends Model
{
    ...
    protected $connection = 'sqlite';
    ...
}
```

## FILLIABLE & GUARDED PROPERTIES

It is important to protect against mass assignment vulnerabilities.

Mass assignment is a technique used to set multiple attributes of a model using an array of data. This can be a security risk if not handled correctly. Examples: password, permissions. 

*(dont use them together)*

Recommendation: always be careful and precise when working with both attributes. 

Only use **fillable**. 

### the $fillable property

List of fields that are allowed for mass assignment. 

Any field or attribute that are not listed in the $fillable property array will not be allowed to be mass assigned, so the eloquent cannot create them.

`app/Models/Post.php`
```
class Post extends Model
{
    ...
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'excerpt',
        'description',
        'min_to_read',
        'is_published'
    ];
    ...
}
```

### the $guarded property

The $guarded property is an array that list the fields that are not allowed to be mass assigned.

`app/Models/Post.php`
```
class Post extends Model
{
    ...
    protected $guarded = [
        'is_published'
    ];
    ...
}
```

## Building Queries

Advantages: improved performance, flexibility, readability.

use `php artisan tinker` or if you buy `tinkerwell`.
```
> Post::all();
```

### where()

```
> Post::where('is_published', true)->get();

# with cursorPaginate()
> Post::where('is_published', true)->cursorPaginate(2);

# complex queries
> Post::where('is_published', true)
    ->where('min_to_read', '>', 5)
    ->orderBy('title', 'desc')
    ->get()
    ->count();

> Post::where('is_published', true)
        ->where('min_to_read', '>', 5)
        ->orderBy('title', 'desc')
        ->get();


> Post::where('is_published', true)
        ->where('min_to_read', '>', 5)
        ->toSql();

```


## Retrieving single models/record

### find()

This is a method used to retrieve a specifi row from the database based on the primary key.

`app/Models/Post.php`
```
Post::find(1)
```

### first()

The first() method is needed because we want to find one row based on a condition. 

`app/Models/Post.php`
```
# If you find similar records, use where() and then first() methods
Post::where('slug', 'requiem')->first();
```

### firstWhere()

This method is used to retrieve a specific post by a custom attribute.
`app/Models/Post.php`
```
Post::firstWhere('slug', 'requiem')
```

### findOrFail()

This method finds by Primary key, if not exist send a null value. 
`app/Models/Post.php`
```
Post::findOrFail(1)
```

### firstOrFail()

This method is used to retrieve a specific row from the DB based on a custom attribute.
`app/Models/Post.php`
```
Post::where('slug','requiem')
        ->firstOrFail();
```

If not exists, return an Exception.


## INSERTING/CREATING MODELS


### Model Instance

A **model instance** refers to an instance of a model class, which is a representation of a DB table.
**Option 1**:
`App\Http\Controllers\PostController.php`
```
...
use App\Models\Post;

$post = new Post;

# Insert values to the column
$post->user_id      = 15;
$post->title        = "Test title";
$post->slug         = "test-title";
$post->excerpt      = "Test excerpt";
$post->description  = "Test description";
$post->is_published = false;
$post->min_to_read  = 4;

# Saving/Inserting data to DB.
# Returns boolean type.
$post->save();
...
```

### fill()
**Option 2**
`App\Http\Controllers\PostController`
```
use App\Models\Post

$post = new Post;

$post->fill([
    "user_id"      => 4,
    "title"        => "Test title 2",
    "slug"         => "test-title-2",
    "excerpt"      => "Test excerpt 2",
    "description"  => "Test description 2",
    "is_published" => false,
    "min_to_read"  => 1
]);

$post->save()
```

### create()
**Option 3***

**Dont need to use save() anymore.**
```
Post::create([
    "user_id"      => 4,
    "title"        => "Eloquent is awesome",
    "slug"         => "eloquent-is-awesome",
    "excerpt"      => "Test excerpt: Eloquen is awesome 1",
    "description"  => "Test desc: Eloquen is awesome 1",
    "is_published" => true,
    "min_to_read"  => 5
]);
```

```
$post = Post::create([
    "user_id"      => 4,
    "title"        => "Eloquent is awesome",
    "slug"         => "eloquent-is-awesome",
    "excerpt"      => "Test excerpt: Eloquen is awesome 1",
    "description"  => "Test desc: Eloquen is awesome 1",
    "is_published" => true,
    "min_to_read"  => 5
]);

dd($post->title);
```