<?php

namespace xc;

/**
 * 控制器基类
 * get set 可以设置懒加载的服务
 * @author ryan
 */
class Controller
{
    public $view_root;
    public $config;
    public $app;
    public $request;

    private $vars = array();
    private $lazies = array('names' => array(), 'values' => array());

    private $scripts = array();
    private $styles = array();
    private $inner = array();
    
    public function __construct($app) {
        $this->view_root = $app->view_root;
        $this->config = $app->config;
        $this->app = $app;;
    }

    /**
     * 获取类属性
     * @param string $key
     * @return mixed 如无，返回null
     */
    public function __get($key)
    {
        if (array_key_exists($key, $this->vars)) {
            return $this->vars[$key];
        } elseif (array_key_exists($key, $this->lazies['names'])) {
            if (!array_key_exists($key, $this->lazies['values'])) {
                $this->lazies['values'][$key] = $this->lazies['names'][$key]();
            }
            return $this->lazies['values'][$key];
        }
        return null;
    }

    /**
     * 给类属性赋值
     * @param string $key
     * @param mixed $value 如果是一个函数，则为懒加载服务
     */
    public function __set($key, $value)
    {
        if (is_callable($value)) {
            return $this->lazies['names'][$key] = $value;
        } else {
            return $this->vars[$key] = $value;
        }
    }

    /**
     * get $_GET or $_POST parameter
     * @return [type] [description]
     */
    protected function param()
    {
        $num_args = func_num_args();
        if ($num_args == 1) {
            $args = func_get_arg(0);
            if (is_array($args)) {
                $names = $args;
                return $this->paramMulti($names);
            } elseif (is_string($args)) {
                $name = $args;
                return $this->_param($name, null);
            }
        } elseif ($num_args == 2) {
            $name = func_get_arg(0);
            $default = func_get_arg(1);
            return $this->_param($name, $default);
        } else {
            return $_REQUEST;
        }
    }
    
    protected function paramMulti($names)
    {
        $ret = array();
        foreach ($names as $a => $b) {
            if (is_int($a)) {
                $name = $b;
                $default = null;
            } else {
                $name = $a;
                $default = $b;
            }
            $ret[$name] = $this->_param($name, $default);
        }
        return $ret;
    }

    public function paramFile($name)
    {
        if (isset($_FILES[$name])) {
            return $_FILES[$name];
        }
        return null;
    }

    private function _param($key, $default = null)
    {
        return isset($_REQUEST[$key]) ? $_REQUEST[$key] : $default;
    }

    public function renderJson($code = 0)
    {
        $ret['code'] = $code;
        if (func_num_args() == 2) {
            $ret[$code == 0 ? 'data' : 'error'] = func_get_arg(1);
        }
        echo json_encode($ret);
    }

    /**
     * 渲染视图
     * @param string $tpl 模版路径
     */
    public function renderView($tpl)
    {
        $fname = "$this->view_root/$tpl.phtml";
        $mdfname = "$this->view_root/$tpl.md";
        if (file_exists($mdfname)) {
            $this->_render($mdfname);
        } else {
            $this->_render($fname);
        }
    }
    public function _render($fname)
    {
        $f = fopen($fname, 'r');
        $currentLine = fgets($f);
        if (preg_match('/^<!-- extends: (\w+) -->$/', $currentLine, $matches)) {
            fclose($f);
            $this->inner[] = $fname;
            $layout = $matches[1];
            $this->_render("$this->view_root/layout/$layout.phtml");
        } else {
            fclose($f);
            $this->yieldView($fname);
        }
    }

    public function yieldView($fname = null)
    {
        if ($fname === null) {
            $fname = array_pop($this->inner);
        }
        $pathinfo = pathinfo($fname);
        if (isset($pathinfo['extension']) && $pathinfo['extension'] == 'md') {
            $content = file_get_contents($fname);
            $content = preg_replace('/(\r?\n|^)([^<].+?)\r?\n/s', '<p>$2</p>'."\n", $content);
            $content = preg_replace('/\*\*(.+?)\*\*/s', '<strong>$1</strong>'."\n", $content);
            echo "$content\n";
        } else {
            include $fname;
        }
    }

    /**
     * 渲染html块
     * @param string $tpl 模版路径
     * @param array $data 内部变量
     */
    public function renderBlock($tpl, $data = array())
    {
        extract($this->vars); // you do not change things in view
        extract($data);
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
        $f = "$this->view_root/$tpl.phtml";
        if (!file_exists($f)) {
            throw new \Exception("No $tpl($f)", 1);
        }
        include $f;
    }

    /**
     * 添加js
     * @param string $js
     */
    public function addScript($js)
    {
        $this->scripts[] = $js;
    }

    /**
     * 添加css
     * @param string $css
     */
    public function addStyle($css)
    {
        $this->styles[] = $css;
    }

    public function redirect($url)
    {
        header('Location: '.$url);
        exit;
    }

    /**
     * 获取客户端 IP
     * @return string
     */
    public function ip()
    {
        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unkown';
    }
    
}
