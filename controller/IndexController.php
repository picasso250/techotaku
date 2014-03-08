<?php

namespace controller;

use xc\Paginate;

/**
 * @file    index
 * @author  ryan <cumt.xiaochi@gmail.com>
 */
class IndexController extends BaseController
{

    public function indexAction()
    {
        $this->news = $this->newsModel->getListForIndex();
        $this->renderView('index/index');
    }
}
