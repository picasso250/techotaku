<?php

/**
 * @author  ryan <cumt.xiaochi@gmail.com>
 * @created Jun 27, 2012 6:20:27 PM
 * config of server
 */

return array(
    'static.source.version' => array(
        'js'=>'C05',
        'css'=>'C05',
    ),
    'pdb' => array(
        'dsn' => 'mysql:'.implode(';', array('host='.SAE_MYSQL_HOST_M, 'port='.SAE_MYSQL_PORT, 'dbname='.SAE_MYSQL_DB)),
        'dsn_s' => 'mysql:'.implode(';', array('host='.SAE_MYSQL_HOST_S, 'port='.SAE_MYSQL_PORT, 'dbname='.SAE_MYSQL_DB)),
        'username' => SAE_MYSQL_USER,
        'password' => SAE_MYSQL_PASS
    ),
);
