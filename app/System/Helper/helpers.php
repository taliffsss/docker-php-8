<?php 


if (! function_exists('env')) {
    /**
     * Gets the value of an environment variable.
     *
     * @param  string  $key
     * @return mixed
     */
    function env($key)
    {
        return getenv($key);
    }
}

?>