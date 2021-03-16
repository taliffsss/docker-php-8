<?php

namespace Simple\Orm\QueryBuilder;

interface QueryBuilderInterface
{

    public function select(string $select): string;

    public function insert(string $table, array $data): bool;

    public function from(string $table): string;

    public function get(string $table): string;

    public function join(array $where): string;

    public function where(array $where): string;

    public function save(array $save): bool;

    public function row(): object;

    public function results(): object;

    public function rowArray(): array;

    public function resultsArray(): array;

    public function numRows(): int;

    public function lastInsertId(): int;

    public function beginTransaction(): bool;

    public function commit(): bool;

    public function rollBack(): bool;
}
?>