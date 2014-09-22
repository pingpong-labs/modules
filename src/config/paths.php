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
    'modules'	=>	app_path('Modules'),

    /*
    |--------------------------------------------------------------------------
    | Modules assets path
    |--------------------------------------------------------------------------
    |
    | Here you may update the modules assets path.
    |
    */
	'assets'	=>	public_path('modules'),

    /*
    |--------------------------------------------------------------------------
    | Generator path
    |--------------------------------------------------------------------------
    | 
    | Here you may update the modules generator path.
    |
    */
    'generator' => [
        'controller' => 'Http/Controllers',
        'filter' => 'Http/Filters',
        'seeder' => 'Database/Seeders',
        'migration' => 'Database/Migrations',
        'model' => 'Database/Models',
        'repository' => 'Database/Repositories',
        'command' => 'Console',
        'provider' => 'Providers',
        'test' => 'Tests',
    ]
];