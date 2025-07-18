<?php

declare(strict_types=1);

namespace App\Api\Users;

use App\ApplicationError;
use App\Auth;
use App\FrontController\BaseController;
use App\FrontController\ControllerInterface;
use App\FrontController\LayoutInterface;
use App\FrontController\RequestDataInterface;

class AuthController extends BaseController implements ControllerInterface
{
    public function __construct(RequestDataInterface $request, AuthJsonView $view, LayoutInterface $layout)
    {
        $this->request = $request;
        $this->view = $view;
        $this->layout = $layout;

        $this->view->enabled = Auth::isEnabled();
    }

    public function isEnabled(): self
    {
        return $this;
    }

    /**
     * SignIn controller
     * @throws ApplicationError
     */
    public function signIn(): self
    {
        if (headers_sent()) {
            throw new ApplicationError("Headers already sent");
        }

        if (Auth::isEnabled()) {

            if (
                mb_strtolower($this->request->getString("login") ?? "") !== mb_strtolower(getenv("SIMPLE_SYSLOG_VIEWER_LOGIN"))
                || mb_strtolower($this->request->getString("password") ?? "") !== mb_strtolower(getenv("SIMPLE_SYSLOG_VIEWER_PASSWORD"))
            ) {
                Auth::setAuthorized(false);
                $this->view->result->addError("Login or password is not correct");
            } else {
                Auth::setAuthorized(true);
            }

            return $this;
        }

        return $this;

    }
}