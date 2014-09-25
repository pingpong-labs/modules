<?php namespace Pingpong\Modules\Publishing;

class MigrationPublisher extends AssetPublisher {

    /**
     * Publish migrations form the specified module.
     *
     * @param $module
     */
    protected function publishFromModule($module)
    {
        if ( ! $this->module->has($module))
        {
            $this->console->error("Module [{$module}] does not exist.");

            exit;
        }

        $this->filesystem->copyDirectory($this->getPublishingPath($module), $this->getDestinationPath($module));

        $this->console->info("Migrations published from module : {$module}");
    }

    /**
     * Get asset path from the specified module.
     *
     * @param $module
     * @return string
     */
    protected function getPublishingPath($module)
    {
        return $this->module->getModulePath($module) . $this->config->get('modules::paths.generator.migration');
    }

    /**
     * Get the destination path for the specified module.
     *
     * @param $module
     * @return string
     */
    protected function getDestinationPath($module)
    {
        return $this->config->get('modules::paths.migration');
    }

} 