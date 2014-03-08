<?php

namespace xc\orm;

/**
 * 数据库访问基类
 * 
 * @author ryan
 */
class Model extends Sql
{
    public $table;
    public $pkey = 'id';
    protected $proto;

    public function __construct()
    {
        parent::__construct();
        $classname = preg_replace('/\bmodel\b/', 'entity', get_called_class());
        $classname = preg_replace('/Model$/', '', $classname);
        $this->proto = new $classname;
        $this->proto->model = $this;
    }

    /**
     * 新建一个实体
     * @return IdEntity
     */
    public function create()
    {
        $proto = $this->proto;
        return clone $proto;
    }

    protected function makeEntity($row)
    {
        $proto = $this->proto;
        $entity =  clone $proto;
        $entity->populate($row);
        return $entity;
    }

    /**
     * 插入
     * @param \ptf\IdEntity $entity
     * @return type
     */
    public function insertEntity(IdEntity $entity)
    {
        PdoWrapper::insert($this->table(), $entity->toArray());
        return PdoWrapper::lastInsertId();
    }

    /**
     * 更新
     * @param \ptf\IdEntity $entity
     * @return int
     */
    public function updateEntity(IdEntity $entity)
    {
        $set = $entity->dirtyArray();
        if ($set) {
            return PdoWrapper::update($this->table, $set, "`{$this->pkey}`=?", array($entity->id()));
        }
        return 0;
    }

    /**
     * 删除
     * @param \ptf\IdEntity $entity
     * @return type
     */
    public function deleteEntity(IdEntity $entity)
    {
        return PdoWrapper::delete($this->table(), "`{$this->pkey}`=?", array($entity->id()));
    }

    /**
     * 获取现在的时间，依mysql表示方法
     * @return string
     */
    public function now()
    {
        return date('Y-m-d H:i:s', time());
    }
}
