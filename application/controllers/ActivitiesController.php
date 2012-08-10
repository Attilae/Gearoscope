<?php

class ActivitiesController extends Zend_Controller_Action {

    public function sidebarAction() {

        $title = $this->_request->getParam('title');

        $posts = new Model_DbTable_Posts();

        $result = $posts->getPostsByTopic($title);

        $page = $this->_getParam('page', 1);
        $paginator = Zend_Paginator::factory($result);
        $paginator->setItemCountPerPage(7);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;
    }

    public function addAction() {
        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('admin');

        $form = new Form_Activity();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $title = $form->getValue('title');
                $link = $form->getValue('link');

                if ($form->photo->isUploaded()) {
                    $form->photo->receive();
                    $photo = basename($form->photo->getFileName());
                }

                $active = "0";

                $model = new Model_DbTable_Activity();
                $id = $model->saveActivity($title, $active, $photo, $link);

                $this->_redirect('/activities/list?create=1');
            }
        }
        $this->view->form = $form;
    }

    public function editAction() {

        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('admin');

        $id = $this->_request->getParam("id");

        $model = new Model_DbTable_Activity();
        $activity = $model->getActivity($id);

        $form = new Form_Activity();
        $form->getElement('title')->setValue($activity[0]["title"]);
        $form->getElement('link')->setValue($activity[0]["link"]);

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $title = $form->getValue('title');
                $link = $form->getValue('link');

                if ($form->photo->isUploaded()) {
                    $form->photo->receive();
                    $photo_url = basename($form->photo->getFileName());
                    $model->updatePhoto($id, $photo_url);
                }

                $active = "0";

                $model = new Model_DbTable_Activity();
                $id = $model->editActivity($id, $title, $link);

                $this->_redirect('/activities/list?update=1');
            }
        }
        $this->view->form = $form;        
    }

    public function listAction() {

        ini_set("display_errors", "1");

        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('admin');

        $model = new Model_DbTable_Activity();
        $activities = $model->getAll();

        $this->view->activities = $activities;
    }

    public function activeAction() {
        $id = $this->_request->getParam("id");

        $modelUser = new Model_DbTable_Posts();
        $modelUser->setActive($id);

        $this->_redirect("/posts/adminlist?active=1");
    }

    public function deactiveAction() {
        $id = $this->_request->getParam("id");

        $modelUser = new Model_DbTable_Posts();
        $modelUser->setDeactive($id);

        $this->_redirect("/posts/adminlist?deactive=1");
    }

    public function deleteAction() {
        $id = $this->_request->getParam("id");

        $model = new Model_DbTable_Activity();
        $model->deleteActivity($id);

        $this->_redirect("/activity/list?delete=1");
    }

}
