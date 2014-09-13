<?php

return [

    'modules'	=>	app_path('Modules'),

	'assets'	=>	public_path('modules'),

    'generator' => [
        'controller' => 'Http/Controllers',
        'seeder' => 'Database/Seeders',
        'migration' => 'Database/Migrations',
        'model' => 'Database/Models',
        'repository' => 'Repository',
        'command' => 'Console',
        'provider' => 'Console',
        'test' => 'Tests',
    ]
];