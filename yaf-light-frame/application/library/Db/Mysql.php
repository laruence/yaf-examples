<?php

namespace Db;

if (!defined('YAF'))
    exit(-1);

/**
 * mysql操作类
 *
 * @author zxcvdavid@gmail.com
 *
 */
class Mysql implements DbInterface {

    private static $_instances;
    private $_dbh;
    private $_sth;
    private $_sql;

    private function __construct($dbhost, $username, $password, $dbname, $dbcharset) {
        try {
            $this->_dbh = new \PDO('mysql:dbname=' . $dbname . ';host=' . $dbhost, $username, $password, array(\PDO::ATTR_PERSISTENT => true));
            $this->_dbh->query('SET NAMES ' . $dbcharset);
        } catch (PDOException $e) {
            echo '<pre>';
            echo '<b>Connection failed:</b> ' . $e->getMessage();
            die();
        }
    }

    static public function getInstance($db_config = '') {

        $_db_host = $db_config->host;
        $_db_name = $db_config->dbname;
        $_db_charset = $db_config->charset;
        $_db_usr = $db_config->usr;
        $_db_pwd = $db_config->pwd;

        $idx = md5($_db_host . $_db_name);

        if (!isset(self::$_instances[$idx])) {
            self::$_instances[$idx] = new Mysql($_db_host, $_db_usr, $_db_pwd, $_db_name, $_db_charset);
        }
        return self::$_instances[$idx];
    }

    function halt($msg = '', $sql = '') {

        $error_info = $this->_sth->errorInfo();
        $s = '<pre>';
        $s .= '<b>Error:</b>' . $error_info[2] . '<br />';
        $s .= '<b>Errno:</b>' . $error_info[1] . '<br />';
        $s .= '<b>Sql:</b>' . $this->_sql;
        exit($s);
    }

    function execute($sql, $values = array()) {
        $this->_sql = $sql;
        $this->_sth = $this->_dbh->prepare($sql);
        $bool = $this->_sth->execute($values);

        if ('00000' !== $this->_sth->errorCode()) {
            $this->halt();
        }

        return $bool;
    }

    function getAll($sql, $values = array(), $fetch_style = \PDO::FETCH_ASSOC) {
        $this->execute($sql, $values);
        return $this->_sth->fetchAll($fetch_style);
    }

    function getCol($sql, $params = array(), $column_number = 0) {
        $columns = array();
        $results = array();
        $this->execute($sql, $params);
        $results = $this->_sth->fetchAll(\PDO::FETCH_NUM);
        foreach ($results as $result) {
            $columns[] = $result[$column_number];
        }
        return $columns;
    }

    function getRow($sql, $values = array(), $fetch_style = \PDO::FETCH_ASSOC) {
        $this->execute($sql, $values);
        return $this->_sth->fetch($fetch_style);
    }

    function getOne($sql, $values = array(), $column_number = 0) {
        $this->execute($sql, $values);
        return $this->_sth->fetchColumn($column_number);
    }

    function insert($table = null, $data = null, $returnStr = false) {

        $fields = array_keys($data);
        $marks = array_fill(0, count($fields), '?');

        $sql = "INSERT INTO $table (`" . implode('`,`', $fields) . "`) VALUES (" . implode(", ", $marks) . " )";
        if ($returnStr) {
            $fields = array_keys($data);
            $marks = array_values($data);

            foreach ($marks as $k => $v) {
                if (!is_numeric($v))
                    $marks[$k] = '\'' . $v . '\'';
            }
            $sql = "INSERT INTO $table (`" . implode('`,`', $fields) . "`) VALUES (" . implode(", ", $marks) . " )";
            return $sql;
        }
        $this->execute($sql, array_values($data));
        $last_insert_id = $this->_dbh->lastInsertId();
        if ($last_insert_id)
            return $last_insert_id;
        else
            return true;
    }

    /*
     * 处理事务
     */

    function transaction($sql) {
        try {
            $this->_dbh->beginTransaction();
            $this->_dbh->exec($sql);
            $this->_dbh->commit();
        } catch (PDOException $ex) {
            $this->_dbh->rollBack();
        }
    }

    function update($table, $data, $where) {

        $values = $bits = $wheres = array();
        foreach ($data as $k => $v) {
            $bits[] = "`$k` = ?";
            $values[] = $v;
        }

        foreach ($where as $c => $v) {
            $wheres[] = "$c = ?";
            $values[] = $v;
        }

        $sql = "UPDATE $table SET " . implode(', ', $bits) . ' WHERE ' . implode(' AND ', $wheres);
        return $this->execute($sql, $values);
    }

    function delete($table, $where) {
        $values = $wheres = array();
        foreach ($where as $key => $val) {
            $wheres[] = "$key = ?";
            $values[] = $val;
        }

        $sql = "DELETE FROM $table WHERE " . implode(' AND ', $wheres);
        return $this->execute($sql, $values);
    }

    function close() {
        unset($this->_instances);
        unset($this->_dbh);
    }

}