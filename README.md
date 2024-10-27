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

## RETRIEVING ALL MODELS/DATA

### all()

disadvantage: It retrieves all records from a table at once, which can cause performance issues. **Not recommended when you are handling large datasets**.

It used to retrieving all records from a table.

`app/Http/Controllers/PostController.php`
```
$posts = Post::all();


Post::all()->count();
```

### paginate()

**Recommended for large datasets**. 

Default: 15 rows. 

```
Post::paginate();

Post::paginate(5);

Post::paginate()->count();
```

### simplePaginate()
```
Post::simplePaginate()

Post::simplePaginate(5)
```

### cursorPaginate()

**More efficient when dealing with large datasets**.

```
Post::cursorPaginate();

Post::cursorPaginate(5);
```

**Recommendations**:

* Small data -> all()
* Large data -> paginate() or cursorPaginate()


## firstOrCreate() & firstOrNew()

Create a new record in a DB only if it doesn't already exist or retrieve an existing record if it does. 

Both methods allows us to avoid duplicating data in the DB and can pontentially speed up queries. 


### firstOrCreate()

```
# first parameter: Tries to find a match based on a key value pairs
# second parameter: If no matching record is found based on the 
# first key value-pair.
Post::firstOrCreate([], []);

$array1 = [
    "title"        => "Eloquent is awesome",
];

$array2 = [
    "user_id"      => 4,
    "title"        => "First or create",
    "slug"         => "first-or-create",
    "excerpt"      => "Test excerpt: First or create",
    "description"  => "Test desc: First or create",
    "is_published" => false,
    "min_to_read"  => 2
]

Post::firstOrCreate( $array1, $array2 );
```

**Situational**
```
$post = Post::where('title', 'my title')->first() ?: Post::create($array2);
```
Better using `firstOrCreate()` that the code above.


### firstOrNew()

This method used for retrieve a record if it exists or creates a new one if it doesn't.

Similar to `firstOrCreate()`, but instead of creating a new record, if one is not found, it simply returns a new instance of the model with a given attribute sets. 

```
$array1 = [
    "title"        => "Eloquent is awesome",
];

$array2 = [
    "user_id"      => 4,
    "title"        => "First or create",
    "slug"         => "first-or-create",
    "excerpt"      => "Test excerpt: First or create",
    "description"  => "Test desc: First or create",
    "is_published" => false,
    "min_to_read"  => 2
]

Post::firstOrNew( $array1, $array2 );
```

Both are useful methods for avoiding duplicate data in the DB and speeding up queries. 

They require more code than the create() method and slower for complex queries.


## UPDATE

**option 1**
```
$post = Post::find(1);

$post->title        = "Test update";
$post->slug         = "test-update";
$post->description  = "Test description update";

$post->save()
```

**option 2**
```
Post::where('id', 1)->update([
    "title"        => "update OPtion 2",
    "slug"         => "update-option-2",
    "description"  => "Test desc: option 2 update"
]);

Post::where('is_published', false)->update([
    "is_published" => true
]);
```

## Attribute Changes [isDiry, isClean & wasChanged]

Eloquent offers a total of 3 methods that can be used to check for changes attributes. These methods are very useful when working with models and need to **track changes to attributes**.


### isDirty()

Mainly used to determine whether an attribute in a model has been changed.

**Use it before `save()` methods.**

```
$post = Post::find(1);
$post->title = "Batman, El caballero de la noche"

# returns boolean type. 
$post->isDirty()

#returns true
$post->isDirty('title')

#returns false
$post->isDirty('excerpt')

#returns boolean if one or another is changed.
$post->isDirty(['title', 'excerpt'])

if($post->isDirty('title')){
    echo "Title has been changed";
}else{
    echo "Title has not been changed";
}

# BEHIND THE SCENE
# getOriginal('value') retrieves the original value before updates.
$post->getOriginal('title') !== $post->title
```


### isClean()

Mainly used to determine whether there any changes to the model attributes that have not been saved to the DB.

Indica que está limpio sin modificar los atributos de la tabla. 
```
$post = Post::find(1);

# returns true
$post->isClean()
```

```
$post = Post::find(2);

$post->title = "Joker";

# returns false
$post->isClean();
$post->isClean('title');
$post->isClean(['title', 'excerpt']);
```

Useful for real life: Editing a form with multiples fields, if user has made changes to one or more fields but has not submitted the form, the `isClean()` can be used to determine whether they are unsafe changes in the form.

### wasChanged()

This method is used to determine whether a specific attribute has been modified since the model was last saved.

It returns a boolean type.
```
$post = Post::find(3)

# returns false
$post->wasChanged();
$post->wasChanged('title');
$post->wasChanged(['title', 'excerpt']);
```

