###Laravel 4.* Modules Package

This package makes laravel can implement HMVC or modular. 
With this package, you can create a web application that is structured and easier to manage a large web application. 

### Installation 
Open your composer.json file, and add the new required package. 

  ```
  "pingpong/modules": "dev-master" 
  ```

Next, open a terminal and run. 

  ```
  composer update 
  ```

After the composer updated. Add new service provider in `app/config/app.php`. 

  ```
  'Pingpong\Modules\ModulesServiceProvider' 
  ```

Finish. 

### Folder Structure

```
laravel/
|-- app
|-- bootstrap
|-- modules
    |-- blog
        |-- config
        |-- controllers
        |-- database
            |-- migrations
            |-- seeds
        |-- models
        |-- tests
        |-- views
        |-- filters.php
        |-- routes.php
|-- vendor
```

### Introduction 
After the installation is finished you will get a new artisan features for: 

1. Creating new module.
2. Run migration from specified module.
3. Run database seeder from specified module.
4. Creating Controller
5. Creating migration

Note: Before creating a new module, run 

  ```
  php artisan module:setup
  ```
it will set the path and folder configuration module. 

### Artisan CLI 
1. Create a new module. 

  Format: 
  `php artisan module:make <module-name>`
  ```
  php artisan module:make blog 
  ```
  
2. Creating Migration 

  Format: 
  `php artisan module:migrate-make <module-name> <table-name> --fields="<optional>"`
  ```
  php artisan module:migrate-make blog user --fields="username:string, password:string" 
  ```
  
3. Creating Controller

  Format: 
  `php artisan module:controller-make <module-name> <controller-name>`
  ```
  php artisan module:controller-make blog Site 
  ```
  It will be created `SiteController` on blog module.
  
4. Running migration

  Format: 
  `php artisan module:migrate <module-name>`
  ```
  php artisan module:migrate blog 
  ```
  
5. Seeding database
  
  Format: 
  `php artisan module:migrate-make <module-name>`

  ```
  php artisan module:db-seed blog 
  ```
  
### Module Namespaces

When you creating a new module, it also creating a new namespace view, lang and config for that module. For example if you create a new module something like 'Blog' you can calling a lang, view and config like below:

Calling view:

`View::make('<module-name>::<view-name>')`

```php
  View::make('blog::index');
  View::make('blog::content.index')
```

Calling config:

`Config::get('<module-name>::<config>')`

```php
  Config::get('blog::site.author')
```

Calling lang

`Lang::get('<module-name>::<lang>')`

```php
  Lang::get('blog::title')
```

### License

This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
