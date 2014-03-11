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
        $user = $this->where('name', $username)->findOne();
        if ($user) {
            return $user;
        }

        $user = $this->create();
        $user->name = $username;
        $user->created = $this->now();
        $user->save();
        return $user;
    }

    public function add($args)
    {
        $username = $args['username'];
        if (empty($username)) {
            throw new \Exception("用户名不能为空", 1);
        }
        $password = $args['password'];
        if (empty($password)) {
            throw new \Exception("密码不能为空", 1);
        }
        $u = $this->where('name', $username)->findOne();
        if ($u) {
            throw new \Exception("$username 已经存在，请换一个用户名", 1);
        }
        $u = $this->create();
        $u->name = $args['username'];
        $u->password = sha1($password);
        $u->created = $this->now();
        $u->save();
        return $u;
    }

    public function auth($username, $password)
    {
        $u = $this->where('name', $username)->findOne();
        if (sha1($password) == $u->password) {
            $_SESSION['user'] = $u->id;
            return true;
        } else {
            return false;
        }
    }

    public function out()
    {
        $_SESSION['user'] = 0;
    }

    public function getCurrentUser()
    {
        $id = $this->getCurrentUserId();
        if ($id) {
            return $this->findOne($id);
        }
        return null;
    }
    public function getCurrentUserId()
    {
        if (isset($_SESSION['user']) && $_SESSION['user']) {
            return ($_SESSION['user']);
        }
        return null;
    }

}

