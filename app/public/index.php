<?php 
	
	require_once __DIR__ . DIRECTORY_SEPARATOR .'../vendor/autoload.php';

	define('ENVIRONMENT', isset($_SERVER['NATIVE_PHP']) ? $_SERVER['NATIVE_PHP'] : 'development');

	define('BASEPATH', __DIR__ . DIRECTORY_SEPARATOR .'../');

	switch (ENVIRONMENT) {
		case 'test':
		case 'development':
			error_reporting(-1);
			ini_set('display_errors', 1);
			ini_set("error_log", "logs/".date('Y-m-d').'.log');
		break;
		case 'production':
			ini_set('display_errors', 0);
			ini_set("error_log", "logs".Constant::ERROR_LOG.date('Y-m-d').'.log');
			error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
		break;
		default:
			header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
			echo 'The application environment is not set correctly.';
			exit(1);
	}

	try {

		$repository = Dotenv\Repository\RepositoryBuilder::createWithNoAdapters()
			    ->addAdapter(Dotenv\Repository\Adapter\EnvConstAdapter::class)
			    ->addWriter(Dotenv\Repository\Adapter\PutenvAdapter::class)
			    ->immutable()
			    ->make();

		$dotenv = Dotenv\Dotenv::create($repository, BASEPATH);
		$dotenv->load();

	} catch (Exception $e) {
		
		echo $e->getMessage();

	}
?>