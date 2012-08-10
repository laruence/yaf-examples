<?php
namespace Db;


if( !defined('YAF') )
    exit(-1);

/**
 * Db工厂数据类
 *
 * @author  zxcvdavid@gmail.com
 *
 */


class Factory {

    static public function create($which = 'master') {

        $db_config = \Yaf\Registry::get('config')->db->$which;

        $class_name = '\Db\\' . ucfirst(strtolower( $db_config->type ) );

        $db = $class_name::getInstance( $db_config );

        return ($db instanceof DbInterface) ? $db : false;

        }

}