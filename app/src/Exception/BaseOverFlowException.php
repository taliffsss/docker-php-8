<?php

declare(strict_types=1);

namespace Simple\Exception;

use OverflowException;

class BaseOverflowException  extends OverflowException  
{ 

    /**
     * Exception thrown when adding an element to a full container.
     *
     * @param string $message
     * @param integer $code
     * @param OverflowException   $previous
     * @throws RuntimeException
     */
    public function __construct(string $message, int $code = 0, OverflowException   $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}