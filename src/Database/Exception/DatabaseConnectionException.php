<?php

declare(strict_type=1);

namespace Simple\Database\Exception;

use PDOException;

/**
 * summary
 */
class DatabaseConnectionException extends PDOException
{

	/**
	 *
	 */
	protected $message;

	/**
	 *
	 */
	protected $code;

    /**
    * Initialize constructor
    * @return void
    */
    public function __construct($message = null, $code = null)
    {
        $this->message = $message;
        $this->code = $code;
    }
}

?>