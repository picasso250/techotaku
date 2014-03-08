<?php

namespace model;

use xc\orm\Model;
use model\UserModel;

/**
 * 帖子
 *
 * @author  ryan <cumt.xiaochi@gmail.com>
 */
class NewsModel extends Model {

    public $table = 'news';

    public function add ($args) {
        $userModel = new UserModel;
        $user = $userModel->findOrCreate(trim($args['username']));

        $t = $this->create();
        $t->url = trim($args['url']);
        $t->title = trim($args['url']);
        $t->user = $user->id;
        $t->created = $this->now();
        $t->save();

        return $t;
    }

    public function getListForIndex($n = 10, $p = 1) {
        $ret = $this
            ->limit($n)
            ->offset(($p-1)*$n)
            ->orderBy(array('id' => 'DESC'))
            ->findMany();
        return $ret;
    }

    public function upvote($id)
    {
        self::execute("update $this->table set point=point+1 where id=?", array($id));
        return $this->findOne($id)->point;
    }

}

