<?php

return [

    'modules'	=>	app_path('Modules'),

	'assets'	=>	public_path('modules'),

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