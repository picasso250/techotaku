<?php

use model\UserModel;

class Func
{

    const LOG_LEVEL_EMERGENCY = 0; // system is unusable
    const LOG_LEVEL_ALERT = 1; // action must be taken immediately
    const LOG_LEVEL_CRITICAL = 2; // critical conditions
    const LOG_LEVEL_ERROR = 3; // error conditions
    const LOG_LEVEL_WARNING = 4; // warning conditions
    const LOG_LEVEL_NOTICE = 5; // normal but significant condition
    const LOG_LEVEL_INFORMATIONAL = 6; // informational messages
    const LOG_LEVEL_DEBUG = 7; // debug-level messages

    public static function config($key, $value = null)
    {
        static $config;
        if (empty($config)) {
            $config = array_merge(
                require __DIR__.'/config/config.php',
                require __DIR__.'/config/config.'.DEPLOY_ENV.'.php'
            );
        }
        if (isset($config[$key])) {
            return $config[$key];
        }
        return null;
    }

    public static function dispatch($controller, $action, $params)
    {
        $classname = '\controller\\'. $controller.'Controller';
        try {
            $c = new $classname;
        } catch (Exception $e) {
            self::log($e->getMessage(), self::LOG_LEVEL_CRITICAL);
            $classname = "\\controller\\page404Controller";
            $c = new $classname;
        }

        $acl = self::config('action_control_list');
        $userModel = new UserModel;
        $currentUserId = $userModel->getCurrentUserId();
        if (in_array("$controller/$action", $acl) && !$currentUserId) {
            echo "javascript:location.href='/login';";
            exit;
        }
        $c->view_root = __DIR__.'/view';
        $c->request = $params;
        $c->{$action.'Action'}($params);
        exit;
    }
    public static function log($msg, $level = self::LOG_LEVEL_INFORMATIONAL, $data = null)
    {
        static $map = array(
            self::LOG_LEVEL_EMERGENCY => 'EMERGENCY',
            self::LOG_LEVEL_ALERT => 'ALERT',
            self::LOG_LEVEL_CRITICAL => 'CRITICAL',
            self::LOG_LEVEL_ERROR => 'ERROR',
            self::LOG_LEVEL_WARNING => 'WARNING',
            self::LOG_LEVEL_NOTICE => 'NOTICE',
            self::LOG_LEVEL_INFORMATIONAL => 'INFORMATIONAL',
            self::LOG_LEVEL_DEBUG => 'DEBUG',
        );
        return error_log(date('Y-m-d H:i:s')." [$map[$level]] $msg\n", 3, __DIR__.'/app.log');
    }
}
