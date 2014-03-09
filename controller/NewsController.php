<?php

namespace controller;

/**
 * @file    NewsController
 * @author  ryan <cumt.xiaochi@gmail.com>
 */
class NewsController extends BaseController
{

    public function viewAction()
    {
    	$id = $this->request['id'];
    	$this->n = $this->newsModel->findOne($id);
        $this->renderView('news/view');
    }
}
