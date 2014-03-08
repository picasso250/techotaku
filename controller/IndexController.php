<?php

namespace controller;

use xc\Paginate;

/**
 * @file    index
 * @author  ryan <cumt.xiaochi@gmail.com>
 * @created Jun 27, 2012 6:24:01 PM
 */
class IndexController extends BaseController
{

    public function indexAction()
    {
        $this->news = $this->newsModel->getListForIndex();
        $this->renderView('index/index');
    }
}
