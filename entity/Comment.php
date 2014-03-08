<?php

use ptf\IdEntity;

/**
 * 评论
 * @author  ryan <cumt.xiaochi@gmail.com>
 */
class Comment extends IdEntity {
    
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

