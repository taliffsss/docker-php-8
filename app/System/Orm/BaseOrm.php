<?php

declare(strict_types=1);

namespace Simple\Orm;

use Simple\DatabaseConnection\DatabaseConnection;
use Simple\Orm\DataMapper\MapperCredentialConfiguration;
use Simple\Orm\DataMapper\MapperFactory;
use Simple\Orm\EntityManager\EntityManagerFactory;
use Simple\Orm\QueryBuilder\QueryBuilderFactory;
use Simple\Orm\QueryBuilder\QueryBuilder;
use Simple\Orm\EntityManager\EntityModel;

class BaseOrm
{
    /** @var string */
    protected string $tableSchema;

    /** @var string */
    protected string $tableSchemaID;

    /** @var array */
    protected array $options;

    /** @var MapperCredentialConfiguration */
    protected MapperCredentialConfiguration $environmentConfiguration;

    /**
     * Main class constructor
     *
     * @param MapperCredentialConfiguration $environmentConfiguration
     * @param string $tableSchema
     * @param string $tableSchemaID
     * @param array|null $options
     */
    public function __construct(MapperCredentialConfiguration $environmentConfiguration, string $tableSchema, string $tableSchemaID, ?array $options = [])
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
        $MapperFactory = new MapperFactory();
        $dataMapper = $MapperFactory->create(DatabaseConnection::class, $this->environmentConfiguration);
        if ($dataMapper) {
            $queryBuilderFactory = new QueryBuilderFactory();
            $queryBuilder = $queryBuilderFactory->create(QueryBuilder::class);
            if ($queryBuilder) {
                $entityManagerFactory = new EntityManagerFactory($dataMapper, $queryBuilder);
                return $entityManagerFactory->create(EntityModel::class, $this->tableSchema, $this->tableSchemaID, $this->options);
            }
        }
    }

}