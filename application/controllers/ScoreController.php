<?php

class ScoreController extends Zend_Controller_Action {
    
    public function init() {
        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('registration');
    }

    public function indexAction() {
         $model = new Model_DbTable_Score();
         $scores = $model->findOneToTen();
         $this->view->scores110 = $scores;
         $scores = $model->findTenToTwenty();
         $this->view->scores1020 = $scores;
    }


}

