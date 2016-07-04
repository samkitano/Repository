<?php

namespace Kitano\Tests\Repositories;

use Kitano\Repository\Contracts\RepositoryInterface as Repository;
use Kitano\Repository\Contracts\CriteriaInterface as Criteria;
use \PHPUnit_Framework_TestCase as TestCase;
use Illuminate\Database\Eloquent\Model;
use \Mockery as m;

class RepositoryTest extends TestCase
{
    protected $mock;

    protected $repository;

    public function setUp()
    {
        $this->mock = m::mock('Illuminate\Database\Eloquent\Model');
    }

    public function testRepository()
    {
        $this->assertTrue(true);
    }
}
