<?php

namespace controller;

/**
 * @file    page404
 * @author  ryan <cumt.xiaochi@gmail.com>
 */
class Page404Controller extends BaseController
{
    public function indexAction()
    {
        $this->renderView('page404');
    }
}
