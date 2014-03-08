<?php

spl_autoload_register(function ($name) {
    $map = array('xc' => __DIR__.'/vendor/xc');
    foreach ($map as $key => $value) {
        if (strpos($name, $key.'\\') === 0) {
            $regex = '/^'.$key.'\b/';
            $f = preg_replace($regex, "$value", $name).'.php';
            $f = str_replace('\\', '/', $f);
            if (!file_exists($f)) {
                Func::log("class $name not found($f)", Func::LOG_LEVEL_NOTICE);
            } else {
                require $f;
            }
            return;
        }
    }
    $f = __DIR__.'/'.str_replace('\\', '/', $name).'.php';
    if (!file_exists($f)) {
        Func::log("class $name not found($f)", Func::LOG_LEVEL_NOTICE);
    } else {
        require $f;
    }
});
