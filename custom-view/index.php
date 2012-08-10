<?php
/* A sample for using custom view (smarty) in Yaf */

define ("APPLICATION_PATH", dirname(__FILE__));

$application = new Yaf_Application(APPLICATION_PATH . "/conf/application.ini");

$response = $application
    ->bootstrap() /* init custom view in bootstrap */
	->run();

?>
