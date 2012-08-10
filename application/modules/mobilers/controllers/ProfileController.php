<?php

class Mobilers_ProfileController extends Zend_Controller_Action {

    public function init() {

    }

    public function indexAction() {

        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('layout');

        $id = $this->_request->getParam('user');
        $tab = $this->_request->getParam('tab');

        $this->view->id = $id;

        $modelMobilers = new Model_DbTable_User();
        $mobiler = $modelMobilers->getMobiler($id);

        $this->view->mobiler = $mobiler;

        if ($tab == "comments") {
            $modelComment = new Model_DbTable_Comments();
            $comments = $modelComment->getCommentsByMobiler($id);

            $page = $this->_getParam('page', 1);
            $paginator = Zend_Paginator::factory($comments);
            $paginator->setItemCountPerPage(7);
            $paginator->setCurrentPageNumber($page);
            $this->view->comments = $paginator;
        } else {
            $modelPost = new Model_DbTable_Posts();
            $posts = $modelPost->getPostsByMobiler($id);

            $page = $this->_getParam('page', 1);
            $paginator = Zend_Paginator::factory($posts);
            $paginator->setItemCountPerPage(7);
            $paginator->setCurrentPageNumber($page);
            $this->view->posts = $paginator;
        }
    }

    public function addAction() {
        $form = new Mobilers_Form_Mobilers();
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($_POST)) {
                // create a new page item
                $data = $this->_request->getPost();
                $username = $form->getValue('username');
                $password = $form->getValue('password');
                $first_name = $form->getValue('first_name');
                $last_name = $form->getValue('last_name');
                $email = $form->getValue('email');
                $bio = $form->getValue('bio');
                $job = $form->getValue('job');
                $birth_year = $form->getValue('birth_year');

                $role = 'mobiler';

                if ($form->photo->isUploaded()) {
                    $form->photo->receive();
                    $photo_url = basename($form->photo->getFileName());
                }
                $model = new Model_DbTable_User();
                $user = $model->createMobiler($username, $password, $first_name, $last_name, $role, $email, $bio, $job, $birth_year, $photo_url);
                $this->_redirect('/mobilers/profile');
            }
        }
        $form->setAction('/mobilers/profile/add');
        $this->view->form = $form;
    }

    public function editAction() {

        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('layout');

        $auth = Zend_Auth::getInstance();
        $identity = $auth->getIdentity();

        $id = $identity->user_id;

        $model = new Model_DbTable_User();
        $item = $model->getMobiler($id);

        $form = new Mobilers_Form_MobilerEdit($item);
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($_POST)) {
                $data = $this->_request->getPost();
                $username = $form->getValue('username');
                $password = $form->getValue('password');
                $first_name = $form->getValue('first_name');
                $last_name = $form->getValue('last_name');
                $email = $form->getValue('email');
                $bio = $form->getValue('bio');
                $job = $form->getValue('job');
                $birth_year = $form->getValue('birth_year');

                if ($form->photo->isUploaded()) {
                    $form->photo->receive();
                    $photo = basename($form->photo->getFileName());
                    $model->updatePhoto($id, $photo);
                }

                $model = new Model_DbTable_User();
                $user = $model->updateMobiler($id, $email, $bio, $job, $birth_year);
                $this->_redirect('/mobilers/profile/index/user/' . $id . '/tab/posts');
            }
        }
        $this->view->form = $form;
    }

    public function newpasswordAction() {

        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('layout');

        $form = new Form_User();
        $form->setDecorators(array(array('ViewScript', array('viewScript' => 'profile/newpasswordform.phtml'))));
        $form->removeElement('password');
        $form->removeElement('role');
        $form->removeElement('username');
        $form->removeElement('username_reg');
        $form->removeElement('captcha');
        $form->removeElement('email');

        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($_POST)) {

                $auth = Zend_Auth::getInstance();
                $identity = $auth->getIdentity();

                $id = $identity->user_id;

                $pass = $form->getValue('password_reg');

                $userModel = new Model_DbTable_User();
                $userModel->updatePassword(
                        $id,
                        $pass
                );

                $bodyText = "<body>";
                $bodyText .= "<p>Kedves Felhasználó!</p>";
                $bodyText .= "<p>Az új jelszavad:</p>";
                $bodyText .= "<p>" . $pass . "</p>";
                $bodyText .= "<p>Üdv,<br/>Samsung Mob!lers csapat</p>";
                $bodyText .= "</body>";

                $mail = new Zend_Mail('UTF-8');
                $mail->setBodyText($bodyText);
                $mail->setBodyHtml($bodyText);
                $mail->setHeaderEncoding(Zend_Mime::ENCODING_BASE64);
                $mail->setFrom('noreply@mobilers.samsung.hu', 'mobilers.samsung.hu');
                $mail->addTo($identity->email);
                //$mail->addBcc("attila.erdei87@gmail.com");
                $mail->setSubject('Samsung Mob!lers új jelszó');
                $mail->send();

                $this->_redirect('/mobilers/profile/edit');
            } else {
//                print_r($form->getErrorMessages());
//                print_r($form->getErrors());
            }
        }
    }

    public function listAction() {
        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('admin');

        $model = new Arajanlat_Model_DbTable_Arajanlat();
        $items = $model->findAll();

        $this->view->items = $items;
    }

}

?>
