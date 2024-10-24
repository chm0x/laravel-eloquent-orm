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

## eloquent commands

use `php artisan tinker` or if you buy `tinkerwell`.
```
> Post::all();
```