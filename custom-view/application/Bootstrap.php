<?php

class Bootstrap extends Yaf_Bootstrap_Abstract{

	public function _initLocalName() {
		Yaf_Loader::getInstance()->registerLocalNamespace(array(
			'Smarty',
		));
	}

	public function _initSmarty(Yaf_Dispatcher $dispatcher) {
        /* init smarty view engine */
		Yaf_Loader::import("Smarty/Adapter.php");
		$smarty = new Smarty_Adapter(null, Yaf_Application::app()->getConfig()->smarty);
		$dispatcher->setView($smarty);
	}
}
