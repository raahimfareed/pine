# Pine: A PHP Framework
## About Pine

Pine is a minimal PHP Web Framework

## Important
This is just a hobby project and is not complete in any way.

If you are looking for a framework that is production compatible, check out [Laravel](https://github.com/laravel/laravel/)

This is not meant to be used in a production environment.

On the other hand, if you stumble upon any issues (which I assume are plenty), please open an issue on github so I can take a look at it.

## Prerequisites
- PHP 8.2.10
- Composer 2.6.2
- Node 20.6.1 (Optional, only needed for vite/bun)
- Bun 1.0 (Optional, only needed for vite)

## Installation

First clone the repository
```sh
$ git clone https://github.com/raahimfareed/pine.git
```

Install composer packages
```sh
$ composer install
```

Optional: If you intend on using ViteJS, you can install all js packages

We use bun for this
```sh
$ bun install
```

Run PHP server
```sh
$ php -S localhost:8000
```

This will run a php server on localhost with port 8000

In a new terminal window, start vite

```sh
$ bun run dev
```

## Routing

You can create more routes in `src/routes.php`

For now, only GET and POST methods are supported, more methods will be added in future.

## Views and Leaf Templates

Views are essentially HTML files with added template functionalities.

You can create more views in `src/views` directory with the format `filename.leaf.html`

All view files should have an extension of `.leaf.html`

When rendering a view in routes, you will need to enter the view path relative to the view folder and without the extension

For example, with a project structure of the following
```sh
.
├── pine
├── src
│   ├── Controllers
│   │   └── SampleController.php
│   ├── database
│   │   └── 0001_create_users_table.php
│   ├── resources
│   │   ├── css
│   │   │   └── index.css
│   │   └── js
│   │       └── index.js
│   ├── routes.php
│   └── views
│       ├── controller.leaf.html
│       └── index.leaf.html
└── vite.config.js
```

We have a index.leaf.html

We can simple render this

```php
Route::get("/", function () {
    return new View("test");
});
```

### Showing data in pages

You can pass data to your templates as well using an optional 2nd argument to the View class.

Looking at the previous example, we would write it as

```php
Route::get("/", function () {
    return new View("test", ["name" => "Chuck Norris"]);
});
```

And then you can access it in your leaf file

```html
<h1>{{name}}</h1>
```

### CSS and JS
Pine serves `public/style.css` and `public/main.js` files for css and js respectively

You just need to add @css and @js directives in your leaf file if you need these files.

If you are using Vite, you will need to edit `src/resources/js/index.js` and `src/resources/css/index.css`
as vite will automatically bundle js and css for you, you will still need @css and @js to add it into your html

## Controllers
Barebone controller is implemented, doc needs to be updated.

## Migrations and Models
Migrations are used to create database schemas and models are used to manipulate the data in the tables.

Pine comes bundled with Eloquent which is a powerful ORM primarily used by Laravel.

### Creating Migrations
Migrations are just PHP files with some code in it to define the schema of a table.

By default, a users migration is provided.

```php
<?php

use Illuminate\Database\Capsule\Manager as Capsule;

return new class
{
  public function up(): void
  {
    Capsule::schema()->create('users', function ($table) {
      $table->id();
      $table->string('email')->unique();
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Capsule::schema()->drop('users');
  }
};
```

If you've ever used Laravel, this will be similar to you.

Two functions are required, `up` is for creating and `down` is for dropping.

For the most part, you just need to use `schema` function to create or drop tables as well as change the table schema.

For further help on how to use this, refer to [Laravel's Migrations](https://laravel.com/docs/10.x/migrations)

### Running Migrations
Pine comes with a pine file (shock). That file will come in handy for running migrations.

To run the migration you can run
```sh
$ php pine --migrate up
```

And to rollback the migrations, you can run
```sh
$ php pine --migrate down
```

You can also use the shorthand `-m` instead of `--migrate`

> [!IMPORTANT]  
> The migrations are run in the the order of their number. For example, 0001 will be run first, then 0002, 0003, so on and so forth until the last migration.


