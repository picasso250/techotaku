<?php

namespace model;

use xc\orm\Model;
use model\UserModel;

/**
 * 帖子
 *
 * @author  ryan <cumt.xiaochi@gmail.com>
 */
class UserModel extends Model {

    public $table = 'user';

    public function findOrCreate ($username) {
        $user = $this->where('username', $username)->findOne();
        if ($user) {
            return $user;
        }

        $user = $this->create();
        $user->name = $username;
        $user->created = $this->now();
        $user->save();
        return $user;
    }
}

