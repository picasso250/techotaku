<?php

namespace model;

use xc\orm\Model;

/**
 * 帖子
 *
 * @author  ryan <cumt.xiaochi@gmail.com>
 * @created Jul 17, 2012 3:15:17 PM
 */
class NewsModel extends Model {

    public $table = 'news';

    public function add ($args) {

        $t = $this->create();
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

