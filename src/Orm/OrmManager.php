<?php

declare(strict_types=1);

namespace Simple\Orm;

use Simple\DatabaseConnection\DatabaseConnection;
use Simple\Orm\DataMapper\DataMapperEnvironmentConfiguration;
use Simple\Orm\DataMapper\DataMapperFactory;
use Simple\Orm\EntityManager\EntityManagerFactory;
use Simple\Orm\QueryBuilder\QueryBuilderFactory;
use Simple\Orm\QueryBuilder\QueryBuilder;
use Simple\Orm\EntityManager\Entity;

class OrmManager
{
    /** @var string */
    protected string $tableSchema;

    /** @var string */
    protected string $tableSchemaID;

    /** @var array */
    protected array $options;

    /** @var DataMapperEnvironmentConfiguration */
    protected DataMapperEnvironmentConfiguration $environmentConfiguration;

    /**
     * Main class constructor
     *
     * @param DataMapperEnvironmentConfiguration $environmentConfiguration
     * @param string $tableSchema
     * @param string $tableSchemaID
     * @param array|null $options
     */
    public function __construct(DataMapperEnvironmentConfiguration $environmentConfiguration, string $tableSchema, string $tableSchemaID, ?array $options = [])
    {
        $this->environmentConfiguration = $environmentConfiguration;
        $this->tableSchema = $tableSchema;
        $this->tableSchemaID = $tableSchemaID;
        $this->options = $options;
    }

    /**
     * initialize method which glues all the components together and inject the necessary dependency within the 
     * respective object
     *
     * @return Object
     */
    public function initialize() : Object
    {
        $dataMapperFactory = new DataMapperFactory();
        $dataMapper = $dataMapperFactory->create(DatabaseConnection::class, $this->environmentConfiguration);
        if ($dataMapper) {
            $queryBuilderFactory = new QueryBuilderFactory();
            $queryBuilder = $queryBuilderFactory->create(QueryBuilder::class);
            if ($queryBuilder) {
                $entityManagerFactory = new EntityManagerFactory($dataMapper, $queryBuilder);
                return $entityManagerFactory->create(Entity::class, $this->tableSchema, $this->tableSchemaID, $this->options);
            }
        }
    }

}