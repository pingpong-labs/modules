Laravel 4 - Simple Modules
=========================

[![Build Status](https://travis-ci.org/pingpong-labs/modules.svg?branch=master)](https://travis-ci.org/pingpong-labs/modules)
[![Latest Stable Version](https://poser.pugx.org/pingpong/modules/v/stable.svg)](https://packagist.org/packages/pingpong/modules)
[![Total Downloads](https://poser.pugx.org/pingpong/modules/downloads.svg)](https://packagist.org/packages/pingpong/modules)
[![Latest Unstable Version](https://poser.pugx.org/pingpong/modules/v/unstable.svg)](https://packagist.org/packages/pingpong/modules)
[![License](https://poser.pugx.org/pingpong/modules/license.svg)](https://packagist.org/packages/pingpong/modules)

### Server Requirements

- PHP 5.4 or higher

### Donation

If you find this source useful, you can share some milk to me if you want ^_^

[![Donate](https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=95ZPLBB8U3T9N)

### Changes Log

**1.1.5 to 1.1.6**

- Bug fixes

**1.1.4 to 1.1.5**

See [#39](https://github.com/pingpong-labs/modules/pull/39)

**1.1.3 to 1.1.4**

See [#35](https://github.com/pingpong-labs/modules/pull/35)

**1.1.2 to 1.1.3**

- Added new controller validator. Please see [this example](https://github.com/pingpong-modules/Admin/blob/master/Http/Controllers/Auth/RegisterController.php).
- Added `--master` option to `module:seed-make` command.
- Fix `module:seed` command

**1.1.1 to 1.1.2**

- Added new feature, now every module can require other composer package. you can define the required package in your module.json file. for example :

```json
{
    "name": "Admin",
    "alias": "admin",
    "description": "Admin Modules",
    "keywords": [
      "admin",
      "modules",
      "pingpong"
    ],
    "require": {
      "pingpong/trusty": "1.*",
      "pingpong/shortcode": "1.*"
    },
    "active": 1
}
```

If the `require` key is not empty, then we will install its packages automatically when you install that module.

**1.1.0 to 1.1.1**

- Added new artisan commands `module:install`. This command is useful for installing the modules.
For example i have `Admin` modules [here](https://github.com/pingpong-modules/Admin). You can install it using this command.

```
php artisan module:install pingpong-modules/Admin
```

By default, that module will stored in current modules directory. If you want to store that in other directory or other path, simply specify the `--path` option. For example :
```
php artisan module:install pingpong-modules/Admin --path=App/Modules
```

**1.0.* to 1.1.0**

See [#32](https://github.com/pingpong-labs/modules/pull/32)

**1.0.10 to 1.0.11**

See [#26](https://github.com/pingpong-labs/modules/pull/26)

**1.0.9 to 1.0.10**

- Added support for [#13](https://github.com/pingpong-labs/modules/pull/13) : enable and disable module.
- There is new artisan command `module:enable` and `module:disable`.

**1.0.8 to 1.0.9**

- Fix `module:seed-make` command when running `module:make` command.
- Command Improvement.

**1.0.7 to 1.0.8**

- There is command name changed :
  -  `php artisan module:migrate:make` to `php artisan module:migration`
  -  `php artisan module:seed:make` to `php artisan module:seed-make`
- Merged [#8](https://github.com/pingpong-labs/modules/pull/18) : Fix constructor error.
- Package improvement.

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
  ```
  php artisan config:publish pingpong/modules
  ```
Done.

### Setup modules folder for first use

By default modules folder is in your `app/` directory. For first use, please run this command on your terminal.
  ```
  php artisan module:setup
  ```

### New Folder Structure
  
  Now, naming modules must use a capital letter on the first letter. For example: Blog, News, Shop, etc.

  ```
  app/
      Modules
      |-- Blog
          |-- Assets/
          |-- Config/
          |-- Console/
          |-- Database/
              |-- Migrations/
              |-- Models/
              |-- Repositories/
              |-- Seeders/
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
          |-- Tests/
          |-- module.json
          |-- start.php
  ```

  **Note:** File `start.php` is required for registering `View`, `Lang` and `Config` namespaces. If that file does not exist, an exception `FileMissingException` is thrown.

### Autoloading

Now, by default the controllers, models and others not autoloaded automatically. You can autoload all modules using psr-4 or psr-0. For example :

```json
{
    "autoload": {
        "psr-4": {
            "Modules\\": "app/Modules"
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

Get asset url from specified module.

  ```php
  Module::asset('blog', 'image/news.png');
  ```

Generate new stylesheet tag.

  ```php
  Module::style('blog', 'image/all.css');
  ```

Generate new stylesheet tag.

  ```php
  Module::script('blog', 'js/all.js');
  ```

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

Get assets modules path.

  ```php
  Module::getAssetsPath();
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

Get module json property as array from a specified module.
```php
    Module::getProperties('blog')
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

### License

  This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
