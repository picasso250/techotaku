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

    public function countComment()
    {
        $count = $this->model->where('pid', $this->id)->count();
        return $count;
    }

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

}

