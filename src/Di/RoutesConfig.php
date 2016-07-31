<?php
namespace Hkt\ExpressiveAuth\Di;

use Aura\Di\ContainerConfig;
use Aura\Di\Container;
use Zend\Expressive\Router\Route;

class RoutesConfig extends ContainerConfig
{
	public function define(Container $di)
	{
	}

	public function modify(Container $di)
	{
		$router = $di->get('Zend\Expressive\Router\RouterInterface');

		$router->addRoute(new Route('/login', 'Hkt\ExpressiveAuth\Action\LoginAction', ['GET', 'POST'], 'hkt/expressive-auth:login'));
		$router->addRoute(new Route('/logout', 'Hkt\ExpressiveAuth\Action\LogoutAction', ['GET'], 'hkt/expressive-auth:logout'));
	}
}
