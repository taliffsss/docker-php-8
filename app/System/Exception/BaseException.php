<?php

declare(strict_types=1);

namespace Simple\Exception;

class BaseException extends Exception
{ 

    /**
     * Main class constructor. Which allow overriding of SPL exceptions to add custom
     * exact message within core framework.
     *
     * @param string $message
     * @param integer $code
     * @param Throwable $previous
     */
    private $previous;
   
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code);
       
        if (!is_null($previous))
        {
            $this ->previous = $previous;
        }
    }
   
    public function getPrevious()
    {
        return $this ->previous;
    }

}