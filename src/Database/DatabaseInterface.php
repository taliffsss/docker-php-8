<?php 

declare(strict_type=1);

namespace Simple\Database;

interface DatabaseInterface
{
	/**
	* Create new Connetion
	* @return pdo
	*/
	public function open(): PDO

	/**
	* Close new Connetion
	* @return pdo
	*/
	public function close(): void
}
?>