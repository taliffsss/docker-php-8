<?php 
	echo "<pre>";
	var_dump(opcache_get_status()['jit']); 
	echo "</pre>";
	phpinfo(); 
?>