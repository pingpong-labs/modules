<?php  namespace Pingpong\Modules\Commands;

use Illuminate\Console\Command;
use Pingpong\Modules\Generators\FileGenerator;
use Pingpong\Modules\Exceptions\FileAlreadyExistsException;

abstract class GeneratorCommand extends Command {

    /**
     * Get template contents.
     *
     * @return string
     */
    abstract protected function getTemplateContents();

    /**
     * Get the destination file path.
     *
     * @return string
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
        catch (FileAlreadyExistsException $e)
        {
            $this->error("File : {$path} already exists.");
        }
    }

} 