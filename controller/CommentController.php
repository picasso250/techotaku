<?php

namespace controller;

/**
 * @file    CommentController
 * @author  ryan <cumt.xiaochi@gmail.com>
 */
class CommentController extends BaseController
{

    public function addAction()
    {
        $id = $this->request['id'];
        $this->commentModel->add(array(
            'news' => $id,
            'content' => $this->request['content'],
        ));
        $this->renderJson();
    }
    public function reply()
    {
        $id = $this->request['id'];
        $this->commentModel->add(array(
            'news' => $id,
            'content' => $this->request['content'],
        ));
        $this->renderJson();
    }
}
