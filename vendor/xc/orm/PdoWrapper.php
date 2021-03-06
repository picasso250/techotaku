<?php

namespace xc\orm;

use \Pdo;
use \Exception;

/**
 * PDO 封装
 * @author ryan
 */
class PdoWrapper
{
    protected static $instance;
    protected static $config = array(
            'driver_options' => array(),
            'debug' => false,
            'logging' => false,
    );
    protected static $sqls = array();

    /**
     * 配置
     * 
     * 支持的传参方式
     * key, value
     * 或
     * array(key=>value,...)
     */
    public static function config()
    {
        $num = func_num_args();
        if ($num == 1) {
            foreach (func_get_arg(0) as $key => $value) {
                self::$config[$key] = $value;
            }
        } elseif ($num == 2) {
            self::$config[func_get_arg(0)] = func_get_arg(1);
        }
    }

    public static function getDb()
    {
        if (!self::$instance) {
            if (!isset(self::$config['username'])) {
                self::$instance = new Pdo(self::$config['dsn']);
            } else {
                self::$instance = new Pdo(self::$config['dsn'], self::$config['username'], self::$config['password'], self::$config['driver_options']);
            }
        }
        return self::$instance;
    }

    public static function setDb($db)
    {
        self::$instance = $db;
    }

    /**
     * 插入
     * 
     * 对应 sql 中的 insert 语句
     */
    public static function insert($table, $data) 
    {
        $keys = array_keys($data);
        $keystr = implode(', ', array_map(function ($k) {return "`$k`";}, $keys));
        $values = implode(', ', array_map(function ($k) {return ":$k";}, $keys));
        $sql = "INSERT INTO `$table` ($keystr) VALUES ($values)";
        $statement = self::execute($sql, $data);
        return $statement->rowCount();
    }

    /**
     * 更新
     * 
     * 对应sql中的update语句
     * @example update($set, $where)
     */
    public static function update($table, $data, $whereStr = '', $whereVals = array())
    {
        $set = array();
        $vals = array();
        foreach ($data as $key => $value) {
            if (is_int($key)) {
                $set[] = $value;
            } else {
                $set[] = "`$key` = ?";
                $vals[] = $value;
            }
        }
        $set_str = implode(', ', $set);

        $sql = "UPDATE $table SET $set_str";
        if ($whereStr) {
            $sql .= " WHERE $whereStr";
        }
        
        $values = array_merge($vals, $whereVals);

        $statement = self::execute($sql, $values);
        return $statement->rowCount();
    }

    /**
     * 相当于 sql 中的 DELETE
     * 
     * @param string $id
     */
    public static function delete($table, $whereStr = '', $whereVals = array()) {

        $sql = "DELETE FROM `$table`";
        if ($whereStr) {
            $sql .= " WHERE $whereStr";
        }

        $statement = self::execute($sql, $whereVals);
        return $statement->rowCount();
    }

    public static function fetchRow($sql, $args = array(), $fetchType = Pdo::FETCH_ASSOC)
    {
        $statement = self::execute($sql, $args);
        return $statement->fetch($fetchType);
    }

    public static function fetchAll($sql, $args = array(), $fetchType = Pdo::FETCH_ASSOC)
    {
        $statement = self::execute($sql, $args);
        return $statement->fetchAll($fetchType);
    }

    public static function lastInsertId()
    {
        return self::getDb()->lastInsertId();
    }

    /**
     * 执行一条Sql语句
     */
    public static function execute($sql, $args = array()) {
        if (self::$config['logging']) {
            self::logSql($sql, $args);
        }

        $db = self::getDb();
        $statement = $db->prepare($sql);

        self::bindValues($statement, $args);
        $rs = $statement->execute();
        if (!$rs) {
            if (self::$config['debug']) {
                var_dump($statement);
                var_dump($statement->errorInfo());
            }
            throw new Exception("db error: " . $statement->errorCode(), 1);
        }

        return $statement;
    }

    protected static function logSql($sql, $args = array())
    {
        if ($args) {
            if (is_int(key($args))) {
                $sql = self::formatQuestionMarkSql($sql, $args);
            } else {
                $sql = self::formatNameMarkSql($sql, $args);
            }
        }
        self::$sqls[] = $sql;
    }
    
    private static function formatQuestionMarkSql($sql, $args)
    {
        $sql = str_replace('?', "'%s'", $sql);
        array_unshift($args, $sql);
        return call_user_func_array('sprintf', $args);
    }
    
    private static function formatNameMarkSql($sql, $args)
    {
        foreach ($args as $key => $value) {
            $sql = preg_replace('/:'.$key.'\b/', "'$value'", $sql);
        }
        return $sql;
    }

    /**
     * get last sql executed
     */
    public static function lastSql()
    {
        return self::$sqls[count(self::$sqls) - 1];
    }

    /**
     * get logs array
     */
    public static function sqlLog()
    {
        return self::$sqls;
    }

    /**
     * 绑定参数
     */
    protected static function bindValues($statement, $args) {
        foreach ($args as $key => $value) {
            if (is_int($key)) {
                $statement->bindValue($key + 1, $value); // for ?
            } else {
                $statement->bindValue($key, $value); // for :param
            }
        }
    }
}
