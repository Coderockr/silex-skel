<?php

namespace Skel\Test;

use Mockery as m;
use PHPUnit_Framework_TestCase;
use Doctrine\ORM\AbstractQuery;

Abstract class ApplicationUnitTestCase extends PHPUnit_Framework_TestCase
{
    protected $repositoryMock;
    protected $emMock;
    protected $qbMock;
    protected $entity = '';

    public function tearDown()
    {
        m::close();
    }

    protected function getDefaultEmMock()
    {
        if ($this->emMock) {
            return $this->emMock;
        }

        $this->qbMock = $this->buildQbMock();

        $this->repositoryMock = $this->buildRepositoryMock();

        $this->emMock = $this->buildEmMock($this->repositoryMock, $this->qbMock);

        return $this->emMock;
    }

    protected function buildQbMock()
    {
        $qbMock = m::mock('Doctrine\ORM\QueryBuilder');
        $qbMock->shouldReceive('select')->andReturn($qbMock)->byDefault();
        $qbMock->shouldReceive('addSelect')->andReturn($qbMock)->byDefault();
        $qbMock->shouldReceive('join')->andReturn($qbMock)->byDefault();
        $qbMock->shouldReceive('leftJoin')->andReturn($qbMock)->byDefault();
        $qbMock->shouldReceive('where')->andReturn($qbMock)->byDefault();
        $qbMock->shouldReceive('andWhere')->andReturn($qbMock)->byDefault();
        $qbMock->shouldReceive('groupBy')->andReturn($qbMock)->byDefault();
        $qbMock->shouldReceive('orderBy')->andReturn($qbMock)->byDefault();
        $qbMock->shouldReceive('setParameter')->andReturn($qbMock)->byDefault();
        $qbMock->shouldReceive('setParameters')->andReturn($qbMock)->byDefault();
        $qbMock->shouldReceive('getQuery')->andReturn($qbMock)->byDefault();

        $qbMock->shouldReceive('from')
            ->with(m::on(function ($entityName) use (&$entity) {
                $entity = '\\'.$entityName;

                return true;
            }))
            ->andReturnUsing(function () use (&$entity) {
                return new $entity();
            })->byDefault();

        return $qbMock;
    }

    protected function buildQbExpr()
    {
        $comparisonMock = m::mock('Doctrine\ORM\QueryExpr\Comparison');

        $qbExpr = m::mock('Doctrine\ORM\Query\Expr');
        $qbExpr->shouldReceive('neq')->andReturn($comparisonMock)->byDefault();

        return $qbExpr;
    }

    protected function buildQuery()
    {
        return  m::mock(AbstractQuery::class);
    }

    protected function buildEmMock($repositoryMock = null, $qbMock = null)
    {
        $entity = &$this->entity;

        if (!$repositoryMock) {
            $repositoryMock = $this->buildRepositoryMock();
        }

        if (!$qbMock) {
            $qbMock = $this->buildQbMock();
        }

        $emMock = m::mock('Doctrine\ORM\EntityManager');

        $emMock->shouldReceive('persist')
            ->andReturn(null)
            ->byDefault();

        $emMock->shouldReceive('flush')
            ->andReturn(null)
            ->byDefault();

        $emMock->shouldReceive('getClassMetadata')
            ->andReturn((object) array('name' => &$entity))
            ->byDefault();

        $emMock->shouldReceive('getRepository')
            ->with(m::on(function ($entityName) use (&$entity) {
                $entity = '\\'.$entityName;

                return true;
            }))
            ->andReturn($repositoryMock)
            ->byDefault();

        $emMock->shouldReceive('createQueryBuilder')
            ->andReturn($qbMock)
            ->byDefault();

        return $emMock;
    }

    protected function buildRepositoryMock()
    {
        $entity = &$this->entity;
        $repositoryMock = m::mock('Doctrine\ORM\EntityRepository');
        $repositoryMock->shouldReceive('findOneBy')
            ->andReturnUsing(function ($params) use (&$entity) {
                $object = new $entity();

                foreach ($params as $key => $value) {
                    $setMethod = 'set'.ucfirst($key);
                    $object->$setMethod($value);
                }

                return $object;
            })->byDefault();
        $repositoryMock->shouldReceive('findBy')
            ->andReturnUsing(function ($params) use (&$entity) {
                $object = new $entity();
                foreach ($params as $key => $value) {
                    $setMethod = 'set'.ucfirst($key);
                    $object->$setMethod($value);
                }

                return $object;
            })->byDefault();

        return $repositoryMock;
    }
}
