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

    public $table = 'comment';

    public function add($args) {
        $c = $this->create();
        $c->setMulti($args);
        $userModel = new UserModel;
        $c->user = $userModel->getCurrentUserId();
        $c->created = $this->now();
        $c->save();
    }

}

