<?php 
	
	require_once __DIR__ . DIRECTORY_SEPARATOR .'../vendor/autoload.php';

	$join = [
		['sfasf','sfasfa = sfasfas', 'left']
	];

	$str = null;

	$js = ['aa' => 'val', 'cc' => 'ss'];

	foreach ($join as $key => $val) {
		if (is_array($val)) {
			$prefix = strtoupper($val[2]);
			$str .= "{$prefix} JOIN {$val[0]} ON {$val[1]} ";
		}
	}

	echo "<pre>";
	print_r($js);
	echo "</pre>";
?>