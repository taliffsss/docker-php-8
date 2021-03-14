<?php 

namespace Simple\Database;
use \PDO;

interface DatabaseInterface
{
	/**
	 * Create new Connetion
	  * @return pdo
	 */
	public function open(): PDO;

	/**
	 * Close Connetion
	 * @return pdo
	 */
	public function close(): void;
}
?>