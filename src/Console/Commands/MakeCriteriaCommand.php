<?php

namespace Kitano\Repository\Console\Commands;

use Kitano\Repository\Console\Commands\Creators\CriteriaCreator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Console\Command;

class MakeCriteriaCommand extends Command
{
    /** The name and signature of the console command. @var string */
    protected $name = 'make:criteria';

    /** The console command description. @var string */
    protected $description = 'Create a new criteria class';

    /** @var */
    protected $creator;

    /** @var */
    protected $composer;

    /**
     * @param CriteriaCreator $creator
     */
    public function __construct(CriteriaCreator $creator)
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

        $this->writeCriteria($arguments, $options);
        $this->composer->dumpAutoloads();
    }

    /**
     * Write the criteria.
     *
     * @param $arguments
     * @param $options
     */
    public function writeCriteria($arguments, $options)
    {
        $criteria = $arguments['criteria'];
        $model    = $options['model'];

        if ($this->creator->create($criteria, $model)) {
            $this->info("Succesfully created the criteria class.");
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
            ['criteria', InputArgument::REQUIRED, 'The criteria name.']
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
