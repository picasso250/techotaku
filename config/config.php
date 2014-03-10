<?php
/**
 * @file    common
 * @author  ryan <cumt.xiaochi@gmail.com>
 * @created Jun 30, 2012 10:38:22 AM
 */
return array(
    // 网址=>控制器
    'routers' => array(
        // array('GET', '/about', array('Index', 'about')),
        array('GET', '/talk/[:id]', array('News' => 'view')),
    ),
    'action_control_list' => array(
        'Comment/add',
        'Submit/save',
    ),
);
