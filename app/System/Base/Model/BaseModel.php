<?php

declare(strict_types=1);

namespace Simple\Base\Model;


class BaseModel
{
    
    /** @var string */
    public string $table;

    /** @var string */
    public string $primary_key;

    /**
     * Main class constructor
     *
     * @param string $tableSchema
     * @param string $tableSchemaID
     * @return void
     * @throws BaseInvalidArgumentException
     */
    public function __construct()
    {
        
    }

    /**
     * Get the data repository object based on the context model
     * which the repository is being executed from.
     *
     * @return Repository
     */
    public function get(): Repository
    {
        return $this->repository;
    }


}