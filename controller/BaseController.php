<?php

namespace controller;

use model\NewsModel;
use model\UserModel;
use model\CommentModel;

/**
 * @file    init
 * @author  ryan <cumt.xiaochi@gmail.com>
 * @created Jun 30, 2012 11:50:49 AM
 */
class BaseController extends \xc\Controller
{
    protected $newsModel;
    protected $userModel;
    protected $commentModel;

    public function __construct()
    {
        $this->newsModel = new NewsModel;
        $this->userModel = new UserModel;
        $this->commentModel = new CommentModel;

        $this->currentUser = $this->userModel->getCurrentUser();
        $this->currentUserId = $this->currentUser ? $this->currentUser->id : 0;

        $this->page = new \stdClass;
        $this->page->description = 'todo';
        $this->page->keywords = array('todo','扮演');
    }

}

