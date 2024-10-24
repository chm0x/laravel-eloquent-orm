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

## CHANGING TABLE NAME

By default, Eloquent assumes that the table name of the model is the Plural form of the model class name with an underscore separating each word. 

Example, we have `Post` model will work with a table named `posts` and the `User` model will work with a table named `users` .

However, eloquent allows you to change the table name that a model represents by defining a table property within the model class.

`app/Models/Post.php`
```
class Post extends Model
{
    ...
    protected $table = 'table_name';
    ...
}
```