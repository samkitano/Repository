<?php

namespace Kitano\Repository\Contracts;

use Kitano\Repository\Criteria\Criteria;

interface CriteriaInterface
{
    /**
     * @param bool $status
     * @return $this
     */
    public function ignoreCriteria($status = true);

    /**
     * @return mixed
     */
    public function getCriteria();

    /**
     * @param Criteria $criteria
     * @return $this
     */
    public function getByCriteria(Criteria $criteria);

    /**
     * @param Criteria $criteria
     * @return $this
     */
    public function addCriteria(Criteria $criteria);

    /**
     * @return $this
     */
    public function applyCriteria();
}
