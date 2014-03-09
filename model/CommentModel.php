<?php

namespace model;

use xc\orm\Model;
use model\UserModel;

/**
 * 评论
 *
 * @author  ryan <cumt.xiaochi@gmail.com>
 */
class CommentModel extends Model {

    public $table = 'news';

    public function add($args) {
        $c = $this->create();
        $c->setMulti($args);
        $userModel = new UserModel;
        $c->user = $userModel->getCurrentUserId();
        $c->created = $this->now();
        $c->save();
    }

    public function getList() {
        return $this
            ->where('pid != 0')
            ->orderBy(array('id' => 'desc'))
            ->findMany();
    }

}

