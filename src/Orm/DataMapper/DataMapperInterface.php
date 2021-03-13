<?php
	
declare(strict_type=1);

namespace Simple\Orm\DataMapper;

/**
 * summary
 */
interface DataMapperInterface
{
    /**
     * Prepares a statement for execution and returns a statement object
     * 
     * @param string $sqlQuery
     * @return self
     */
    public function prepare(string $sqlQuery): self;

    /**
     * Explicit dat type for the parameter usinmg the PDO::PARAM_* constants.
     * 
     * @param mixed $value
     * @return mixed
     */
    public function bind($value);

    /**
     * Binds a parameter to the specified variable name
     * 
     * @param array $fields
     * @param bool $isSearch
     * @return mixed
     */
    public function bindParameters(array $fields, bool $isSearch = false): self;

    /**
     * returns the number of rows affected by a DELETE, INSERT, or UPDATE statement.
     * 
     * @return int|null
     */
    public function numRows(): int;

    /**
     * Execute function which will execute the prepared statement
     * 
     * @return bool
     */
    public function execute(): bool;

    /**
     * Returns a single database row as an object
     * 
     * @return Object
     */
    public function result(): Object;

    /**
     * Returns all the rows within the database as an array
     * 
     * @return array
     */
    public function results(): array;

    /**
     * Returns a database column
     * 
     * @return mixed
     */
    public function column();

    /**
     * Returns the last inserted row ID from database table
     * 
     * @return int
     * @throws Throwable
     */
    public function getLastId(): int;

    /**
     * Dumps the information contained by a prepared statement directly on the output
     * It will provide the SQL query in use, the number of parameters used (Params)
     * @return void
     */
    public function dump();

    /**
     * Executes an SQL statement, returning a result set as a PDOStatement object
     *
     * @param string $rawSql
     * @return mixed
     */
    public function rawQuery(string $rawSql);

    /**
     * Initiates a transaction
     * While autocommit mode is turned off, 
     * changes made to the database via the PDO object instance are not committed until you end the transaction
     *
     * @return bool
     */
    public function startTransaction(): bool;

    /**
     * Commits a transaction
     * While autocommit mode is turned off, 
     * changes made to the database via the PDO object instance are not committed until you end the transaction
     *
     * @return bool
     */
    public function endTransaction(): bool;

    /**
     * Rolls back a transaction
     *
     * @return bool
     */
    public function cancelTransaction(): bool;

    /**
     * Return an array of available PDO drivers
     *
     * @return array
     */
    public function getDatabaseDrivers(): array;
}


?>