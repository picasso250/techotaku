<?php

namespace controller;

/**
 * @file    LoginController
 * @author  ryan <cumt.xiaochi@gmail.com>
 */
class LoginController extends BaseController
{

    public function indexAction()
    {
        $this->renderView('welcome');
    }

}
