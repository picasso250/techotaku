<?php

namespace xc\orm;

/**
 * 实体基类
 * 
 * represents a row in a table
 * @author ryan
 */
class Entity
{
    public $model; // model object
    protected $data = array(); // data
    protected $id;

    protected $dirty = array();

    /**
     * 工厂函数，获取一个实例
     * @param IdDao $model
     * @param array $row
     * @return IdEntity
     */
    public static function make($model, array $row)
    {
        $classname = get_called_class();
        $o = new $classname;
        $o->model = $model;
        if ($row) {
            $o->data = $row;
            $o->id = $row[$model->pkey];
        }
        return $o;
    }

    public static function fromArray($arr)
    {
        $o = new self;
        $o->data = $arr;
        $o->id = $arr[$this->model->pkey];
        return $o;
    }

    public function populate($arr)
    {
        $this->data = $arr;
        if (isset($arr['id'])) {
            $this->id = $arr['id'];
        }
        return $this;
    }

    /**
     * 获取主键的值
     * @return int
     */
    public function id()
    {
        $pkey = $this->model->pkey;
        if (isset($this->data[$pkey])) {
            return $this->data[$pkey];
        }
        return null;
    }

    /**
     * 保存
     * @return \ptf\IdEntity
     */
    public function save()
    {
        if ($this->id()) {
            $this->model->updateEntity($this);
        } else {
            $this->id = $this->data[$this->model->pkey] = $this->model->insertEntity($this);
        }
        $this->clean();
        return $this;
    }

    /**
     * 获取写脏的值，键值对数组
     * @return array
     */
    public function dirtyArray()
    {
        $set = array();
        foreach ($this->dirty as $key) {
            $set[$key] = $this->data[$key];
        }
        return $set;
    }

    /**
     * 标记为干净的
     */
    public function clean()
    {
        $this->dirty = array();
        return $this;
    }

    /**
     * 从数据库删除这条记录
     * @return type
     */
    public function delete()
    {
        return $this->model->delete($this);
    }

    /**
     * 转化为数组
     * @return type
     */
    public function toArray()
    {
        return $this->data;
    }

    /**
     * 获取对应的属性
     * @param type $name
     * @return null
     */
    public function get($name)
    {
        if (isset($this->data) && isset($this->data[$name])) {
            return $this->data[$name];
        }
        return null;
    }

    /**
     * 设置对应的属性
     * @return \ptf\IdEntity
     */
    public function set()
    {
        $num_args = func_num_args();
        if ($num_args == 1) {
            $arr = func_get_arg(0);
            if (is_array($arr)) {
                $this->setMulti($arr);
            }
        } elseif ($num_args == 2) {
            $key = func_get_arg(0);
            $value = func_get_arg(1);
            $this->_set($key, $value);
        }
        return $this;
    }
    
    /**
     * 同时设置多个属性
     * 貌似大家都叫populate来着
     * @param array $arr
     * @return \ptf\IdEntity
     */
    public function setMulti(array $arr)
    {
        foreach ($arr as $key => $value) {
            $this->_set($key, $value);
        }
        return $this;
    }

    public function _set($key, $value)
    {
        $this->data[$key] = $value;
        $this->dirty[] = $key;
    }

    public function __set($key, $value)
    {
        $this->_set($key, $value);
        return $value;
    }

    public function __get($key)
    {
        return $this->get($key);
    }

    public function __isset($key)
    {
        return isset($this->data[$key]);
    }
}
