# Pine: A PHP Framework
## About Pine

Pine is a minimal PHP Web Framework

## Prerequisites
- PHP 8.2.10
- Composer 2.6.2

## Installation

First clone the repository
```sh
$ git clone https://github.com/raahimfareed/pine.git
```

Install composer packages
```sh
$ composer install
```

Run PHP server
```sh
$ php -S localhost:8000
```

This will run a php server on localhost with port 8000

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
./src/
├── App
│   ├── LeafTemplateEngine.php
│   ├── Request.php
│   ├── Route.php
│   └── View.php
├── config
│   └── bootstrap.php
├── routes.php
└── views
    └── test.leaf.html
```

We have a test.leaf.html

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

