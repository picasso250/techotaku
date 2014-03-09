<?php

namespace controller;

use xc\Paginate;

/**
 * @file    LoginController
 * @author  ryan <cumt.xiaochi@gmail.com>
 */
class LoginController extends BaseController
{

    public function indexAction()
    {
        $this->renderView('login');
    }

    public function addAction()
    {
        $u = $this->userModel->add($this->request);
        $this->renderJson(0, $u->toArray());
    }

    public function authAction()
    {
        $rs = $this->userModel->auth($this->request['username'], $this->request['password']);
        $this->renderJson($rs ? 0 : 1);
    }

    public function outAction()
    {
        $this->userModel->out();
        $this->redirect('/');
    }
}
