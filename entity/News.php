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
     * 转发帖子
     * @param array $args 转发帖子所需的信息
     * @return string 新帖子的id
     */
    public function retweet($args) {
        $twitDao = self::dao();
        $t = $twitDao->create();
        $t->role_id = $args['role_id'];
        $t->origin_id = $this->id;
        if (isset($args['comment_id'])) {
            $t->origin_comment_id = $args['comment_id'];
        }
        $t->created = $twitDao->now();
        $t->save();

        $logDao = new LogDao;
        $log = $logDao->create();
        $log->ip = $args['ip'];
        $log->role_id = $args['role_id'];
        $log->twit_id = $t->id;
        $log->save();

        return $t->id;
    }

    /**
     * 格式化内容
     * @param string $text 原帖子内容
     * @return string html代码
     */
    public static function formatHtml($text) { // this should be private, but...
        return preg_replace("/(@[^\s]+)(\sv)?($|\s)/", '[$1$2]', $text);
    }

    /** 
     * translate Y-m-d to xx之前 or 今天XX
     * 
     * 对人类更加友好的时间
     *
     * @param string $date_time_str 形如 Y-m-d H:i:s （sql中获得的DateTime类型即可）
     * @return string 时间描述
     */
    public static function readableTime($date_time_str) {
        $date_time = new DateTime($date_time_str);
        $nowtime = new DateTime();
        $diff = $nowtime->diff($date_time);
        if ($diff->y==0 && $diff->m==0 && $diff->d==0) { // 同一天
            if ($diff->h<1) // 一个小时以内
                if ($diff->i==0) // 一分钟以内
                    return '刚刚';
                else
                    return $diff->i.'分钟前'; // minutes
            else
                return '今天';
        } else {
            return current(explode(' ', $date_time_str));
        }
    }
}

