<?php

if( !defined('YAF') )
    exit(-1);

 function autoload ($class_name)
{
    $root = APP_PATH . '/application';
    $load_paths = "$root/models;$root/modules;$root/plugins;$root/library;$root/controllers";
    $paths = explode(";", $load_paths);
    if (strstr($class_name, "_")) {
        $class_name = str_ireplace("_", "/", $class_name);
    }

    if (is_array($paths)) {
        $i = 0;
        foreach ($paths as $path) {
//            var_dump( $path . "/" . $class_name . ".php" );
            if (file_exists($path . "/" . $class_name . ".php")) {
                require_once $path . "/" . $class_name . ".php";
            }
        }
    }
    if (file_exists($class_name . "php")) {
        require_once $class_name . "php";
    }
}