<?php

namespace Simple\Database\Exception;

use \PDOException;

/**
 * summary
 */
class DatabaseConnectionException extends PDOException
{

	/**
	 * @var error message
	 */
	protected $message;

	/**
	 * @var error code
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