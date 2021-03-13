<?php

declare(strict_type=1);

namespace Simple\Router;

interface RouterInterface
{

	/**
	* Add route to routing class
	* @param string $route
	* @param array $params
	* @return void
	*/
	public function add(string $route, array $params): void

	/**
	* Dispatch route and create controller objects and execute the default method
	* on that controller object
	*/
	public function dispatch(string $url): void

}

?>