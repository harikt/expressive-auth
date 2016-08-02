<?php
namespace Hkt\ExpressiveAuth\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use Aura\Auth\Exception\UsernameMissing;
use Aura\Auth\Exception\PasswordMissing;
use Aura\Auth\Exception\UsernameNotFound;
use Aura\Auth\Exception\MultipleMatches;
use Aura\Auth\Exception\PasswordIncorrect;
use Aura\Auth\Exception\ConnectionFailed;
use Hkt\ExpressiveAuth\Service\UserService;
use Exception;

class LoginAction
{
    protected $userService;

    protected $router;

    protected $template;

    protected $redirectTo;

    public function __construct(
        UserService $userService,
        RouterInterface $router,
        TemplateRendererInterface $template,
        $redirectTo = 'hkt/expressive-auth:home'
    ) {
        $this->userService = $userService;
        $this->router = $router;
        $this->template = $template;
        $this->redirectTo = $redirectTo;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $data = [];

        if ($this->userService->isValid()) {
            return $response->withHeader('Location', $this->router->generateUri($this->redirectTo));
        }

        if ($request->getMethod() == 'POST') {
            try {
                $post = $request->getParsedBody();
                $this->userService->login([
                    'username' => $post['username'],
                    'password' => $post['password'],
                ]);

                return $response->withHeader('Location', $this->router->generateUri($this->redirectTo));
                // redirect
            } catch (UsernameMissing $e) {
                $data['error'] =  'Username missing';
            } catch (PasswordMissing $e) {
                $data['error'] =  'Password missing';
            } catch (UsernameNotFound $e) {
                $data['error'] =  'Username not found';
            } catch (MultipleMatches $e) {
                $data['error'] =  'Multiple matches found';
            } catch (PasswordIncorrect $e) {
                $data['error'] =  'Password is incorrect';
            } catch (ConnectionFailed $e) {
                $data['error'] = 'Connection failure';
            } catch (Exception $e) {
                $data['error'] = 'Some error occured';
            }
        }        

        return new HtmlResponse($this->template->render('hkt/expressive-auth::login', $data));
    }
}
