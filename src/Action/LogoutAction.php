<?php
namespace Hkt\ExpressiveAuth\Action;

use Zend\Expressive\Router\RouterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use Hkt\ExpressiveAuth\Service\UserService;

class LogoutAction
{
    protected $user;

    protected $router;

    protected $redirectTo;

    public function __construct(UserService $user, RouterInterface $router, $redirectTo = 'hkt/expressive-auth:login')
    {
        $this->user = $user;
        $this->router = $router;
        $this->redirectTo = $redirectTo;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $this->user->logout();

        return $response->withHeader('Location', $this->router->generateUri($this->redirectTo));
    }
}
