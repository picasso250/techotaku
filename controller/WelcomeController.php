<?php

namespace controller;

/**
 * @file    WelcomeController
 * @author  ryan <cumt.xiaochi@gmail.com>
 */
class WelcomeController extends BaseController
{

    public function indexAction()
    {
        $this->renderView('welcome');
    }

}
