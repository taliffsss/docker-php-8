<?php

declare(strict_types=1);

namespace Simple\Orm\Entity;

use Simple\Exception\BaseUnexpectedValueException;
use Simple\Orm\QueryBuilder\QueryBuilderInterface;
use Simple\Orm\DataMapper\MapperInterface;
use EntityInterface;

class EntityFactory
{

    /** @var MapperInterface */
    protected MapperInterface $dataMapper;

    /** @var QueryBuilderInterface */
    protected QueryBuilderInterface $queryBuilder;

    /**
     * Main class constructor
     *
     * @param MapperInterface $dataMapper
     * @param QueryBuilderInterface $queryBuilder
     */
    public function __construct(MapperInterface $dataMapper, QueryBuilderInterface $queryBuilder)
    {
        $this->dataMapper = $dataMapper;
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * Create the entityManager obejct and inject the dependency which is the crud object
     *
     * @param string $crudString
     * @param string $tableSchema
     * @param string $tableSchemaID
     * @param array $options
     * @return EntityInterface
     * @throws BaseUnexpectedValueException
     */
    public function create(string $crudString, string $tableSchema, string $tableSchemaID, array $options = []) : EntityInterface
    {
        $crudObject = new $crudString($this->dataMapper, $this->queryBuilder, $tableSchema, $tableSchemaID, $options);
        if (!$crudObject instanceof CrudInterface) {
            throw new BaseUnexpectedValueException($crudString . ' is not a valid crud object.');
        }
        return new EntityManager($crudObject);
    }

}