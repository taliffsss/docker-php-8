<?php

declare(strict_types=1);

namespace Simple\Orm\Entity;

use Simple\Exception\BaseInvalidArgumentException;
use Simple\Exception\BaseUnexpectedValueException;
use Simple\Orm\DataMapper\Mapper;
use Simple\Orm\QueryBuilder\QueryBuilder;
use EntityModelInterface;
use \Throwable;

class EntityModel implements EntityModelInterface
{

    /** @var Mapper */
    protected Mapper $Mapper;

    /** @var QueryBuilder */
    protected QueryBuilder $queryBuilder;

    /** @var string */
    protected string $tableSchema;

    /** @var string */
    protected string $tableSchemaID;

    /** @var array */
    protected array $options;

    /**
     * Main constructor
     *
     * @param Mapper $Mapper
     * @param QueryBuilder $queryBuilder
     * @param string $tableSchema
     * @param string $tableSchemaID
     */
    public function __construct(Mapper $Mapper, QueryBuilder $queryBuilder, string $tableSchema, string $tableSchemaID, ?array $options = [])
    {
        $this->Mapper = $Mapper;
        $this->queryBuilder = $queryBuilder;
        $this->tableSchema = $tableSchema;
        $this->tableSchemaID = $tableSchemaID;
        $this->options = $options;
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getSchema() : string
    {
        return (string)$this->tableSchema;
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getSchemaID() : string
    {
        return (string)$this->tableSchemaID;
    }

    /**
     * @inheritdoc
     *
     * @return integer
     */
    public function lastID() : int
    {
        return $this->Mapper->getLastId();
    }

    /**
     * @inheritdoc
     *
     * @param array $fields
     * @return boolean
     */
    public function create(array $fields = []) : bool
    {
        try {
            $args = ['table' => $this->getSchema(), 'type' => 'insert', 'fields' => $fields];
            $query = $this->queryBuilder->buildQuery($args)->insertQuery();
            $this->Mapper->persist($query, $this->Mapper->buildQueryParameters($fields));
            if ($this->Mapper->numRows() ==1) {
                return true;
            }
        } catch(Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * @inheritdoc
     *
     * @param array $selectors
     * @param array $conditions
     * @param array $parameters
     * @param array $optional
     * @return array
     */
    public function read(array $selectors = [], array $conditions = [], array $parameters = [], array $optional = []) : array
    {
        $args = ['table' => $this->getSchema(), 'type' => 'select', 'selectors' => $selectors, 'conditions' => $conditions, 'params' => $parameters, 'extras' => $optional];
        $query = $this->queryBuilder->buildQuery($args)->selectQuery();
        $this->Mapper->persist($query, $this->Mapper->buildQueryParameters($conditions, $parameters));
        if ($this->Mapper->numRows() >= 0) {
            return $this->Mapper->results();
        }
    }

    /**
     * @inheritdoc
     *
     * @param array $fields
     * @param string $primaryKey
     * @return boolean
     */
    public function update(array $fields = [], string $primaryKey) : bool
    {
        try {
            $args = ['table' => $this->getSchema(), 'type' => 'update', 'fields' => $fields, 'primary_key' => $primaryKey];
            $query = $this->queryBuilder->buildQuery($args)->updateQuery();
            $this->Mapper->persist($query, $this->Mapper->buildQueryParameters($fields));
            if ($this->Mapper->numRows() == 1) {
                return true;
            }
        }catch(Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * @inheritdoc
     *
     * @param array $conditions
     * @return boolean
     */
    public function delete(array $conditions = []) : bool
    {
        try {
            $args = ['table' => $this->getSchema(), 'type' => 'delete', 'conditions' => $conditions];
            $query = $this->queryBuilder->buildQuery($args)->deleteQuery();
            $this->Mapper->persist($query, $this->Mapper->buildQueryParameters($conditions));
            if ($this->Mapper->numRows() == 1) {
                return true;
            }
        }catch(Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * @inheritdoc
     *
     * @param array $selectors
     * @param array $conditions
     * @return array
     */
    public function search(array $selectors = [], array $conditions = []) : array
    {
        try {
            $args = ['table' => $this->getSchema(), 'type' => 'search', 'selectors' => $selectors, 'conditions' => $conditions];
            $query = $this->queryBuilder->buildQuery($args)->searchQuery();
            $this->Mapper->persist($query, $this->Mapper->buildQueryParameters($conditions));
            if ($this->Mapper->numRows() >= 0) {
                return $this->Mapper->results();
            }
        }catch(Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * @inheritDoc
     *
     * @param array $selectors
     * @param array $conditions
     * @return Object|null
     */
    public function get(array $selectors = [], array $conditions = []) : ?Object
    {
        $args = ['table' => $this->getSchema(), 'type' => 'select', 'selectors' => $selectors, 'conditions' => $conditions];
        $query = $this->queryBuilder->buildQuery($args)->selectQuery();
        $this->Mapper->persist($query, $this->Mapper->buildQueryParameters($conditions));
        if ($this->Mapper->numRows() >= 0) {
            return $this->Mapper->result();
        }
    }

    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function aggregate(string $type, ?string $field = 'id', array $conditions = [])
    {
        $args = ['table' => $this->getSchema(), 'primary_key'=>$this->getSchemaID(), 
        'type' => 'select', 'aggregate' => $type, 'aggregate_field' => $field, 
        'conditions' => $conditions];

        $query = $this->queryBuilder->buildQuery($args)->selectQuery();
        $this->Mapper->persist($query, $this->Mapper->buildQueryParameters($conditions));
        if ($this->Mapper->numRows() > 0)
            return $this->Mapper->column();
    }

    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function countRecords(array $conditions = [], ?string $field = 'id') : int
    {
        if ($this->getSchemaID() !='') {
            return empty($conditions) ? $this->aggregate('count', $this->getSchemaID()) : $this->aggregate('count', $this->getSchemaID(), $conditions);
        }
    }

    /**
     * @inheritDoc
     *
     * @param string $sqlQuery
     * @param array|null $conditions
     * @param string $resultType
     * @return void
     */
    public function rawQuery(string $sqlQuery, ?array $conditions = [], string $resultType = 'column')
    {
        /*$args = ['table' => $this->getSchema(), 'type' => 'raw', 'conditions' => $conditions, 'raw' => $sqlQuery];
        $query = $this->queryBuilder->buildQuery($args)->rawQuery();
        $this->Mapper->persist($query, $this->Mapper->buildQueryParameters($conditions));
        if ($this->Mapper->numRows()) {
            if (!in_array($resultType, ['fetch', 'fetch_all', 'column'])) {
                throw new BaseInvalidArgumentException('Invalid 3rd argument. Your options are "fetch, fetch_all or column"');
            }
            switch ($resultType) {
                case 'column' :
                    //$data = $this->Mapper->column(); not implemented yet!
                    break;
                case 'fetch' :
                    $data = $this->Mapper->result();
                    break;
                case 'fetch_all' :
                    $data = $this->Mapper->results();
                    break;
                default :
                    throw new BaseUnexpectedValueException('Please choose a return type for this method ie. "fetch, fetch_all or column."');
                    break;
            }
            if ($data) {
                return $data;
            }
        }*/

    }


}