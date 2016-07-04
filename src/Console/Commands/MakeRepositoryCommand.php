<?php

namespace Kitano\Repository\Console\Commands;

use Kitano\Repository\Console\Commands\Creators\RepositoryCreator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Console\Command;

class MakeRepositoryCommand extends Command
{
    /** The name and signature of the console command. @var string */
    protected $name = 'make:repository';

    /** The console command description. @var string */
    protected $description = 'Create a new repository class';

    /** @var RepositoryCreator */
    protected $creator;

    /** @var */
    protected $composer;

    /**
     * @param RepositoryCreator $creator
     */
    public function __construct(RepositoryCreator $creator)
    {
        parent::__construct();

        $this->creator  = $creator;
        $this->composer = app()['composer'];
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $arguments = $this->argument();
        $options   = $this->option();

        $this->writeRepository($arguments, $options);
        $this->composer->dumpAutoloads();
    }

    /**
     * @param $arguments
     * @param $options
     */
    protected function writeRepository($arguments, $options)
    {
        $repository = $arguments['repository'];
        $model      = $options['model'];

        if ($this->creator->create($repository, $model)) {
            $this->info("Successfully created the repository class");
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['repository', InputArgument::REQUIRED, 'The repository name.']
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
            ['model', null, InputOption::VALUE_OPTIONAL, 'The model name.', null],
        ];
    }
}
