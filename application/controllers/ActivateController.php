<?php

class ActivateController extends Zend_Controller_Action {

	public function init() {
        $this->view->addHelperPath(
                'ZendX/JQuery/View/Helper'
                , 'ZendX_JQuery_View_Helper');
    }
	
	public function indexAction() {

		parent::init();
		$layout = Zend_Layout::getMvcInstance();
		$layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
		$layout->setLayout('layout');

		$locale = Zend_Registry::get('Zend_Locale');

		$id = (int) $this->_getParam('user');

		$code = $this->_getParam('code');

		$modelUser = new Model_DbTable_User();
		$user = $modelUser->getUser($id);

		if ($user["code"] == $code) {
			$modelUser->activateUser($id);
			$this->_redirect($locale->getLanguage() . '/activate/successfull');
		} else {
			throw new Exception("Varátlan hiba történt!");
		}
	}

	public function successfullAction() {
		parent::init();
		$layout = Zend_Layout::getMvcInstance();
		$layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
		$layout->setLayout('layout');
	}

}

