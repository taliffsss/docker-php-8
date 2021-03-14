<?php

declare(strict_types=1);

namespace Simple\Orm\EntityManager;

use Simple\Orm\EntityManager\EntityInterface;

class EntityManager implements EntityManagerInterface
{

    /**
     * @var EntityInterface
     */
    protected EntityInterface $crud;

    /**
     * Main constructor clas
     * 
     * @return void
     */
    public function __construct(EntityInterface $crud)
    {
        $this->crud = $crud;
    }

    /**
     * @inheritDoc
     */
    public function getCrud() : Object
    {
        return $this->crud;
    }

}