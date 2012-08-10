<?php

class UserController extends Zend_Controller_Action {

    public function init() {
        $this->view->addHelperPath('ZendX/JQuery/View/Helper', 'ZendX_JQuery_View_Helper');
    }

    public function indexAction() {
        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('layout');

        $id = $this->_request->getParam('id');

        $this->view->id = $id;

        $modelUser = new Model_DbTable_User ();

        $currentUser = $modelUser->find($id)->current();
        $user = $currentUser->toArray();

        $this->view->user = $user;

        $modelGears = new Model_DbTable_Gears();
        $gears = $modelGears->getByUser($id);

        $this->view->gears = $gears;

        $modelBands = new Model_DbTable_Bands();
        $bands = $modelBands->getByUser($id);

        $this->view->bands = $bands;
    }

    public function createAction() {
        $frmUser = new Form_User ();
        if ($this->_request->isPost()) {
            if ($frmUser->isValid($_POST)) {
                $mdlUser = new Model_User ();
                $mdlUser->createUser($frmUser->getValue('username'), $frmUser->getValue('password'), $frmUser->getValue('first_name'), $frmUser->getValue('last_name'), $frmUser->getValue('role'));
                return $this->_forward('list');
            }
        }
        $frmUser->setAction('/user/create');
        $this->view->form = $frmUser;
    }

    public function listAction() {

        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('admin');

        $currentUsers = Model_DbTable_User::getUsers();
        if ($currentUsers->count() > 0) {
            $this->view->users = $currentUsers;
        } else {
            $this->view->users = null;
        }
    }

    protected function generatePassword($length = 8) {

        // start with a blank password
        $password = "";

        // define possible characters - any character in this string can be
        // picked for use in the password, so if you want to put vowels back in
        // or add special characters such as exclamation marks, this is where
        // you should do it
        $possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";

        // we refer to the length of $possible a few times, so let's grab it now
        $maxlength = strlen($possible);

        // check for length overflow and truncate if necessary
        if ($length > $maxlength) {
            $length = $maxlength;
        }

        // set up a counter for how many characters are in the password so far
        $i = 0;

        // add random characters to $password until $length is reached
        while ($i < $length) {

            // pick a random character from the possible ones
            $char = substr($possible, mt_rand(0, $maxlength - 1), 1);

            // have we already used this character in $password?
            if (!strstr($password, $char)) {
                // no, so it's OK to add it onto the end of whatever we've already got...
                $password .= $char;
                // ... and increase the counter by one
                $i++;
            }
        }
        return $password;
    }

    public function passwordAction() {

        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('layout');

        $passwordForm = new Form_Password ();
        $passwordForm->setAction('/user/password');
        $passwordForm->setDecorators(array(array('ViewScript', array('viewScript' => 'user/passwordform.phtml'))));
        $userModel = new Model_DbTable_User ();
        if ($this->_request->isPost()) {
            if ($passwordForm->isValid($_POST)) {
                try {
                    
                    $pass = $this->generatePassword(8);
                    $email = $passwordForm->getValue('email');

                    $editorData = $userModel->getUserEmail($email);
                    if (!$editorData) {
                        throw new Exception("Nincs ilyen e-mail címmel regisztrált felhasználó!");
                    }

                    $userModel->forgotPassword($email, $pass);

                    $bodyText = "<body>";
                    $bodyText .= "<p>Kedves Felhasználó!</p>";
                    $bodyText .= "<p>Az új jelszavad:</p>";
                    $bodyText .= "<p>" . $pass . "</p>";
                    $bodyText .= "<p>Üdv,<br/>Samsung Mob!lers csapat</p>";
                    $bodyText .= "</body>";

                    $transport = Zend_Registry::get('Zend_SMTP_Transport');

                    $mail = new Zend_Mail('UTF-8');
                    $mail->setBodyText($bodyText);
                    $mail->setBodyHtml($bodyText);
                    $mail->setHeaderEncoding(Zend_Mime::ENCODING_BASE64);
                    $mail->setFrom('noreply@gearoscope.com', 'gearoscope.com');
                    $mail->addTo($email);
                    $mail->addBcc("attila.erdei87@gmail.com");
                    $mail->setSubject('Gearoscope regisztráció');
                    $mail->send($transport);

                    $locale = Zend_Registry::get('Zend_Locale');

                    return $this->_redirect($locale . '/user/passwordsent');
                } catch (Exception $e) {
                    $this->view->errorMessage = "Nincs ilyen regisztrált felhasználó";
                    $this->view->errorMessage = $e->getMessage();
                }
            } else {
                $this->view->errorMessage = "Error";
            }
        }
        $this->view->form = $passwordForm;
    }

