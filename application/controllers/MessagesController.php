<?php

class MessagesController extends Zend_Controller_Action {

    public function viewAction() {

        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('layout');

        // action body
        $thread_id = $this->_request->getParam("thread");

        $auth = Zend_Auth::getInstance();
        $identity = $auth->getIdentity();
        $sender = $identity->user_id;

        $messageModel = new Model_DbTable_Messages();
        $result = $messageModel->getMessagesByThread($thread_id, $identity->user_id);
        $this->view->message = $result;
    }

    public function viewinAction() {

        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('layout');

        // action body
        $thread_id = $this->_request->getParam("thread");

        $auth = Zend_Auth::getInstance();
        $identity = $auth->getIdentity();
        $sender = $identity->user_id;

        $messageModel = new Model_DbTable_Messages();
        $result = $messageModel->getMessagesByThreadIn($thread_id, $identity->user_id);
        $this->view->message = $result;
    }

    public function addAction() {

        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('admin');

        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_redirect('index/index');
        }

        $form = new Form_Messages();

        if ($this->getRequest()->isPost()) {

            if ($form->isValid($this->getRequest()->getPost())) {
                $subject = $form->getValue('subject');
                $receivers = $form->getValue('receiver');
                $message = $form->getValue('lead');
                $date = date("Y-m-d H:i:s", time());

                $auth = Zend_Auth::getInstance();
                $identity = $auth->getIdentity();
                $sender = $identity->user_id;

                $messageModel = new Model_DbTable_Messages();
                $thread = $messageModel->getLastThread();

                $thread_id = $thread[0]["thread_id"];
                $thread_id = $thread_id + 1;

                for ($i = 0; $i < count($receivers); $i++) {
                    $modelUser = new Model_DbTable_User();
                    $receiver = $modelUser->getUser($receivers[$i]);

                    $messageModel->saveMessage($message, $thread_id, $subject, $sender, $receivers[$i], $date);

                    $bodyText = "<body>";
                    $bodyText .= "<p>Kedves " . $receiver["username"] . "!</p>";
                    $bodyText .= "<p>Üzeneted érkezett a mobilers.samsung.hu oldalon.</p>";
                    $bodyText .= "<p>Üdv,<br/>Samsung Mob!lers csapat</p>";
                    $bodyText .= "</body>";

                    $mail = new Zend_Mail('UTF-8');
                    $mail->setBodyText($bodyText);
                    $mail->setBodyHtml($bodyText);
                    $mail->setHeaderEncoding(Zend_Mime::ENCODING_BASE64);
                    $mail->setFrom('noreply@mobilers.samsung.hu', 'mobilers.samsung.hu');
                    $mail->addTo($receiver["email"]);
                    $mail->addBcc("attila.erdei87@gmail.com");
                    $mail->setSubject('Samsung Mob!lers üzenet');
                    $mail->send();
                }
                $this->_redirect('/messages/adminlist');
            }
        }
        $this->view->form = $form;
    }

    public function answerAction() {

        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('layout');

        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_redirect('index/index');
        }

        $id = $this->_request->getParam('id');

        $messageModel = new Model_DbTable_Messages();
        $messageData = $messageModel->getMessage($id);

        $form = new Form_Messages();
        $form->getElement('subject')->setValue($messageData[0]["subject"]);
        //$form->getElement('lead')->setValue("<br/><br/>".$message[0]["message"]);
        $form->removeElement('receiver');

        if ($this->getRequest()->isPost()) {

            if ($form->isValid($this->getRequest()->getPost())) {
                $subject = $form->getValue('subject');
                //$receivers = $form->getValue('receiver');
                $message = $form->getValue('lead');
                $date = date("Y-m-d H:i:s", time());

                $auth = Zend_Auth::getInstance();
                $identity = $auth->getIdentity();
                $sender = $identity->user_id;

                $thread_id = $this->_request->getParam('thread');

                $messageModel->saveMessage($message, $thread_id, $subject, $sender, $messageData[0]["sender"], $date);

                $modelUser = new Model_DbTable_User();
                $receiver = $modelUser->getUser($messageData[0]["sender"]);

                $bodyText = "<body>";
                $bodyText .= "<p>Kedves " . $receiver["username"] . "!</p>";
                $bodyText .= "<p>Üzeneted érkezett a mobilers.samsung.hu oldalon.</p>";
                $bodyText .= "<p>Üdv,<br/>Samsung Mob!lers csapat</p>";
                $bodyText .= "</body>";

                $mail = new Zend_Mail('UTF-8');
                $mail->setBodyText($bodyText);
                $mail->setBodyHtml($bodyText);
                $mail->setHeaderEncoding(Zend_Mime::ENCODING_BASE64);
                $mail->setFrom('noreply@mobilers.samsung.hu', 'mobilers.samsung.hu');
                $mail->addTo($receiver["email"]);
                //$mail->addBcc("attila.erdei87@gmail.com");
                $mail->setSubject('Samsung Mob!lers üzenet');
                $mail->send();

                $this->_redirect('/messages/list');
            }
        }
        $this->view->form = $form;
    }

    public function adminanswerAction() {

        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('admin');

        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_redirect('index/index');
        }

        $id = $this->_request->getParam('id');

        $messageModel = new Model_DbTable_Messages();
        $messageData = $messageModel->getMessage($id);

        $form = new Form_Messages();
        $form->getElement('subject')->setValue($messageData[0]["subject"]);
        //$form->getElement('lead')->setValue("<br/><br/>".$message[0]["message"]);
        $form->removeElement('receiver');

        if ($this->getRequest()->isPost()) {

            if ($form->isValid($this->getRequest()->getPost())) {
                $subject = $form->getValue('subject');
                //$receivers = $form->getValue('receiver');
                $message = $form->getValue('lead');
                $date = date("Y-m-d H:i:s", time());

                $auth = Zend_Auth::getInstance();
                $identity = $auth->getIdentity();
                $sender = $identity->user_id;

                $thread_id = $this->_request->getParam('thread');

                $messageModel->saveMessage($message, $thread_id, $subject, $sender, $messageData[0]["sender"], $date);

                $modelUser = new Model_DbTable_User();
                $receiver = $modelUser->getUser($messageData[0]["sender"]);

                $bodyText = "<body>";
                $bodyText .= "<p>Kedves " . $receiver["username"] . "!</p>";
                $bodyText .= "<p>Üzeneted érkezett a mobilers.samsung.hu oldalon.</p>";
                $bodyText .= "<p>Üdv,<br/>Samsung Mob!lers csapat</p>";
                $bodyText .= "</body>";

                $mail = new Zend_Mail('UTF-8');
                $mail->setBodyText($bodyText);
                $mail->setBodyHtml($bodyText);
                $mail->setHeaderEncoding(Zend_Mime::ENCODING_BASE64);
                $mail->setFrom('noreply@mobilers.samsung.hu', 'mobilers.samsung.hu');
                $mail->addTo($receiver["email"]);
                //$mail->addBcc("attila.erdei87@gmail.com");
                $mail->setSubject('Samsung Mob!lers üzenet');
                $mail->send();

                $this->_redirect('/messages/adminlist/tab/sent');
            }
        }
        $this->view->form = $form;
    }

    public function listAction() {

        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('layout');

        $tab = $this->_request->getParam('tab');

        if ($tab == "sent") {
            $modelMessages = new Model_DbTable_Messages();
            $messages = $modelMessages->getMessagesByMobilerSent($_SESSION["Zend_Auth"]["storage"]->user_id);

            $this->view->tab = "sent";
            $this->view->messages = $messages;
        } elseif ($tab == "get") {
            $modelMessages = new Model_DbTable_Messages();
            $messages = $modelMessages->getMessagesByMobilerGot($_SESSION["Zend_Auth"]["storage"]->user_id);

            $this->view->tab = "get";
            $this->view->messages = $messages;
        }
    }

    public function adminlistAction() {

        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('admin');

        $tab = $this->_request->getParam('tab');

        if ($tab == "sent") {
            $modelMessages = new Model_DbTable_Messages();
            $messages = $modelMessages->getMessagesByMobilerSent($_SESSION["Zend_Auth"]["storage"]->user_id);

            $this->view->tab = "sent";
            $this->view->messages = $messages;
        } elseif ($tab == "get") {
            $modelMessages = new Model_DbTable_Messages();
            $messages = $modelMessages->getMessagesByMobilerGot($_SESSION["Zend_Auth"]["storage"]->user_id);

            $this->view->tab = "get";
            $this->view->messages = $messages;
        }
    }

    public function deleteAction() {

        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->getHelper('layout')->disableLayout();

        $id = $this->_request->getParam('id');

        $modelComment = new Model_DbTable_Comments();
        $comments = $modelComment->getComments($id);

        if ($comments):
            foreach ($comments as $comment):
                $modelComment->deleteComment($comment["comment_id"]);
            endforeach;
        endif;

        $modelRate = new Model_DbTable_Rates();
        $rates = $modelRate->getRates($id);

        if ($rates):
            foreach ($rates as $rate):
                $modelRate->deleteRate($rate["rate_id"]);
            endforeach;
        endif;

        $modelPosts = new Model_DbTable_Posts();
        $modelPosts->deletePost($id);

        $this->_redirect('/messages/adminlist?delete=1');
    }

}
