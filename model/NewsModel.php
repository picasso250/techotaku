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

        $n = $this->create();
        $n->title = trim($args['title']);
        if (empty($n->title)) {
            throw new \Exception("empty title", 1);
        }
        $n->url = trim($args['url']);
        if (($n->url)) {
            $arr = parse_url($n->url);
            if (!isset($arr['host'])) {
                throw new \Exception("no host in url $n->url", 1);
            }
            $n->host = $arr['host'];
        }
        $n->user = $userModel->getCurrentUserId();
        $n->content = $args['detail'];
        $n->created = $this->now();
        $n->save();

        return $n;
    }

    public function getListForIndex($n = 10, $p = 1) {
        $ret = $this
            ->alias('n')
            ->limit($n)
            ->offset(($p-1)*$n)
            ->leftJoin(array('u' => 'user'), array('u.id', '=', 'n.user'), array('username' => 'name'))
            ->orderBy(array('n.id' => 'DESC'))
            ->where('pid', 0)
            ->findMany();
        return $ret;
    }

    public function upvote($id)
    {
        self::execute("update $this->table set point=point+1 where id=?", array($id));
        return $this->findOne($id)->point;
    }

}

