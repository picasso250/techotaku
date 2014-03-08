<?php

namespace controller;

/**
 * @file    UpvoteController
 * @author  ryan <cumt.xiaochi@gmail.com>
 */
class UpvoteController extends BaseController
{

    public function indexAction()
    {
    	$id = ($this->request['id']);
    	$point = $this->newsModel->upvote($id);
    	$this->renderJson(0, $point);
    }
}
