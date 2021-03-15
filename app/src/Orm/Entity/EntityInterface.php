<?php

declare(strict_types=1);

namespace Simple\Orm\Entity;

interface EntityInterface
{
    /**
     * Get the crud object which will expose all the method within our crud class
     * 
     * @return Object
     */
    public function getCrud() : Object;

}