<?php  namespace Pingpong\Modules\Commands;

use Illuminate\Console\Command;
use Pingpong\Modules\Generators\FileGenerator;
use Pingpong\Modules\Exceptions\FileAlreadyExistsException;

/**
 * Class GeneratorCommand
 * @package Pingpong\Modules\Commands
 */
abstract class GeneratorCommand extends Command {

    /**
     * @return mixed
     */
    abstract protected function getTemplateContents();

    /**
     * @return mixed
     */
    abstract protected function getDestinationFilePath();

    /**
     * Execute the console command.
     */
    public function fire()
    {
        $path = $this->getDestinationFilePath();

        $contents = $this->getTemplateContents();

        try
        {
            with(new FileGenerator($path, $contents))->generate();

            $this->info("Created : {$path}");
        }
        catch(FileAlreadyExistsException $e)
        {
            $this->error("File : {$path} already exists.");
        }
    }

} 