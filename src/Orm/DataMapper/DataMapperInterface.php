<?php
	
declare(strict_type=1);

namespace Simple\Orm\DataMapper;

/**
 * summary
 */
interface DataMapperInterface
{
    /**
     * Prepare the query string
     * 
     * @param string $sqlQuery
     * @return self
     */
    public function prepare(string $sqlQuery): self;

    /**
     * 
     * 
     * 
     */
    public function bind(string $value);

    /**
     * 
     * 
     * 
     */
    public function bindParameters(array $fileds, bool $isSearch = false): self;

    /**
     * 
     * 
     * 
     */
    public function numRows(): int;

    /**
     * 
     * 
     * 
     */
    public function execute(): void;

    /**
     * 
     * 
     * 
     */
    public function result(): object;

     /**
     * 
     * 
     * 
     */
    public function results(): array;
}


?>