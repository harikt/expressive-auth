<?php
namespace Hkt\ExpressiveAuth\Service;

use Aura\Auth\Auth;
use Aura\Auth\Service\LoginService;
use Aura\Auth\Service\LogoutService;
use Aura\Auth\Service\ResumeService;

class UserService
{
    protected $auth;

    protected $loginService;

    protected $logoutService;

    protected $resumeService;

    public function __construct(
        Auth $auth,
        LoginService $loginService,
        LogoutService $logoutService,
        ResumeService $resumeService
    ) {
        $this->auth = $auth;
        $this->loginService = $loginService;
        $this->logoutService = $logoutService;
        $this->resumeService = $resumeService;

        $this->resume();
    }

    public function login($input)
    {
        $this->loginService->login($this->auth, $input);
    }

    public function logout()
    {
        $this->logoutService->logout($this->auth);
    }

    public function getStatus()
    {
        return $this->auth->getStatus();
    }

    public function isValid()
    {
        return $this->auth->isValid();
    }

    /**
     *
     * Magic call to all Aura\Auth\Auth methods
     *
     */
    public function __call($method, array $params)
    {
        return call_user_func_array(array($this->auth, $method), $params);
    }

    protected function resume()
    {
        $this->resumeService->resume($this->auth);
    }
}
