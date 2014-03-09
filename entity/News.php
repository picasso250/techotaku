<?php

namespace entity;

use xc\orm\Entity;

/**
 * 帖子
 *
 * @file    Twit
 * @author  ryan <cumt.xiaochi@gmail.com>
 * @created Jul 17, 2012 3:15:17 PM
 */
class News extends Entity {

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
    public function getComments() {
        $commentDao = new CommentDao;
        return $commentDao
            ->where('twit_id', $this->id())
            ->orderBy(array('id' => 'ASC'))
            ->findMany();
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