```
$post = Post::find(3)

$post->title = 'some'

$post->save()

# returns true
$post->wasChanged();

if($post->wasChanged('title')){
    echo "The title attribute was changed";
}
```

Example in real life: An Ecommerce app that allows users to edit their shipping information, if a user updates their shipping address but have not yet submitted a form, the `wasChanged()` method can be used to determine whether the adverse fields have been modified.

## UPDATE RECORDS

### updateOrCreate()

Case use: It when working with forms or APIs when you may need to update an existing records or create a new one if it doesn't exist.

This method allows you to update an existing record or create a new one if it does not exist.
```
# takes 2 arguments
# arg1: an array to find the record by an array of  attributes to update
# or create a record with. 
# arg2: update data
$post = Post::updateOrCreate([
    'id' => 1
], [
    "user_id"      => 4,
    "title"        => "Update or Create 1 ",
    "slug"         => "update-or-create-1",
    "excerpt"      => "Test excerpt: update or create",
    "description"  => "Test desc: Update or create",
    "is_published" => false,
    "min_to_read"  => 2
])
```

### upsert()

The difference is that you don't need to se the `'id' => 1`. 

```
# Has 2 arguments
# arg1: An array of values that you want to insert OR update.
# arg2: An array of unique keys to find the record by. 
Post::upsert([],[]);

Post::upsert([
    'id' => 10,
    'user_id' => 18,
    'title' => 'Boulevard of the broken dreams',
    'slug' => 'boulevard-of-the-broken-dreams',
    'excerpt' => 'exc boulevard',
    'description' => 'upserting',
    'is_published' => true,

],[ 'id' ]);
```

## DELETING ROW(S)

### delete()

This method is a simple way to delete a single model instance. 

```
$post = Post::find(1);

$post->delete();
```

### truncate() 


**warning**:This method is used to delete all records from a table. And, also, reset the auto_increment ID to ZERO.
```
Post::truncate();
```

### destroy()

This method is used to delete **multiple records** from a table. And, also, you can delete 1 record.

```
# Multiple records
Post::destroy([1,2,3]);

# single records
Post::destroy(1);
```

## SOFT DELETES 

When you deletes records from your DB, it's permanently deleted. It's means that if you don't have a backup, it's gone forever. 

**Soft deleting** provides a way to mark  a record as  deleted without actually removing it from DB. 

Column: `delete_at`

**YOU NEED TO ENABLE SOFT DELETING IN THE ELOQUENT MODEL** (using traits)

On `App\Models\Post`
```
class Post
{
    use HasFactory, SoftDeletes;
}
```

Then
```
$post = Post::find(5);

# The record was "deleted" but not in DB. 
$post->delete()

# returns null. But in DB exist.
Post::fiind(5);
```

Behind scenes.
```
# Excludes deleted_at
select * from posts where deleted_at is null order by created_at desc;
```

### retrieving soft deletes

#### withTrashed()

This method will retrieves all records, including ones that have been soft deleted. 
```
Post::withTrashed()
    ->orderBy('id', 'desc')
    ->get();
```

#### restore()
Recuperar el ultimo registro
```
Post::withTrashed()
    ->where('id', 5)
    ->restore();
```

### PERMANENTLY REMOVED

```
# first
$post = Post::find(1);
$post->delete()

# then
$post_trash = Post::withTrashed()
    ->find(1);

$post->forceDelete();
```

## PRUNING MODEL

La base de datos puede volverse desordenada si no elimina los registros del `soft deleted` temporalmente después de un cierto período de tiempo. Laravel ofrece una "limpieza"(prune) a los modelos del base de datos. Lo cual es una excelente manera de eliminar **datos obsoletos** de la base de datos.

You need to **enable the prune table trait**. With `Prunable`.

On `App\Models\Post`
```
use Illuminate\Database\Eloquent\Prunable;

class Post extends Model
{
    use HasFactory, SoftDeletes, Prunable;
    ...
    ...

    public function prunable() 
    {
        # Every time a soft deleted row has been deleted almost a month ago.
        return static::where('deleted_at', '<=', now()->subMonth() );
    }
}
```


## REPLICATING MODELS


### replicate()

```
$post = Post::create([
    'user_id' => 1,
    'title' => 'Replicating Models',
    'slug' => 'replicate',
    'excerpt' => 'replicate',
    'description' => 'desc replicate',
    'is_published' => false,
]);

$post->replicate();

# Use fill() when you have issues about unique fields.
$post->replicate()->fill([
    'title' => 'REPLICATED',
    'slug' => 'replicated-slug'
]);
```