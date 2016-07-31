<?php
namespace Hkt\ExpressiveAuth\Di;

use Aura\Di\ContainerConfig;
use Aura\Di\Container;
use Zend\Expressive\Router\Route;

class AuthConfig extends ContainerConfig
{
	public function define(Container $di)
	{		
		// action

        $di->params['Hkt\ExpressiveAuth\Service\UserService'] = [
			'auth' => $di->lazyGet('aura/auth:auth'),
			'loginService' => $di->lazyGet('aura/auth:login_service'),
			'logoutService' => $di->lazyGet('aura/auth:logout_service'),
            'resumeService' => $di->lazyGet('aura/auth:resume_service'),
		];

        $di->set('Hkt\ExpressiveAuth\Service\UserService', $di->lazyNew('Hkt\ExpressiveAuth\Service\UserService'));

		$di->params['Hkt\ExpressiveAuth\Action\LoginAction'] = [
			'user' => $di->lazyGet('Hkt\ExpressiveAuth\Service\UserService'),
			'router' => $di->lazyGet('Zend\Expressive\Router\RouterInterface'),
			'template' => $di->lazyGet('Zend\Expressive\Template\TemplateRendererInterface'),
		];

		$di->params['Hkt\ExpressiveAuth\Action\LogoutAction'] = [
			'user' => $di->lazyGet('Hkt\ExpressiveAuth\Service\UserService'),
			'router' => $di->lazyGet('Zend\Expressive\Router\RouterInterface'),
		];

		$di->set('Hkt\ExpressiveAuth\Action\LoginAction', $di->lazyNew('Hkt\ExpressiveAuth\Action\LoginAction'));
		$di->set('Hkt\ExpressiveAuth\Action\LogoutAction', $di->lazyNew('Hkt\ExpressiveAuth\Action\LogoutAction'));
	}

	public function modify(Container $di)
	{
	}
}
