<?php

class AltalanosfeltetelekController extends Zend_Controller_Action {

    public function indexAction() {
                parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('layout');
    }

}

