<?php

namespace Kitano\Repository\Console\Commands\Creators;

use Doctrine\Common\Inflector\Inflector;
//use Illuminate\Support\Facades\Config;
use Illuminate\Filesystem\Filesystem;

class RepositoryCreator
{
    /**  @var Filesystem */
    protected $files;

    /** @var */
    protected $repository;

    /**  @var */
    protected $model;

    /**
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        $this->files = $files;
    }

    /**
     * @return mixed
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param mixed $repository
     */
    public function setRepository($repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param mixed $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * Create the repository.
     *
     * @param $repository
     * @param $model
     *
     * @return int
     */
    public function create($repository, $model)
    {
        $this->setRepository($repository);
        $this->setModel($model);
        $this->createDirectory();

        return $this->createClass();
    }

    protected function createDirectory()
    {
        $directory = $this->getDirectory();

        if (!$this->files->isDirectory($directory)) {
            $this->files->makeDirectory($directory, 0755, true);
        }
    }

    /**
     * Get the repository directory.
     *
     * @return mixed
     */
    protected function getDirectory()
    {
        return config('repositories.repository_path');
    }

    /**
     * Get the repository name.
     *
     * @return mixed|string
     */
    protected function getRepositoryName()
    {
        $name = $this->getRepository();

        if (! strpos($name, 'Repository') !== false) {
            $name .= 'Repository';
        }

        return $name;
    }

    /**
     * Get the model name.
     *
     * @return string
     */
    protected function getModelName()
    {
        $model = $this->getModel();

        if (isset($model) && !empty($model)) {
            $name = $model;
        } else {
            $name = Inflector::singularize($this->stripRepositoryName());
        }

        return $name;
    }

    /**
     * Get the stripped repository name.
     *
     * @return string
     */
    protected function stripRepositoryName()
    {
        return ucfirst(strtolower(str_replace("repository", "", $this->getRepository())));
    }

    /**
     * Get the populate data.
     *
     * @return array
     */
    protected function getPopulateData()
    {
        return [
            'repository_namespace' => config('repositories.repository_namespace'),
            'repository_class'     => $this->getRepositoryName(),
            'model_path'           => config('repositories.model_namespace'),
            'model_name'           => $this->getModelName()
        ];
    }

    /**
     * Get the path.
     *
     * @return string
     */
    protected function getPath()
    {
        return $this->getDirectory() . DIRECTORY_SEPARATOR . $this->getRepositoryName() . '.php';
    }

    /**
     * Get the stub.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->files->get($this->getStubPath() . "repository.stub");
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
        $data = $this->getPopulateData();
        $stub = $this->getStub();

        foreach ($data as $key => $value) {
            $stub = str_replace($key, $value, $stub);
        }

        return $stub;
    }

    protected function createClass()
    {
        return $this->files->put($this->getPath(), $this->populateStub());
    }
}
