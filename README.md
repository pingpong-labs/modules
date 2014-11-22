Laravel Modules
=========================

[![Build Status](https://travis-ci.org/pingpong-labs/modules.svg?branch=master)](https://travis-ci.org/pingpong-labs/modules)
[![Latest Stable Version](https://poser.pugx.org/pingpong/modules/v/stable.svg)](https://packagist.org/packages/pingpong/modules)
[![Total Downloads](https://poser.pugx.org/pingpong/modules/downloads.svg)](https://packagist.org/packages/pingpong/modules)
[![Latest Unstable Version](https://poser.pugx.org/pingpong/modules/v/unstable.svg)](https://packagist.org/packages/pingpong/modules)
[![License](https://poser.pugx.org/pingpong/modules/license.svg)](https://packagist.org/packages/pingpong/modules)
[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/pingpong-labs/modules/trend.png)](https://bitdeli.com/free "Bitdeli Badge")

- [Installation](https://github.com/pingpong-labs/modules#installation)
- [Changes Log](https://github.com/pingpong-labs/modules#changes-log)
- [Setup Modules Folder](https://github.com/pingpong-labs/modules#setup-modules-folder-for-first-use)
- [Folder Structure](https://github.com/pingpong-labs/modules#installation)
- [Autoloading](https://github.com/pingpong-labs/modules#autoloading)
- [Artisan CLI](https://github.com/pingpong-labs/modules#artisan-cli)
- [Facades API](https://github.com/pingpong-labs/modules#facades-api)
- [Custom Namespaces](https://github.com/pingpong-labs/modules##custom-namespaces)
- [Extra](https://github.com/pingpong-labs/modules#extra)
- [License](https://github.com/pingpong-labs/modules#license)

### Server Requirements

- PHP 5.4 or higher

### Donation

If you find this source useful, you can share some milk to me if you want ^_^

[![Donate](https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=95ZPLBB8U3T9N)

### Changes Log

See [this](https://github.com/pingpong-labs/modules/blob/master/changes.md)

### Installation

Open your composer.json file and add a new required package.
  ```
  "pingpong/modules": "1.*"
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
Next, publish package configuration. Open your terminal and run:

Laravel 4+
  ```
  php artisan config:publish pingpong/modules
  ```
  
Laravel 5
  ```
  php artisan publish:config pingpong/modules
  ```
  
Done.

### Setup modules folder for first use

For first use, please run this command on your terminal.
  ```
  php artisan module:setup
  ```

### New Folder Structure
  
  Now, naming modules must use a capital letter on the first letter. For example: Blog, News, Shop, etc.

  ```
  app/
  bootstrap/
  vendor/
  modules/
  |-- Blog
      |-- Assets/
      |-- Config/
      |-- Console/
      |-- Database/
          |-- Migrations/
          |-- Seeders/
      |-- Entities/
      |-- Http
          |-- Controllers
          |-- Filters
          |-- Requests
          |-- routes.php
      |-- Providers/
          |-- BlogServiceProvider.php
      |-- Resources/
          |-- lang/
          |-- views/
      |-- Repositories/
      |-- Tests/
      |-- module.json
      |-- start.php
  ```

### Autoloading

Now, by default the controllers, models and others not autoloaded automatically. You can autoload all modules using psr-4 or psr-0. For example :

```json
{
    "autoload": {
        "psr-4": {
            "Modules\\": "modules"
        }
    }
}
```

### Artisan CLI
  
Create new module.

  ```
  php artisan module:make blog
  ```

Use the specified module. Please see [#26](https://github.com/pingpong-labs/modules/pull/26).

```php
php artisan module:use blog
```

Show all modules in command line.

```
php artisan module:list
```
  
Create new command for the specified module.
  
  ```
  php artisan module:command CustomCommand blog

  php artisan module:command CustomCommand blog --command=custom:command

  php artisan module:command CustomCommand blog --namespace=Modules\Blog\Commands
  ```
  
Create new migration for the specified module.

  ```
  php artisan module:migration blog create_users_table

  php artisan module:migration blog create_users_table --fields="username:string, password:string"

  php artisan module:migration blog add_email_to_users_table --fields="email:string:unique"

  php artisan module:migration blog remove_email_from_users_table --fields="email:string:unique"

  php artisan module:migration blog drop_users_table
  ```

Rollback, Reset and Refresh The Modules Migrations.
```
  php artisan module:migrate-rollback

  php artisan module:migrate-reset

  php artisan module:migrate-refresh
```

Rollback, Reset and Refresh The Migrations for the specified module.
```
  php artisan module:migrate-rollback blog

  php artisan module:migrate-reset blog

  php artisan module:migrate-refresh blog
```
  
Create new seed for the specified module.

  ```
  php artisan module:seed-make users blog
  ```
  
Migrate from the specified module.

  ```
  php artisan module:migrate blog
  ```
  
Migrate from all modules.

  ```
  php artisan module:migrate
  ```
  
Seed from the specified module.

  ```
  php artisan module:seed blog
  ```
  
Seed from all modules.
 
  ```
  php artisan module:seed
  ```

Create new controller for the specified module.

  ```
  php artisan module:controller SiteController blog
  ```

Publish assets from the specified module to public directory.

  ```
  php artisan module:publish blog
  ```

Publish assets from all modules to public directory.

  ```
  php artisan module:publish
  ```

Create new model for the specified module.

  ```
  php artisan module:model User blog

  php artisan module:model User blog --fillable="username,email,password"
  ```

Create new service provider for the specified module.

  ```
  php artisan module:provider MyServiceProvider blog
  ```

Publish migration for the specified module or for all modules.
    This helpful when you want to rollback the migrations. You can also run `php artisan migrate` instead of `php artisan module:migrate` command for migrate the migrations.

For the specified module.
```
php artisan module:publish-migration blog
```

For all modules.
```
php artisan module:publish-migration
```

Enable the specified module.

```
php artisan module:enable blog
```

Disable the specified module.

```
php artisan module:disable blog
```

Generate new filter class.
```
php artisan module:filter-make AuthFilter
```

Update dependencies for the specified module.
```
php artisan module:update ModuleName
```

Update dependencies for all modules.
```
php artisan module:update
```

### Facades API

Get all modules.

  ```php
  Module::all();
  ```

Get all enabled module.
```php
  Module::enabled();
```

Get all disabled module.
```php
  Module::disabled();
```

Get modules path.

  ```php
  Module::getPath();
  ```

Get module path for the specified module.

  ```php
  Module::getModulePath('blog');
  ```

Enable a specified module.
```php
  Module::enable('blog')
```

Disable a specified module.
```php
  Module::disable('blog')
```

### Module Entity

Get an entity from a specific module.

```php
  $module = Module::get('blog');

  $module->getName();

  $module->getLowerName();

  $module->getPath();

  $module->getExtraPath('Assets');

  $module->enable();

  $module->disable();

  $module->delete();
```

### Custom Namespaces

  When you create a new module it also registers new custom namespace for `Lang`, `View` and `Config`. For example, if you create a new module named `blog`, it will also register new namespace/hint `blog` for that module. Then, you can use that namespace for calling `Lang`, `View` or `Config`.
  Following are some examples of its usage:

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

### Extra

If you need the modules that already created, maybe you could try and install some modules from the list below.

- [pingpong-modules](https://github.com/pingpong-modules)
- [AsgardCms Modules](https://github.com/AsgardCms)

### License

  This package is open-sourced software licensed under [The BSD 3-Clause License](http://opensource.org/licenses/BSD-3-Clause)

