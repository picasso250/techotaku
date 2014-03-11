<?php

namespace controller;

/**
 * @file    UserController
 * @author  ryan <cumt.xiaochi@gmail.com>
 */
class UserController extends BaseController
{

    public function viewAction()
    {
        $name = $this->param('name');
        $this->renderView('user/view');
    }
}
