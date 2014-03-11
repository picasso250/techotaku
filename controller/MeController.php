<?php

namespace controller;

/**
 * @file    MeController
 * @author  ryan <cumt.xiaochi@gmail.com>
 */
class MeController extends BaseController
{

    public function indexAction()
    {
        $this->renderView('user/me');
    }
}
