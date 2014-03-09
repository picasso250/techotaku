<?php

namespace entity;

use xc\orm\Entity;

/**
 * 评论
 * @author  ryan <cumt.xiaochi@gmail.com>
 */
class Comment extends BaseEntity {

    public function topic() {
        $pid = $this->pid;
        while ($pid) {
            $topic = $this->model->findOne($pid);
            $pid = $topic->pid;
        }
        return $topic;
    }
    
    /**
     * 获得角色（撰写评论的）
     * @return Role
     */
    public function getRole()
    {
        $roleDao = new RoleDao;
        return $roleDao->findOne($this->role_id);
    }
}

