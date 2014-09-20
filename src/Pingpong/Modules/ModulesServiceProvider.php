<?php namespace Pingpong\Modules;

use Illuminate\Support\Str;
use Pingpong\Modules\Commands;
use Pingpong\Modules\Handlers;
use Illuminate\Support\ServiceProvider;

/**
 * Class ModulesServiceProvider
 * @package Pingpong\Modules
 */
class ModulesServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Booting the package.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('pingpong/modules');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerServices();
        $this->registerCommands();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    protected function registerServices()
    {
        $this->app->bindShared('modules.finder', function ($app)
        {
            return new Finder($app['files'], $app['config']);
        });

        $this->app->bindShared('modules', function ($app)
        {
            return new Module(
                $app['modules.finder'],
                $app['config'],
                $app['view'],
                $app['translator'],
                $app['files'],
                $app['html'],
                $app['url']
            );
        });

        $this->app->booting(function ($app)
        {
            $app['modules']->register();
        });
    }

    /**
     * Register "module:controller" command.
     */
    protected function registerControllerCommand()
    {
        $this->app['modules.controller'] = $this->app->share(function ($app)
        {
            return new Commands\ModuleControllerCommand($app['modules']);
        });
    }

    /**
     * Register "module:model" command.
     */
    protected function registerModelCommand()
    {
        $this->app['modules.model'] = $this->app->share(function ($app)
        {
            return new Commands\ModuleModelCommand;
        });
    }

    /**
     * Register "module:publish" command.
     */
    protected function registerPublisherCommand()
    {
        $this->app['modules.publisher'] = $this->app->share(function ($app)
        {
            $handler = new Handlers\ModulePublisherHandler($app['modules'], $app['files']);

            return new Commands\ModulePublisherCommand($handler);
        });
    }

    /**
     * Register "module:setup" command.
     */
    protected function registerSetupCommand()
    {
        $this->app['modules.setup'] = $this->app->share(function ($app)
        {
            $handler = new Handlers\ModuleSetupHandler($app['modules'], $app['files']);

            return new Commands\ModuleSetupCommand($handler);
        });
    }

    /**
     * Register "module:make" command.
     */
    public function registerGeneratorCommand()
    {
        $this->app['modules.maker'] = $this->app->share(function ($app)
        {
            $hander = new Handlers\ModuleGeneratorHandler($app['modules'], $app['files']);

            return new Commands\ModuleMakeCommand($hander);
        });
    }

    /**
     * Register "module:seed-make" command.
     */
    protected function registerSeedMakerCommand()
    {
        $this->app['modules.seed-maker'] = $this->app->share(function ($app)
        {
            return new Commands\ModuleSeedMakeCommand();
        });
    }

    /**
     * Register "module:seed" command.
     */
    protected function registerSeedCommand()
    {
        $this->app['modules.seeder'] = $this->app->share(function ($app)
        {
            return new Commands\ModuleSeedCommand($app['modules'], $app['files']);
        });
    }

    /**
     * Register "module:migrate" command.
     */
    protected function registerMigratorCommand()
    {
        $this->app['modules.migrator'] = $this->app->share(function ($app)
        {
            return new Commands\ModuleMigrateCommand($app['modules']);
        });
    }

    /**
     * Register "module:migration" command.
     */
    protected function registerMigrationMakerCommand()
    {
        $this->app['modules.migration-maker'] = $this->app->share(function ($app)
        {
            return new Commands\ModuleMigrateMakeCommand($app['modules'], $app['files']);
        });
    }

    /**
     * Register "module:command" command.
     */
    protected function registerCommandCommand()
    {
        $this->app['modules.command-maker'] = $this->app->share(function ($app)
        {
            return new Commands\ModuleCommandCommand;
        });
    }

    /**
     * Register "module:publish-migration" command.
     */
    protected function registerMigrationPublisherCommand()
    {
        $this->app['modules.migration-publisher'] = $this->app->share(function ($app)
        {
            $handler = new Handlers\ModuleMigrationPublisherHandler($app['modules'], $app['files']);

            return new Commands\ModuleMigratePublishCommand($handler);
        });
    }

    /**
     * Register "module:enable" command.
     */
    protected function registerEnableCommand()
    {
        $this->app->bindShared('modules.enable', function ($app)
        {
            return new Commands\ModuleEnableCommand;
        });
    }

    /**
     * Register "module:disable" command.
     */
    protected function registerDisableCommand()
    {
        $this->app->bindShared('modules.disable', function ($app)
        {
            return new Commands\ModuleDisableCommand;
        });
    }

    /**
     * Register "module:use" command.
     */
    protected function registerUseCommand()
    {
        $this->app->bindShared('modules.use', function ($app)
        {
            return new Commands\ModuleUseCommand;
        });
    }

    /**
     * Register the commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        $this->registerControllerCommand();
        $this->registerModelCommand();
        $this->registerPublisherCommand();
        $this->registerSetupCommand();
        $this->registerGeneratorCommand();
        $this->registerEnableCommand();
        $this->registerDisableCommand();
        $this->registerSeedCommand();
        $this->registerSeedMakerCommand();
        $this->registerMigratorCommand();
        $this->registerMigrationMakerCommand();
        $this->registerMigrationPublisherCommand();
        $this->registerCommandCommand();
        $this->registerUseCommand();

        $this->commands([
            'modules.controller',
            'modules.model',
            'modules.publisher',
            'modules.setup',
            'modules.maker',
            'modules.seed-maker',
            'modules.seeder',
            'modules.migrator',
            'modules.migration-maker',
            'modules.command-maker',
            'modules.migration-publisher',
            'modules.enable',
            'modules.disable',
            'modules.use',
        ]);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('modules.finder', 'modules');
    }
}
