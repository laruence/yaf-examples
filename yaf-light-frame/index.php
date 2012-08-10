<?php
define("APP_PATH",  dirname(__FILE__));
define("YAF",1);
define('STARTTIME', microtime(true));

/**
 * yaf默认只支持application/library 目录下类的加载
 * 并且不允许Yaf打头的第三方类出现
 * 这里注册一个__autoload顺序加载application 下所有文件夹下的类
 * Yaf 默认认为每个类都应该有下划线每个下划线代表一层目录
 *
 * @author zxcvdavid@gmail.com
 */


if (file_exists(APP_PATH . '/conf/auto.php')) {
    require_once APP_PATH . '/conf/auto.php';
    spl_autoload_register('autoload');
}

/* 对用户传入的变量进行转义操作。*/
if (!get_magic_quotes_gpc())
{
    if (!empty($_GET))
    {
        $_GET  = addslashes_deep($_GET);
    }
    if (!empty($_POST))
    {
        $_POST = addslashes_deep($_POST);
    }

    $_COOKIE   = addslashes_deep($_COOKIE);
    $_REQUEST  = addslashes_deep($_REQUEST);
}


function addslashes_deep($value)
{
    if (empty($value))
    {
        return $value;
    }
    else
    {
        return is_array($value) ? array_map('addslashes_deep', $value) : addslashes($value);
    }
}



try{
    $app  = new Yaf\Application(APP_PATH . "/conf/application.ini");

    switch ( $app->environ() ){
        case 'testing':
        default :
            error_reporting(E_ALL);
            ini_set('display_errors', 'On');
            break;

        case 'production':
            error_reporting(0);
            ini_set('display_errors', 'Off');
            break;
    }
    $app->bootstrap()->run();

    echo '<!--' . round((microtime(true) - STARTTIME) * 1000) . 'ms-->';

} catch (Exception $e){
    file_put_contents('log/yaf.log',"[" . date("Y-m-d H:i:s", time()) . "]". $e->getCode() . '--' . $e->getMessage() . "\r\n" , FILE_APPEND);
    header('location:/');
}

