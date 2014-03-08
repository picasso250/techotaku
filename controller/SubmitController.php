<?php

namespace controller;

/**
 * @file    SubmitController
 * @author  ryan <cumt.xiaochi@gmail.com>
 */
class SubmitController extends BaseController
{

    public function indexAction()
    {
        $this->renderView('submit');
    }
    public function saveAction()
    {
    	$params = $this->param();
    	$this->newsModel->add($params);
    	return $this->renderJson(0);
    }
}
