<?php namespace Tests\Pingpong\Modules;

use Illuminate\Support\Facades\Log;

abstract class TestCase extends \Pingpong\Testing\TestCase {

    public static function getLaravel()
    {
    	return (new static)->createApplication();
    }

    /**
     * @return array
     */
    protected function getApplicationPaths()
    {
        $basePath = realpath(__DIR__.'/../../../fixture');
        
        return [
            'app'     => "{$basePath}/app",
            'public'  => "{$basePath}/public",
            'base'    => $basePath,
            'storage' => "{$basePath}/app/storage",
        ];
    }

    /**
     * Get application timezone.
     *
     * @return string
     */
    protected function getApplicationTimezone()
    {
        return 'UTC';
    }

    /**
     * Get package aliases.
     *
     * @return array
     */
    protected function getPackageAliases()
    {
        return [
        	'Module' => 'Pingpong\Modules\Facades\Module'
        ];
    }
    /**
     * Get package providers.
     *
     * @return array
     */
    protected function getPackageProviders()
    {
        return [
        	'Pingpong\Modules\ModulesServiceProvider'
        ];
    }

    /**
     * @param $app
     */
    protected function registerBootedCallback($app)
    {
        putenv('MODULES_TEST=1');

        ini_set('display_errors', 1);
        
        Log::useFiles(storage_path().'/logs/laravel.log');
    }
}