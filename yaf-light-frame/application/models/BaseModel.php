<?php


use Db\Factory;

if (!defined('YAF'))
    exit(-1);

/**
 *
 * Module基类
 *
 * @author zxcvdavid@gmail.com
 *
 *
 */


class BaseModel{

    protected $db;

    function __construct(){
        $this->db = Factory::create();
    }
}