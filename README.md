###Laravel 4.* Modules Package

This package makes laravel can implement HMVC or modular. 
With this package, you can create a web application that is structured and easier to manage a large web application. 

### New Feature and Changes
1. Asset for each module.
2. Update artisan command name `module:db-seed` to `module:seed`.
3. Now, all modules require module.json file. This is details for each module.
4. Move this repository from `gravitano/modules` to `pingpong-labs/modules`.
5. Adding new facade `Module`. It's very cool for get all modules, get details from each module and others.
6. Adding new classes for more flexible scripts.
7. Update some generator class.
8. Remove config file.
9. Adding new `phpunit.xml` file when creating a new module.
10. Adding new `module.json` file when creating a new module.
11. Moving `modules` folder to root laravel directory again.
12. Adding new commands `module:cleanup` and `module:asset-publish`.

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
After this repository move to `pingpong/modules`, `modules` path is in public path. 

```
laravel/
|-- app
|-- bootstrap
|-- public
    |-- modules
        |-- blog
            |-- assets
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
            |-- module.json
            |-- phpunit.xml
|-- vendor
```

### Setup for first use

Note: Before creating a new module, run 

  ```
  php artisan module:setup
  ```
it will set the path and folder configuration module. 

### Artisan CLI 
1. Creating a new module. 

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
  It's will be created `SiteController` on blog module.
  
4. Running migration
  
  Running migration from all modules
  ```
  php artisan module:migrate 
  ```

  Running migration from specified module.
  
  Format: 
  `php artisan module:migrate <module-name>`
  ```
  php artisan module:migrate blog 
  ```

  
5. Seeding database

  Seeding from all modules
   ```
  php artisan module:seed 
  ```
  
  Seeding from specified module.
  
  Format: 
  `php artisan module:migrate-make <module-name>`

  ```
  php artisan module:seed blog 
  ```
  
6. Reset database and re-run all migrations from all modules.

   ```
  php artisan module:migrate-refresh 

  php artisan module:migrate-refresh --database="connection-name"
  ```

7. Publishing assets.

  For all modules:
   ```
  php artisan module:asset-publish 
  ```

  For specified module:
   ```
  php artisan module:asset-publish blog
  ```

8. Cleaning up assets.
   When you publish the assets of a particular module or on all modules, the assets simply copied to public / modules and not removed. 
   Here's a useful function. That is to remove all of the assets of a particular module after publishing.

   For all modules
   ```terminal
   php artisan module:cleanup
   ```

   For each module
   ```terminal
   php artisan module:cleanup blog
   ```
  
### Module Namespaces

When you creating a new module, it's also creating a new namespace view, lang and config for that module. For example if you create a new module something like 'Blog' you can calling a lang, view and config like below:

Calling view:

`View::make('<module-name>::<view-name>')`

```php
  View::make('blog::index', array());
  View::make('blog::content.index')
```

Calling config:

`Config::get('<module-name>::<config>')`

```php
  Config::get('blog::site.author')
```

Calling lang:

`Lang::get('<module-name>::<lang>')`

```php
  Lang::get('blog::title')
```
### Module Facades

1. Get all modules.

  ```php
  Module::all()
  ```

2. Get all modules with details

  ```php
  Module::allWithDetails()
  ```

3. Is module exists?

  ```php
  Module::has('blog')
  ```

4. Get details from specified module

  ```php
  Module::getDetails('blog')
  ```

5. Get JSON file from specified module

  ```php
  Module::getJsonFile('blog')
  ```

6. Get JSON content from specified module

  ```php
  Module::getJsonContent('blog')
  ```

7. Get JSON content and Convert JSON module detail to object

  ```php
  Module::parseJson('blog')
  ```

8. Get module path

  ```php
  Module::getPath()
  ```
  The result is `public_path() . 'modules'`

9. Get module directory name

  ```php
  Module::getDirName()
  ```
  The result is `modules`

10. HTML script and style tag for each module
  
  Format :
  ```
  Module::style($moduleName, $assetURL, $attributes = array(), $secure = FALSE);

  Module::script($moduleName, $assetURL, $attributes = array(), $secure = FALSE);
  ````
  Example:
  ```php

  Module::style('blog', 'css/style.css');

  Module::script('blog', 'js/app.js');
  ```

  For secure url:

  ```php
  Module::script('blog', 'css/style-responsive.css', array(),  TRUE);

  Module::script('blog', 'js/jquery.js', array(),  TRUE);
  ```

  For many scripts or styles:

  ```php

  Module::styles('blog', [
    array('css/bootstrap.css', array('id'=>'myCSS')),
    array('css/app.css', array(), true),
  ]);

  Module::scripts('blog', [
    array('js/jquery.js', array('id'=>'myJS')),
    array('js/app.js', array(), true),
  ]);

  ```


11. Get asset from specified module
  
  Format:
  `
  Module::asset($moduleName, $assetUrl, $secure = FALSE)
  `
  ```php
  Module::asset('blog' , 'images/avatar.png', TRUE)
  ```

### License

This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)