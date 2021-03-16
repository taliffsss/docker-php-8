<?php

declare(strict_types=1);

namespace Native\Controller;

class Test
{ 

    public function getResults()
    {
        echo env('DB_DRIVER');
    }

}