<?php



if (!defined('YAF'))
    exit(-1);

class IndexController extends BaseController {

    public function init() {
        parent::init();

    }

    public function indexAction() {
        $this->showPage();
    }

    public function showHeader() {
        echo '重载过的头部';
        $this->tpl->set('exp', 'exp text');
        $this->showTpl('index/index.phtml');
    }

}
