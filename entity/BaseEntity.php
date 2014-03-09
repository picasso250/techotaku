<?php

namespace entity;

use xc\orm\Entity;
use model\CommentModel;

/**
 * 评论
 * @author  ryan <cumt.xiaochi@gmail.com>
 */
class BaseEntity extends Entity {
    /**
     * 获得帖子的评论
     * @return array
     */
    public function getCommentList()
    {
        $commentModel = new CommentModel;
        $list = $commentModel
            ->alias('c')
            ->where(array('c.news' => $this->id))
            ->orderBy(array('c.id' => 'asc'))
            ->findMany();
        return $list;
    }

    /** 
     * translate Y-m-d to xx之前 or 今天XX
     * 
     * 对人类更加友好的时间
     *
     * @return string 时间描述
     */
    public function readableTime() {
        $date_time_str = $this->created;
        $time = strtotime($date_time_str);
        $now = time();
        $d = $now - $time;
        if ($d < 3600) {
            return intval($d / 60) . '分钟前';
        } elseif ($d < 3600 * 24) {
            return intval($d / 3600) . '小时前';
        }
        return $date_time_str;
    }
}

