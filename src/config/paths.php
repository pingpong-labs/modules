<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Modules path
    |--------------------------------------------------------------------------
    |
    | Here you may update the modules path.
    |
    */

    'modules' => app_path('Modules'),

    /*
    |--------------------------------------------------------------------------
    | Modules assets path
    |--------------------------------------------------------------------------
    |
    | Here you may update the modules assets path.
    |
    */

    'assets' => public_path('modules'),

    /*
    |--------------------------------------------------------------------------
    | The migrations path
    |--------------------------------------------------------------------------
    |
    | Where you run 'module:publish-migration' command, where do you publish the
    | the migration files?
    |
    */

    'migration' => app_path('database/migrations'),

    /*
    |--------------------------------------------------------------------------
    | Generator path
    |--------------------------------------------------------------------------
    | 
    | Here you may update the modules generator path.
    |
    */

    'generator' => [
        'assets' => 'Assets',
        'config' => 'Config',
        'command' => 'Console',
        'migration' => 'Database/Migrations',
        'model' => 'Database/Models',
        'repository' => 'Database/Repositories',
        'seeder' => 'Database/Seeders',
        'controller' => 'Http/Controllers',
        'filter' => 'Http/Filters',
        'request' => 'Http/Requests',
        'provider' => 'Providers',
        'lang' => 'Resources/lang',
        'views' => 'Resources/views',
        'test' => 'Tests',
    ]
];