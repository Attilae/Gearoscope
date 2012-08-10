<?php

class SidebarController extends Zend_Controller_Action {

    public function init() {
        //$this->view->headLink()->appendStylesheet('/public/skins/mobilers/css/jqueryui.css');
        //$this->view->headScript()->appendFile('/public/js/jquery-1.6.2.min.js');
        //$this->view->headScript()->appendFile('/public/js/jquery-ui.js');
    }
    
    public function collectAction() {
    	
    }
    
	public function newsAction() {
    	
    }

    public function loginAction() {

        $userForm = new Form_User();
        $userForm->setDecorators(array(array('ViewScript', array('viewScript' => 'sidebar/loginform.phtml'))));
        $userForm->removeElement('first_name');
        $userForm->removeElement('last_name');
        $userForm->removeElement('role');
        $userForm->removeElement('username_reg');
        $userForm->removeElement('password_reg');
        $userForm->removeElement('password_reg_confirm');

        $this->view->form = $userForm;
    }

    public function registerAction() {

        $userForm = new Form_User();
        $userForm->setDecorators(array(array('ViewScript', array('viewScript' => 'sidebar/registerform.phtml'))));
        $userForm->removeElement('password');
        $userForm->removeElement('role');

        $this->view->form = $userForm;
    }

    public function tabAction() {

        $posts = new Model_DbTable_Posts();
        $result = $posts->getFirstThree();

        $this->view->firstthree = $result;

        $comments = new Model_DbTable_Comments();
        $result = $comments->getFirstThree();

        $this->view->firstthreecomment = $result;

        $modelArchive = new Model_Archive();
        $archives = $modelArchive->find();

        $this->view->archives = $archives;

        $modelTag = new Model_DbTable_Tags();
        $tags = $modelTag->findForCloud();        

        $this->view->cloud = $tags;
    }

}

