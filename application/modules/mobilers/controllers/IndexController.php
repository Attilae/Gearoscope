<?php

class Mobilers_IndexController extends Zend_Controller_Action {

    public function init() {
        $this->view->addHelperPath("ZendX/JQuery/View/Helper", "ZendX_JQuery_View_Helper");
    }

    public function indexAction() {

        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('layout');

        $modelMobilers = new Model_DbTable_User();
        $mobilers = $modelMobilers->getMobilers();

        $modelPost = new Model_DbTable_Posts();

        $modelComment = new Model_DbTable_Comments();

        $i = 0;
        foreach ($mobilers as $mobiler):

            $countPost = $modelPost->countMobilerPosts($mobiler["user_id"]);
            $countComments = $modelComment->countMobilerComments($mobiler["user_id"]);

            $mobilers[$i]["countComments"] = $countComments[0]["COUNT(comments.comment_id)"];
            $mobilers[$i]["countPosts"] = $countPost[0]["COUNT(posts.post_id)"];

            $i++;
        endforeach;

//        $page = $this->_getParam('page', 1);
//        $paginator = Zend_Paginator::factory($mobilers);
//        $paginator->setItemCountPerPage(6);
//        $paginator->setCurrentPageNumber($page);

        $this->view->mobilers = $mobilers;
    }

    public function passwordAction() {
        $passwordForm = new Form_User();
        $passwordForm->setAction('/user/password');
        $passwordForm->removeElement('first_name');
        $passwordForm->removeElement('last_name');
        $passwordForm->removeElement('username');
        $passwordForm->removeElement('role');
        $userModel = new Model_User();
        if ($this->_request->isPost()) {
            if ($passwordForm->isValid($_POST)) {
                $userModel->updatePassword(
                        $passwordForm->getValue('id'),
                        $passwordForm->getValue('password')
                );
                return $this->_forward('list');
            }
        } else {
            $id = $this->_request->getParam('id');
            $currentUser = $userModel->find($id)->current();
            $passwordForm->populate($currentUser->toArray());
        }
        $this->view->form = $passwordForm;
    }

    public function loginAction() {
        $userForm = new Form_User();
        $userForm->setAction('/user/login');
        $userForm->removeElement('first_name');
        $userForm->removeElement('last_name');
        $userForm->removeElement('role');
        if ($this->_request->isPost() && $userForm->isValid($_POST)) {
            $data = $userForm->getValues();
            //set up the auth adapter
            // get the default db adapter
            $db = Zend_Db_Table::getDefaultAdapter();
            //create the auth adapter
            $authAdapter = new Zend_Auth_Adapter_DbTable($db, 'users',
                            'username', 'password');
            //set the username and password
            $authAdapter->setIdentity($data['username']);
            $authAdapter->setCredential(md5($data['password']));
            //authenticate
            $result = $authAdapter->authenticate();
            if ($result->isValid()) {
                // store the username, first and last names of the user
                $auth = Zend_Auth::getInstance();
                $storage = $auth->getStorage();
                $storage->write($authAdapter->getResultRowObject(
                                array('username', 'first_name', 'last_name', 'role')));
                return $this->_forward('index');
            } else {
                $this->view->loginMessage = "Sorry, your username or
                password was incorrect";
            }
        }
        $this->view->form = $userForm;
    }

    public function logoutAction() {
        $authAdapter = Zend_Auth::getInstance();
        $authAdapter->clearIdentity();
    }

    public function sidebarAction() {

        $modelMobilers = new Model_DbTable_User();
        $mobilers = $modelMobilers->getSixMobilers();

        $this->view->mobilers = $mobilers;
    }

    public function footerAction() {

        $modelMobilers = new Model_DbTable_User();
        $mobilers = $modelMobilers->getMobilers();

        $this->view->mobilers = $mobilers;
    }

}

