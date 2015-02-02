<?php namespace Pingpong\Modules\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class PublishConfigCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:publish-config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish modules\'s configuration file to the config path.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        if( ! file_exists($destinationPath = base_path() . '/config/modules.php'))
        {
            $path = realpath(__DIR__ . '/../../../../src/config/config.php'); 

            $this->laravel['files']->copy($path, $destinationPath);

            $this->info("Config file published successfully.");
        }
        else
        {
            $this->error("File : {$destinationPath} already exist!");
        }
    }

}
