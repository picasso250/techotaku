<?php

namespace controller;

/**
 * @file    CommentController
 * @author  ryan <cumt.xiaochi@gmail.com>
 */
class CommentController extends BaseController
{

    public function indexAction()
    {
        $this->comments = $this->commentModel->getList();
        $this->renderView('comment/list');
    }
    public function addAction()
    {
        $id = $this->request['id'];
        $this->commentModel->add(array(
            'pid' => $id,
            'content' => $this->request['content'],
        ));
        $this->renderJson();
    }
    public function replyAction()
    {
        $id = $this->request['id'];
        $this->c = $this->commentModel->findOne($id);
        $this->renderView('news/reply');
    }
}