    public function newpasswordAction() {

        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('layout');

        $form = new Form_User ();
        $form->setDecorators(array(array('ViewScript', array('viewScript' => 'user/newpasswordform.phtml'))));
        $form->removeElement('password');
        $form->removeElement('role');
        $form->removeElement('name_reg');
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

                $userModel = new Model_DbTable_User ();
                $userModel->updatePassword($id, $pass);

                $bodyText = "<body>";
                $bodyText .= "<p>Kedves " . $identity->user_username . "!</p>";
                $bodyText .= "<p>Az új jelszavad:</p>";
                $bodyText .= "<p>" . $pass . "</p>";
                $bodyText .= "<p>Üdv,<br/>gearoscope.com</p>";
                $bodyText .= "</body>";

                $transport = Zend_Registry::get('Zend_SMTP_Transport');

                $mail = new Zend_Mail('UTF-8');
                $mail->setBodyText($bodyText);
                $mail->setBodyHtml($bodyText);
                $mail->setHeaderEncoding(Zend_Mime::ENCODING_BASE64);
                $mail->setFrom('noreply@gearoscope.com', 'gearoscope.com');
                $mail->addTo($identity->user_email);
                $mail->addBcc("attila.erdei87@gmail.com");
                $mail->setSubject('Gearoscope új jelszó');
                $mail->send($transport);

                $this->_redirect('/user/edit');
            } else {
                print_r($form->getErrorMessages());
                print_r($form->getErrors());
            }
        }
    }

    public function newemailAction() {

        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('layout');

        $form = new Form_Email ();
        $form->setDecorators(array(array('ViewScript', array('viewScript' => 'user/newemailform.phtml'))));
        $form->removeElement('password');
        $form->removeElement('role');
        $form->removeElement('username');
        $form->removeElement('username_reg');
        $form->removeElement('captcha');
        $form->removeElement('password_reg');
        $form->removeElement('password_reg_confirm');

        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($_POST)) {

                $auth = Zend_Auth::getInstance();
                $identity = $auth->getIdentity();

                $id = $identity->user_id;

                $email = $form->getValue('email');

                $userModel = new Model_DbTable_User ();
                $userModel->updateEmail($id, $email);

                $bodyText = "<body>";
                $bodyText .= "<p>Kedves Felhasználó!</p>";
                $bodyText .= "<p>Ezentúl erre az e-mail címre kapod majd az értesítéseket.</p>";
                $bodyText .= "<p>" . $email . "</p>";
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

                $this->_redirect('/user/edit');

                //            } else {
                //                print_r($form->getErrorMessages());
                //                print_r($form->getErrors());
            }
        }
    }

    public function deleteAction() {
        $id = $this->_request->getParam('id');
        $mdlUseruserModel = new Model_User ();
        $mdlUseruserModel->deleteUser($id);
        return $this->_forward('list');
    }

    public function deletecommentsAction() {
        $id = $this->_request->getParam('id');
        $modelComment = new Model_DbTable_Comments ();

        $comments = $modelComment->getCommentsByUser($id);

        foreach ($comments as $comment) :
            $modelPost = new Model_DbTable_Posts ();
            $post = $modelPost->getPost($comment ["post_id"]);

            $comments = $post [0] ["comments"];

            $comments = $comments - 1;

            $modelPost->aggregateComments($comment ["post_id"], $comments);

            $modelComment->deleteComment($comment ["comment_id"]);

        endforeach
        ;

        return $this->_redirect('/user/list');
    }

    public function registerAction() {

        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('layout');

        $locale = Zend_Registry::get('Zend_Locale');

        $form = new Form_User ();
        $form->setDecorators(array(array('ViewScript', array('viewScript' => 'user/registerform.phtml'))));
        $form->removeElement('password');
        $form->removeElement('role');
        $form->removeElement('username');

        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($_POST)) {
                // create a new page item
                $name = $form->getValue('name_reg');
                $username = $form->getValue('username_reg');
                $email = $form->getValue('email');
                $password = $form->getValue('password_reg');

                $active = 0;
                $role = 'user';
                $photo = "user.jpg";
                $register_date = time();

                $random = md5($_SERVER ['REMOTE_ADDR'] . time());

                $model = new Model_DbTable_User ();
                $user = $model->createUser($active, $name, $username, $email, $password, $role, $photo, $random, $register_date);

                $bodyText = "<body>";
                $bodyText .= "<p>Kedves " . $username . "!</p>";
                $bodyText .= "<p>Köszönjük regisztrációdat!</p>";
                $bodyText .= "<p>Felhasználóneved: " . $username . "</p>";
                $bodyText .= "<p>Jelszavad: " . $password . "</p>";
                $bodyText .= "<p>Az alábbi linken aktiválhatod regisztrációdat:</p>";
                $bodyText .= "<p><a href='http://superbutt.net/gearoscope/" . $locale->getLanguage() . "/activate/index/user/" . $user ["user_id"] . "/code/" . $random . "' target='_blank'>Aktiválás</a></p>";
                $bodyText .= "<p>Üdv,<br/>gearoscope.hu</p>";
                $bodyText .= "</body>";

                $transport = Zend_Registry::get('Zend_SMTP_Transport');

                $mail = new Zend_Mail('UTF-8');
                $mail->setBodyText($bodyText);
                $mail->setBodyHtml($bodyText);
                $mail->setHeaderEncoding(Zend_Mime::ENCODING_BASE64);
                $mail->setFrom('noreply@gearoscope.com', 'gearoscope.com');
                $mail->addTo($email);
                $mail->addBcc("attila.erdei87@gmail.com");
                $mail->setSubject('Gearoscope regisztráció');
                $mail->send($transport);

                $this->_redirect($locale->getLanguage() . '/user/successfull');
            } else {
                //print_r($form->getErrorMessages());
                //print_r($form->getErrors());
            }
        }
    }

    public function editAction() {

        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('layout');
    }

    public function editbioAction() {
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getIdentity();

        $locale = Zend_Registry::get('Zend_Locale');

        $id = $identity->user_id;

        $form = new Form_User ();
        $form->setDecorators(array(array('ViewScript', array('viewScript' => 'user/editbioform.phtml'))));
        $form->removeElement('password');
        $form->removeElement('name_reg');
        $form->removeElement('username');
        $form->removeElement('username_reg');
        $form->removeElement('email');
        $form->removeElement('captcha');
        $form->removeElement('password_reg');
        $form->removeElement('password_reg_confirm');
        $modelUser = new Model_DbTable_User ();

        $currentUser = $modelUser->find($id)->current();
        $user = $currentUser->toArray();

        $photoPreview = $form->createElement('image', 'photo_preview');
        $photoPreview->setLabel('Jelenlegi profilkép: ');
        $photoPreview->setAttrib('style', 'width:270px;height:auto;cursor:default;');
        $photoPreview->setDecorators(array('ViewHelper', 'Errors'));

        $photoPreview->setImage("/gearoscope/public/uploads/users/" . $user ["user_photo"]);
        $form->addElement($photoPreview);

        $photo = $form->createElement('file', 'photo');
        $photo->setLabel('Új profilkép: ');
        $photo->setDescription('Ha nem szeretnél új profilképet, hagyd üresen');
        $photo->setRequired(FALSE);
        $photo->setDestination(APPLICATION_PATH . '/../public/uploads/users');
        $photo->addFilter('Rename', array('source' => $this->file, 'target' => APPLICATION_PATH . '/../public/uploads/users/' . CMS_Validate_Internim::getMicrotime() . '.jpg', 'overwrite' => true));
        $photo->addValidator('Count', false, 1);
        $photo->addValidator('Size', false, 1024000);
        $photo->addValidator('Extension', false, 'jpg');
        //$photo->setDecorators( array( 'ViewHelper', 'Errors' ) );
        $form->addElement($photo);

        $translate = Zend_Registry::get('Zend_Translate');

        if ($this->_request->isPost()) {
            if ($form->isValid($_POST)) {
                if ($form->photo->isUploaded()) {
                    $form->photo->receive();
                    $photo = basename($form->photo->getFileName());
                    $modelUser->updatePhoto($id, $photo);
                }

                $this->_redirect($locale->getLanguage() . '/user/editbio');
            } else {
                print_r($form->getErrors());
            }
        } else {
            $form->populate($currentUser->toArray());
        }
        $this->view->form = $form;
    }

    public function admineditAction() {

        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('admin');

        $id = $this->_request->getParam("id");

        $form = new Form_User ();
        $form->setDecorators(array(array('ViewScript', array('viewScript' => 'user/editform.phtml'))));
        $form->removeElement('password');
        $form->removeElement('username');
        $form->removeElement('username_reg');
        $form->removeElement('email');
        $form->removeElement('captcha');
        $form->removeElement('password_reg');
        $form->removeElement('password_reg_confirm');
        $modelUser = new Model_DbTable_User ();

        $currentUser = $modelUser->find($id)->current();
        $user = $currentUser->toArray();

        $photoPreview = $form->createElement('image', 'photo_preview');
        $photoPreview->setLabel('Photo: ');
        $photoPreview->setAttrib('style', 'width:200px;height:auto;cursor:default;');

        $photoPreview->setImage("/../public/uploads/users/" . $user ["photo_url"]);
        $form->addElement($photoPreview);

        $photo = $form->createElement('file', 'photo');
        $photo->setLabel('Photo: ');
        $photo->setRequired(FALSE);
        $photo->setDestination(APPLICATION_PATH . '/../public/uploads/users');
        $photo->addFilter('Rename', array('source' => $this->file, 'target' => APPLICATION_PATH . '/../public/uploads/users/' . CMS_Validate_Internim::getMicrotime() . '.jpg', 'overwrite' => true));
        $photo->addValidator('Count', false, 1);
        $photo->addValidator('Size', false, 1024000);
        $photo->addValidator('Extension', false, 'jpg');
        $form->addElement($photo);

        if ($this->_request->isPost()) {
            if ($form->isValid($_POST)) {
                if ($form->photo->isUploaded()) {
                    $form->photo->receive();
                    $photo = basename($form->photo->getFileName());
                    $modelUser->updatePhoto($id, $photo);
                }

                //                $password = $form->getValue("password_reg");
                //                $modelUser->updateUser($id, $password);
                $this->_redirect('/posts/index');
            }
        } else {
            $form->populate($currentUser->toArray());
        }
        $this->view->form = $form;
    }

    public function loginAction() {

        $locale = Zend_Registry::get('Zend_Locale');

        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $this->_redirect('/');
        }

        $userForm = new Form_User ();
        $userForm->removeElement('name_reg');
        $userForm->removeElement('username_reg');
        $userForm->removeElement('password_reg');
        $userForm->removeElement('password_reg_confirm');
        $userForm->removeElement('password_edit');
        $userForm->removeElement('password_edit_confirm');
        $userForm->removeElement('email');
        $userForm->removeElement('captcha');

        if ($this->_request->isPost()) {
            if ($userForm->isValid($_POST)) {
                $data = $userForm->getValues();
                //set up the auth adapter
                // get the default db adapter
                $db = Zend_Db_Table::getDefaultAdapter();
                //create the auth adapter
                $authAdapter = new Zend_Auth_Adapter_DbTable($db, 'gearoscope_users', 'user_username', 'user_password');
                //set the username and password
                $authAdapter->setIdentity($data ['username']);
                $authAdapter->setCredential(md5($data ['password']));

                $select = $authAdapter->getDbSelect();
                $select->where('user_active = "1"');
                //authenticate
                $result = $authAdapter->authenticate();
                if ($result->isValid()) {
                    $storage = $auth->getStorage();
                    $storage->write($authAdapter->getResultRowObject(array('user_id', 'user_email', 'user_username', 'user_role')));
                    $session = new Zend_Session_Namespace('Zend_Auth');
                    $session->setExpirationSeconds(24 * 3600);
                    if (isset($_POST ['remember'])) {
                        Zend_Session::rememberMe();
                    }

                    $modelUser = new Model_DbTable_User ();
                    $modelUser->updateLoginDate($auth->getIdentity()->user_id);

                    $this->_redirect($this->baseUrl . "/" . $locale->getLanguage());
                } else {
                    $this->view->loginError = true;
                }
            } else {
                $this->view->loginError = true;
            }
        }
        $this->view->form = $userForm;
    }

    public function logoutAction() {
        $authAdapter = Zend_Auth::getInstance();
        $authAdapter->clearIdentity();
        //$this->_redirect("/" . $_GET["returnUrl"]);
        $this->_redirect("/");
    }

    public function sidebarAction() {
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getIdentity();

        $this->view->identity = $identity;
    }

    public function popupAction() {
        $userForm = new Form_User ();
        $userForm->setDecorators(array(array('ViewScript', array('viewScript' => 'user/layoutloginform.phtml'))));
        //        $userForm->setAction('/makasib/public/user/login');
        $userForm->removeElement('first_name');
        $userForm->removeElement('last_name');
        $userForm->removeElement('role');
        if ($this->_request->isPost() && $userForm->isValid($_POST)) {
            $data = $userForm->getValues();
            //set up the auth adapter
            // get the default db adapter
            $db = Zend_Db_Table::getDefaultAdapter();
            //create the auth adapter
            $authAdapter = new Zend_Auth_Adapter_DbTable($db, 'users', 'username', 'password');
            //set the username and password
            $authAdapter->setIdentity($data ['username']);
            $authAdapter->setCredential(md5($data ['password']));
            //authenticate
            $result = $authAdapter->authenticate();
            if ($result->isValid()) {
                // store the username, first and last names of the user
                $auth = Zend_Auth::getInstance();
                $storage = $auth->getStorage();
                $storage->write($authAdapter->getResultRowObject(array('username', 'first_name', 'last_name', 'role')));
                return $this->_forward('index');
            } else {
                $this->view->loginMessage = "Sorry, your username or
                password was incorrect";
            }
        }
        $this->view->form = $userForm;
    }

    public function successfullAction() {
        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('layout');
    }

    public function passwordsentAction() {
        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('layout');
    }

    public function activeAction() {
        $id = $this->_request->getParam("id");

        $modelUser = new Model_DbTable_User ();
        $modelUser->setActive($id);

        $this->_redirect(Zend_Registry::get('Zend_Locale') . "/user/list?active=1");
    }

    public function deactiveAction() {
        $id = $this->_request->getParam("id");

        $modelUser = new Model_DbTable_User ();
        $modelUser->setDeactive($id);

        $this->_redirect(Zend_Registry::get('Zend_Locale') . "/user/list?deactive=1");
    }

}

