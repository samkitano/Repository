<?php

namespace Kitano\Repository\Console\Commands\Creators;

use Doctrine\Common\Inflector\Inflector;
//use Illuminate\Support\Facades\Config;
use Illuminate\Filesystem\Filesystem;

class CriteriaCreator
{
    /** @var Filesystem */
    protected $files;

    /** @var */
    protected $criteria;

    /** @var */
    protected $model;

    /** @param Filesystem $files */
    public function __construct(Filesystem $files)
    {
        $this->files = $files;
    }

    /** @return mixed */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /** @param mixed $criteria */
    public function setCriteria($criteria)
    {
        $this->criteria = $criteria;
    }

    /** @return mixed */
    public function getModel()
    {
        return $this->model;
    }

    /** @param mixed $model */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * Create the criteria.
     *
     * @param $criteria
     * @param $model
     *
     * @return int
     */
    public function create($criteria, $model)
    {
        $this->setCriteria($criteria);
        $this->setModel($model);
        $this->createDirectory();

        return $this->createClass();
    }


    /**
     * Create the criteria directory.
     */
    public function createDirectory()
    {
        $directory = $this->getDirectory();

        if (! $this->files->isDirectory($directory)) {
            $this->files->makeDirectory($directory, 0755, true);
        }
    }

    /**
     * Get the criteria directory.
     *
     * @return string
     */
    public function getDirectory()
    {
        $model     = $this->getModel();
        $directory = config('repositories.criteria_path');

        if (isset($model) && !empty($model)) {
            $directory .= DIRECTORY_SEPARATOR . $this->pluralizeModel();
        }

        return $directory;
    }


    /**
     * Get the populate data.
     *
     * @return array
     */
    protected function getPopulateData()
    {
        $criteria = $this->getCriteria();
        $model    = $this->pluralizeModel();

        $criteria_namespace = config('repositories.criteria_namespace');
        $criteria_class     = $criteria;

        if (isset($model) && !empty($model)) {
            $criteria_namespace .= '\\' . $model;
        }

        return [
            'criteria_namespace' => $criteria_namespace,
            'criteria_class'     => $criteria_class
        ];
    }

    /**
     * Get the path.
     *
     * @return string
     */
    protected function getPath()
    {
        return $this->getDirectory()
               . DIRECTORY_SEPARATOR
               . $this->getCriteria()
               . '.php';
    }

    /**
     * Get the stub.
     *
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function getStub()
    {
        return $this->files->get($this->getStubPath() . "criteria.stub");
    }

    /**
     * Get the stub path.
     *
     * @return string
     */
    protected function getStubPath()
    {
        return __DIR__ . '/../../../resources/stubs/';
    }

    /**
     * Populate the stub.
     *
     * @return mixed
     */
    protected function populateStub()
    {
        $populate_data = $this->getPopulateData();
        $stub          = $this->getStub();

        foreach ($populate_data as $search => $replace) {
            $stub = str_replace($search, $replace, $stub);
        }

        return $stub;
    }

    /**
     * Create the repository class.
     *
     * @return int
     */
    protected function createClass()
    {
        return $this->files->put($this->getPath(), $this->populateStub());
    }

    /**
     * Pluralize the model.
     *
     * @return string
     */
    protected function pluralizeModel()
    {
        return ucfirst(Inflector::pluralize($this->getModel()));
    }
}
