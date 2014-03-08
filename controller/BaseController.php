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

        require dirname(__DIR__).'/lib/functions.php';
        
        $this->page = new \stdClass;
        $this->page->description = '伪博扮演，扮演整个世界';
        $this->page->keywords = array('伪博','扮演');

        $this->layout('layout/master');
    }

}

