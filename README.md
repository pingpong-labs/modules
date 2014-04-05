Laravel 4 - Simple Modules
=========================

[![Build Status](https://travis-ci.org/pingpong-labs/modules.svg?branch=1.0.2)](https://travis-ci.org/pingpong-labs/modules)

### Server Requirements

- PHP 5.4 or higher

### Installation

Open your composer.json file and add a new required package.
  ```
  "pingpong/modules": "1.0.*" 
  ```
Next, open a terminal and run.
  ```
  composer update 
  ```
Next, Add new service provider in `app/config/app.php`.
  ```php
  'Pingpong\Modules\ModulesServiceProvider',
  ```
Next, Add new class alias in `app/config/php`.
  ```php
  'Module'        => 'Pingpong\Modules\Facades\Module',
  ```
Next, publish package configration. Open your terminal and run:
  ```
  php artisan config:publish pingpong/modules
  ```
Done.

### Setup modules folder for first use.

By default modules folder is in your laravel route directory. For first use, please run this command on your terminal.
  ```
  php artisan module:setup
  ```

### Folder Structure
  
  Now, naming modules must use a capital letter on the first letter. For example: Blog, News, Shop, etc.

  ```
  app/
  public/
  vendor/
  Modules
  |-- Blog
      |-- commands/
      |-- config/
      |-- controllers/
      |-- database/
          |-- migrations/
          |-- seeds/
      |-- models/
      |-- start/
          |-- global.php
      |-- tests/
      |-- views/
      |-- BlogServiceProvider.php
      |-- filters.php
      |-- routes.php
  ```

### Artisan CLI
  
1. Create new module.

  ```
  php artisan module:make blog
  ```
  
2. Create new command the specified module.
  
  ```
  php artisan module:command blog CostumCommand

  php artisan module:command blog CostumCommand --command=costum:command

  php artisan module:command blog CostumCommand --namespace=Modules\Blog\Commands
  ```
  
3. Create new migration for the specified module.

  ```
  php artisan module:migrate:make blog users

  php artisan module:migrate:make blog users --fields="username:string, password:string"
  ```
  
4. Create new seed for the specified module.

  ```
  php artisan module:seed:make blog users
  ```
  
5. Migrate from the specified module.

  ```
  php artisan module:migrate blog
  ```
  
6. Migrate from all modules.

  ```
  php artisan module:migrate
  ```
  
7. Seed from the specified module.

  ```
  php artisan module:seed blog
  ```
  
8. Seed from all modules.
 
  ```
  php artisan module:seed
  ```

9. Create new controller for the specified module.

  ```
  php artisan module:controller blog SiteController
  ```

10. Publish assets from the specified module to public directory.

  ```
  php artisan module:publish blog
  ```

11. Publish assets from all modules to public directory.

  ```
  php artisan module:publish
  ```

12. Create new model for the specified module.

  ```
  php artisan module:model blog User
  ```

### Facades API

1. Get asset url from specified module.

  ```php
  Module::asset('blog', 'image/news.png');
  ```

2. Generate new stylesheet tag.

  ```php
  Module::style('blog', 'image/all.css');
  ```

3. Generate new stylesheet tag.

  ```php
  Module::script('blog', 'js/all.js');
  ```

4. Get all modules.

  ```php
  Module::all();
  ```

5. Get modules path.

  ```php
  Module::getPath();
  ```

6. Get assets modules path.

  ```php
  Module::getAssetsPath();
  ```

### Costum Service Provider

  When your create new module. It's also create new costum service provider. For example, if you create new module named `blog`. It's also create new Service Provider named `BlogServiceProvider` with namespace `Modules\Blog`. I think is useful for registering costum command for each module. This file is not autoload, you can autoload this file using `psr-0` or `psr-4`. That file maybe look like this:

  ```php
  <?php namespace Modules\Blog;

  use Illuminate\Support\ServiceProvider;

  class BlogServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
      //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
      return array();
    }

  }

  ```

### Costum Namespaces

  When you create new module it's also registering new costum namespace for `Lang`, `View` and `Config`. For example, if you create new module named `blog`, it's will registering new namespace/hint `blog` for that module. That, you can use that namespace for calling Lang, View or Config.
  This is an example of its use.

  Calling Lang:
  ```php
  Lang::get('blog::group.name')
  ```

  Calling View:
  ```php
  View::make('blog::index')

  View::make('blog::partials.sidebar')
  ```

  Calling Config:
  ```php
  Config::get('blog::group.name')
  ```

### License

  This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)