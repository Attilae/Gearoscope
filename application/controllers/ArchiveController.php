<?php

class ArchiveController extends Zend_Controller_Action {

    public function indexAction() {

        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('layout');

        $month = $this->_request->getParam('month');

        $modelPosts = new Model_DbTable_Posts();
        $monthPosts = $modelPosts->getPostsByMonth($month);

        $page = $this->_getParam('page', 1);
        $paginator = Zend_Paginator::factory($monthPosts);
        $paginator->setItemCountPerPage(7);
        $paginator->setCurrentPageNumber($page);
        $this->view->monthPosts = $paginator;
    }

    public function sidebarAction() {
        $modelArchive = new Model_Archive();
        $archives = $modelArchive->find();

        $this->view->archives = $archives;
    }

}

