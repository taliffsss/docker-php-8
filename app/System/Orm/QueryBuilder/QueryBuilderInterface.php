<?php

namespace Simple\Orm\QueryBuilder;

interface QueryBuilderInterface
{

    /**
     * Use for selecting data
     *
     * @param string $select
     * @return string
     */
    public function select(string $select): string;

    /**
     * Insert data in database table
     *
     * @param string $table
     * @param array $data
     * @return bool
     */
    public function insert(string $table, array $data): bool;

    /**
     * where to get the data table name
     *
     * @param string $table
     * @return string
     */
    public function from(string $table): string;

    /**
     * execute the query
     *
     * @param string $table
     * @return bool
     */
    public function get(string $table = null): bool;

    /**
     * Join tables
     *
     * @param array $join
     * @return string
     */
    public function join(array $join): string;

    /**
     * SQL condition query
     *
     * @param array $where
     * @return string
     */
    public function where(array $where): string;

    /**
     * Insert or Update
     * Insert if primary key is not present
     * update if primary key is present
     *
     * @param array $save
     * @return bool
     */
    public function save(array $save): bool;

    /**
     * Getting the final output
     * single data must fetch
     *
     * @return object
     */
    public function row(): object;

    /**
     * Getting the final output
     * all data must fetch
     *
     * @return object
     */
    public function results(): object;

    /**
     * Getting the final output
     * single data must fetch
     *
     * @return array
     */
    public function rowArray(): array;

    /**
     * Getting the final output
     * all data must fetch
     *
     * @return array
     */
    public function resultsArray(): array;

    /**
     * Getting the column count
     *
     * @return int
     */
    public function numRows(): int;

    /**
     * get the last insert id
     *
     * @return int
     */
    public function lastInsertId(): int;

    /**
     * Initiates a transaction
     *
     * @return bool
     */
    public function beginTransaction(): bool;

    /**
     * Commits a transaction, returning the database connection 
     * to autocommit mode until the next call beginTransaction
     *
     * @return bool
     */
    public function commit(): bool;

    /**
     * Rolls back the current transaction
     *
     * @return bool
     */
    public function rollBack(): bool;

    /**
     * Dumps the information contained by a prepared statement directly on the output
     */
    public function dumpParams();
}
?>