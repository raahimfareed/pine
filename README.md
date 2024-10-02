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
- Node 20.6.1
- pnpm 9.1.1

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

We use pnpm for this but, you can use any package manager for node
```sh
$ pnpm install
```

Run PHP server
```sh
$ php -S localhost:8000
```

This will run a php server on localhost with port 8000

In a new terminal window, start vite

```sh
$ pnpm run dev
```

## Routing

You can create more routes in `src/routes.php`

For now, only GET and POST methods are supported, more methods will be added in future.

## Views and Leaf Templates

Views are essentially HTML files with added template functionalities.

You can create more views in `src/views` directory with the format `filename.leaf.html`

All view files should have an extension of `.leaf.html`

When rendering a view in routes, you will need to enter the view path relative to the view folder and without the extension

For example, with a project structure of the following (This is the complete project structure, unnecessary directories and files are hidden for this example)
```sh
.
├── src
│   ├── Controllers
│   │   └── SampleController.php
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
> [!NOTE]  
> Migrations and models are not implemented yet, they will be added in the future along with database helpers.

