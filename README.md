Laravel 4 - Simple Modules
=========================

### Installation

Open your composer.json file, and add the new required package.

  "pingpong/modules": "1.0.*" 

Next, open a terminal and run.

  composer update 

Next, Add new service provider in `app/config/app.php`.
  
  'Pingpong\Modules\ModulesServiceProvider',

Next, Add new class alias in `app/config/php`.

  'Module'	  	  => 'Pingpong\Modules\Facades\Module',

Next, publish package configration. Open your terminal and run:
  
  php artisan config:publish pingpong/modules

Done.

### Setup modules folder for first use.

By default modules folder is in your laravel route directory. For first use, please run this command on your terminal.

  php artisan module:setup

### Artisan CLI

### Facades API

### License

This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
  
