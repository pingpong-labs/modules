<?php

namespace Pingpong\Modules\Commands;

use Illuminate\Console\Command;
use Pingpong\Modules\Publishing\AssetPublisher;
use Pingpong\Modules\Publishing\LangPublisher;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class PublishCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish assets from the specified module or from all modules.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        if ($name = $this->argument('module')) {
            return $this->publish($name);
        }

        $this->publishAll();
    }

    /**
     * Publish assets from all modules.
     */
    public function publishAll()
    {
        foreach ($this->laravel['modules']->enabled() as $module) {
            $name = $module->getStudlyName();

            $this->publish($name);
        }
    }

    /**
     * Publish assets from the specified module.
     *
     * @param string $name
     */
    public function publish($name)
    {
        $module = $this->laravel['modules']->findOrFail($name);

        $published = [];

        if ($this->askConfirmation('Do you want to publish module\'s assets?')) {
            $this->publishAsset($module);
            $published[] = 'asset';
        }

        if ($this->askConfirmation('Do you want to publish module\'s translation files?')) {
            $this->publishTranslation($module);
            $published[] = 'translation';
        }

        if (count($published) > 0) {
            $this->line("<info>Published</info>: {$module->getStudlyName()}");

            return;
        }

        $this->comment('Nothing to publish.');
    }

    /**
     * Publish asset files from specific module.
     *
     * @param \Pingpong\Modules\Module $module
     */
    protected function publishAsset($module)
    {
        with(new AssetPublisher($module))
            ->setRepository($this->laravel['modules'])
            ->setConsole($this)
            ->publish();
    }

    /**
     * Publish translation files from specific module.
     *
     * @param \Pingpong\Modules\Module $module
     */
    protected function publishTranslation($module)
    {
        with(new LangPublisher($module))
            ->setRepository($this->laravel['modules'])
            ->setConsole($this)
            ->publish();
    }

    /**
     * Ask confirmation alert.
     *
     * @param string $message
     *
     * @return bool
     */
    protected function askConfirmation($message)
    {
        if ($this->option('force')) {
            return true;
        }

        return $this->confirm($message);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Skip confirmation alert.', null],
        ];
    }
}
