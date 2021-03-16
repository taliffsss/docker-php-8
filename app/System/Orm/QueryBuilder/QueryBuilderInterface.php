<?php

namespace Simple\Orm\QueryBuilder;

interface QueryBuilderInterface
{
    /**
     * Prepare the query string
     * 
     * @param string $sqlQuery
     * @return self
     */
    public function rawQuery(string $sqlQuery);

    /**
     * Explicit dat type for the parameter usinmg the PDO::PARAM_* constants.
     * 
     * @param mixed $value
     * @return mixed
     */
    public function bind($value);

    /**
     * returns the number of rows affected by a DELETE, INSERT, or UPDATE statement.
     * 
     * @return int|null
     */
    public function numRows(): int;

    /**
     * Execute function which will execute the prepared statement
     * 
     * @return void
     */
    public function execute();

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
     * Transactions allows you to run multiple changes to a database
     *
     * @return bool
     */
    public function begin(): bool;

    /**
     * End a transaction and commit your changes
     *
     * @return bool
     */
    public function end(): bool;

    /**
     * Cancel a transaction and roll back your changes
     *
     * @return bool
     */
    public function cancel(): bool;

    /**
     * dumps the the information that was contained in the Prepared Statement
     *
     * @return mixed
     */
    public function dumpParams();
}
?>