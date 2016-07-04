<?php

namespace Kitano\Repository\Criteria;

use Kitano\Repository\Contracts\RepositoryInterface as Repo;

abstract class Criteria
{

    /**
     * @param $model
     * @param Repo $repository
     * @return mixed
     */
    abstract public function apply($model, Repo $repository);
}
