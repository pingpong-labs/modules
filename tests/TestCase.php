<?php

use Illuminate\Support\Facades\Log;

abstract class TestCase extends \Pingpong\Testing\TestCase {

    public static function getLaravel()
    {
    	return (new static)->createApplication();
    }

    /**
     * Get application aliases.
     *
     * @return array
     */
    protected function getApplicationAliases()
    {
        return [
            'App'             => 'Illuminate\Support\Facades\App',
            'Artisan'         => 'Illuminate\Support\Facades\Artisan',
            'Auth'            => 'Illuminate\Support\Facades\Auth',
            'Blade'           => 'Illuminate\Support\Facades\Blade',
            'Cache'           => 'Illuminate\Support\Facades\Cache',
            'ClassLoader'     => 'Illuminate\Support\ClassLoader',
            'Config'          => 'Illuminate\Support\Facades\Config',
            'Controller'      => 'Illuminate\Routing\Controller',
            'Cookie'          => 'Illuminate\Support\Facades\Cookie',
            'Crypt'           => 'Illuminate\Support\Facades\Crypt',
            'DB'              => 'Illuminate\Support\Facades\DB',
            'Eloquent'        => 'Illuminate\Database\Eloquent\Model',
            'Event'           => 'Illuminate\Support\Facades\Event',
            'File'            => 'Illuminate\Support\Facades\File',
            // 'Form'            => 'Illuminate\Support\Facades\Form',
            'Hash'            => 'Illuminate\Support\Facades\Hash',
            // 'HTML'            => 'Illuminate\Support\Facades\HTML',
            'Input'           => 'Illuminate\Support\Facades\Input',
            'Lang'            => 'Illuminate\Support\Facades\Lang',
            'Log'             => 'Illuminate\Support\Facades\Log',
            'Mail'            => 'Illuminate\Support\Facades\Mail',
            'Paginator'       => 'Illuminate\Support\Facades\Paginator',
            'Password'        => 'Illuminate\Support\Facades\Password',
            'Queue'           => 'Illuminate\Support\Facades\Queue',
            'Redirect'        => 'Illuminate\Support\Facades\Redirect',
            'Redis'           => 'Illuminate\Support\Facades\Redis',
            'Request'         => 'Illuminate\Support\Facades\Request',
            'Response'        => 'Illuminate\Support\Facades\Response',
            'Route'           => 'Illuminate\Support\Facades\Route',
            'Schema'          => 'Illuminate\Support\Facades\Schema',
            'Seeder'          => 'Illuminate\Database\Seeder',
            'Session'         => 'Illuminate\Support\Facades\Session',
            'SSH'             => 'Illuminate\Support\Facades\SSH',
            'Str'             => 'Illuminate\Support\Str',
            'URL'             => 'Illuminate\Support\Facades\URL',
            'Validator'       => 'Illuminate\Support\Facades\Validator',
            'View'            => 'Illuminate\Support\Facades\View',
        ];
    }

    /**
     * Get application providers.
     *
     * @return array
     */
    protected function getApplicationProviders()
    {
        return [
            'Illuminate\Foundation\Providers\ArtisanServiceProvider',
            'Illuminate\Auth\AuthServiceProvider',
            'Illuminate\Cache\CacheServiceProvider',
            'Illuminate\Session\CommandsServiceProvider',
            'Illuminate\Foundation\Providers\ConsoleSupportServiceProvider',
            'Illuminate\Routing\ControllerServiceProvider',
            'Illuminate\Cookie\CookieServiceProvider',
            'Illuminate\Database\DatabaseServiceProvider',
            'Illuminate\Encryption\EncryptionServiceProvider',
            'Illuminate\Filesystem\FilesystemServiceProvider',
            'Illuminate\Hashing\HashServiceProvider',
            // 'Illuminate\Html\HtmlServiceProvider',
            'Illuminate\Log\LogServiceProvider',
            'Illuminate\Mail\MailServiceProvider',
            'Illuminate\Database\MigrationServiceProvider',
            'Illuminate\Pagination\PaginationServiceProvider',
            'Illuminate\Queue\QueueServiceProvider',
            'Illuminate\Redis\RedisServiceProvider',
            'Illuminate\Remote\RemoteServiceProvider',
            'Illuminate\Auth\Reminders\ReminderServiceProvider',
            'Illuminate\Database\SeedServiceProvider',
            'Illuminate\Session\SessionServiceProvider',
            'Illuminate\Translation\TranslationServiceProvider',
            'Illuminate\Validation\ValidationServiceProvider',
            'Illuminate\View\ViewServiceProvider',
        ];
    }

    /**
     * @return array
     */
    protected function getApplicationPaths()
    {
        $basePath = realpath(__DIR__.'/../fixture');
        
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