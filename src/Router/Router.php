<?php 

declare(strict_type=1);

namespace Simple\Router;

use Simple\Router\RouterInterface;

/**
 * summary
 */
class Router implements RouterInterface
{
    
    /**
    * returns an array of routes from routing table
    */
	protected array $routes = [];

	/**
	* returns array of route params
	*/
	protected array $params = [];

	/**
	* Adds suffix unto the controller name
	* @var string 
	*/
	protected string $controllerSuffix = "controller";

	/**
	* 
	*/
	public function add(string $route, array $params = []): void
	{
		$this->routes[$route] = $params;
	}

	/**
	* Dispatch routing url
	* @param string $url
	* @return void
	*/
	public function dispatch(string $url): void
	{
		if ($this->match($url)) {
			$controllerString = $this->routes['controller'];
			$controllerString = $this->transformUpperCamelCase($controllerString);
			$controllerString = $this->getNamespace($controllerString);

			if (class_exists($controllerString)) {
				$controllerObject = new $controllerString();

				$action = $this->params['action'];

				$action = $this->transformCamelCase($action);

				if (is_callable($controllerObject, $action)) {
					$controllerObject->$action();
				} else {
					throw new Exception();
				}
			} else {
				throw new Exception();
			}
		} else {
			throw new Exception();
		}
	}

	/**
	*
	*/
	public function transformUpperCamelCase(string $string): string
	{
		return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
	}

	/**
	*
	*/
	public function transformCamelCase(string $string): string
	{
		return lcfirst($this->transformUpperCamelCase($string));
	}

	/**
	* check if incoming url is match to routing table
	* @param string $url
	* @return bool
	*/
	private function match(string $url): bool
	{
		foreach ($this->routes as $route => $params) {
			if (preg_match($route, $url, $match)) {
				foreach ($match as $key => $param) {
					if (is_string($key)) {
						$params[$key] = $param;
					}
				}

				$this->params = $params;
				return true;
			}
		}

		return false;
	}

	/**
	* Get namespace for the controller class 
	* @param string $string
	* @return 
	*/
	public function getNamespace(string $string): string
	{
		$namespace = 'App\Controller\\';

		if (array_key_exists('namespace', $this->params)) {
			$namespace .= $this->params['namespace'] . '\\';
		}

		return $namespace;
	}
}

?>