# Pine: A PHP Framework
## About Pine

Pine is a minimal PHP Web Framework

[Visit Official Pine Docs](https://pine.raahimfareed.com/)

## Important
This is just a hobby project and is not complete in any way. If you are looking 
for a framework that is production compatible, check out 
[Laravel](https://github.com/laravel/laravel/). This is not meant to be used in 
a production environment. On the other hand, if you stumble upon any issues 
(which I assume are plenty), please open an issue on github so I can take a look 
at it.

## Prerequisites
- PHP 8
- Composer
- Node 20

## Installation

First clone the repository
```sh
git clone https://github.com/raahimfareed/pine.git
```

Install composer packages
```sh
composer install
```

Optional: If you intend on using ViteJS, you can install all js packages

We use pnpm for this but, you can use any package manager for node
```sh
pnpm install
# Or
npm install
```

Run PHP server
```sh
php -S localhost:8000
```

This will run a php server on localhost with port 8000

In a new terminal window, start vite

```sh
pnpm dev
```



## Migrations and Models
> [!NOTE]  
> Migrations and models are not implemented yet, they will be added in the future along with database helpers.

