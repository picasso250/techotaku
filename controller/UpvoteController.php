<?php

namespace controller;

/**
 * @file    UpvoteController
 * @author  ryan <cumt.xiaochi@gmail.com>
 * @created Jun 27, 2012 6:24:01 PM
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
