<?php

namespace controller;

use model\NewsModel;

/**
 * @file    init
 * @author  ryan <cumt.xiaochi@gmail.com>
 * @created Jun 30, 2012 11:50:49 AM
 */
class BaseController extends \xc\Controller
{
    protected $newsModel;

    public function __construct()
    {
        $this->newsModel = new NewsModel;

        $this->page = new \stdClass;
        $this->page->description = 'todo';
        $this->page->keywords = array('todo','扮演');

        $this->layout('layout/master');
    }

}

