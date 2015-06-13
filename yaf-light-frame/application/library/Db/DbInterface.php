<?php

namespace Db;

if (!defined('YAF'))
    exit(-1);



/*
 * Db接口定义
 *
 * @author zxcvdavid@gmail.com
 *
 */

interface DbInterface {

    static public function getInstance(); //要求所有数据连接皆为单例

    function execute($query); //执行sql语句

    function transaction($query); //事务

    function getOne($query); //执行sql语句，只得到一条记录

    function getRow($query); //从结果集中取得一行作为关联数组

    function getCol($query); //从结果集中取得一列作为关联数组

    function getAll($query); //返回一个N行N列的结果集

    function insert(); //返回上一次插入记录的ID;

    function close(); //关闭数据库连接
}