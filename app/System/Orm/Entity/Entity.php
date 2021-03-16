<?php

declare(strict_types=1);

namespace Simple\Orm\Entity;

use EntityModelInterface;

class Entity implements EntityModelInterface
{

    /**
     * @var EntityModelInterface
     */
    protected EntityModelInterface $model;

    /**
     * Main constructor clas
     * 
     * @return void
     */
    public function __construct(EntityModelInterface $model)
    {
        $this->model = $model;
    }

    /**
     * @inheritDoc
     */
    public function getmodel() : Object
    {
        return $this->model;
    }

}