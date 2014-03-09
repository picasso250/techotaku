<?php

namespace entity;

use xc\orm\Entity;
use model\CommentModel;

/**
 * 帖子
 *
 * @file    Twit
 * @author  ryan <cumt.xiaochi@gmail.com>
 * @created Jul 17, 2012 3:15:17 PM
 */
class News extends BaseEntity {

    /**
     * 源站
     * @return Role
     */
    public function getSite()
    {
        $arr = parse_url($this->url);
        return $arr['host'];
    }

    public function getUrl() {
        return $this->url ?: "/talk/$this->id";
    }

    /**
     * 获得帖子的评论
     * @return array
     */
    public function getCommentList()
    {
        $commentModel = new CommentModel;
        return $commentModel
            ->alias('c')
            ->join(array('n' => 'news'), array('n.id', '=', 'c.news'), array())
            ->where(array('n.id' => $this->id))
            ->orderBy(array('c.id' => 'desc'))
            ->findMany();
    }

}

