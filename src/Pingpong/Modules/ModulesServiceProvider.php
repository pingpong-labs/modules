<?php namespace Pingpong\Modules;

use Illuminate\Support\Str;
use Pingpong\Modules\Commands;
use Illuminate\Support\ServiceProvider;

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
            return new Commands\ModulePublisherCommand;
        });
    }

    /**
     * Register "module:setup" command.
     */
    protected function registerSetupCommand()
    {
        $this->app['modules.setup'] = $this->app->share(function ($app)
        {
            return new Commands\ModuleSetupCommand;
        });
    }

    /**
     * Register "module:make" command.
     */
    public function registerGeneratorCommand()
    {
        $this->app['modules.maker'] = $this->app->share(function ($app)
        {
            return new Commands\ModuleMakeCommand;
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
        $this->app['modules.migrate'] = $this->app->share(function ($app)
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
            return new Commands\ModuleMigratePublishCommand;
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
     * Register "module:provider" command.
     */
    protected function registerProviderCommand()
    {
        $this->app->bindShared('modules.provider', function ($app)
        {
            return new Commands\ModuleGenerateProviderCommand;
        });
    }

    /**
     * Register "module:migrate-rollback" command.
     */
    protected function registerMigrateRollbackCommand()
    {
        $this->app->bindShared('modules.migrate.rollback', function ($app)
        {
            return new Commands\ModuleMigrateRollbackCommand;
        });
    }

    /**
     * Register "module:migrate-reset" command.
     */
    protected function registerMigrateResetCommand()
    {
        $this->app->bindShared('modules.migrate.reset', function ($app)
        {
            return new Commands\ModuleMigrateResetCommand;
        });
    }

    /**
     * Register "module:migrate-reset" command.
     */
    protected function registerMigrateRefreshCommand()
    {
        $this->app->bindShared('modules.migrate.refresh', function ($app)
        {
            return new Commands\ModuleMigrateRefreshCommand;
        });
    }

    /**
     * Register "module:filter-make" command.
     */
    protected function registerGenerateFilterCommand()
    {
        $this->app->bindShared('modules.generate.filter', function ($app)
        {
            return new Commands\ModuleGenerateFilterCommand;
        });
    }

    /**
     * Register "module:install" command.
     */
    protected function registerInstallCommand()
    {
        $this->app->bindShared('modules.install', function ($app)
        {
            return new Commands\ModuleInstallCommand;
        });
    }

    /**
     * Register "module:update" command.
     */
    protected function registerUpdateCommand()
    {
        $this->app->bindShared('modules.update', function ($app)
        {
            return new Commands\ModuleUpdateCommand;
        });
    }

    /**
     * Register "module:list" command.
     */
    protected function registerListCommand()
    {
        $this->app->bindShared('modules.list', function ($app)
        {
            return new Commands\ModuleListCommand;
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
        $this->registerProviderCommand();
        $this->registerMigrateRollbackCommand();
        $this->registerMigrateResetCommand();
        $this->registerMigrateRefreshCommand();
        $this->registerInstallCommand();
        $this->registerUpdateCommand();
        $this->registerGenerateFilterCommand();
        $this->registerListCommand();

        $this->commands([
            'modules.controller',
            'modules.model',
            'modules.publisher',
            'modules.setup',
            'modules.maker',
            'modules.seed-maker',
            'modules.seeder',
            'modules.migrate',
            'modules.migration-maker',
            'modules.command-maker',
            'modules.migration-publisher',
            'modules.enable',
            'modules.disable',
            'modules.use',
            'modules.provider',
            'modules.migrate.rollback',
            'modules.migrate.reset',
            'modules.migrate.refresh',
            'modules.install',
            'modules.update',
            'modules.generate.filter',
            'modules.list',
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
