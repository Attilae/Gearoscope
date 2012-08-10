<?php

class RegistrationController extends Zend_Controller_Action {

    public function init() {
        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('layout');

        $this->view->addHelperPath("ZendX/JQuery/View/Helper", "ZendX_JQuery_View_Helper");
    }

    public function indexAction() {

        $form = new Form_MobilersRegistration;
        $form->setDecorators(array(array('ViewScript', array('viewScript' => 'registration/form.phtml'))));

        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($_POST) && $form->getValue("terms")) {

                $name = $form->getValue('name');
                $sex = $form->getValue('sex');
                $city = $form->getValue('city');
                $graduate = $form->getValue('graduate');
                $job = $form->getValue('job');
                $age = $form->getValue('age');
                $phone = $form->getValue('phone');
                $email = $form->getValue('email');
                $activate = $form->getValue('activate');
                $facebook = $form->getValue('facebook');
                $facebookUsers = $form->getValue('facebook_users');
                $twitter = $form->getValue('twitter');
                $twitterUsers = $form->getValue('twitter_users');
                $blog = $form->getValue('blog');
                $blogAddress = $form->getValue('blog_address');
                $app = $form->getValue('app');
                $bio = $form->getValue('bio');
                $date = date("Y-m-d H:i:s", time());

                $model = new Model_DbTable_Mobiler();
                $user = $model->createMobiler($name, $sex, $city, $graduate, $job, $age, $phone, $email, $activate, $facebook, $facebookUsers, $twitter, $twitterUsers, $blog, $blogAddress, $app, $bio, $date);


                $bodyText = "<body>";
                $bodyText .= "<p>Köszönjük, hogy jelentkeztél Mob!lernek!</p>";
                $bodyText .= "<p>Amennyiben a megadott információk alapján megtetszel nekünk, egy személyes meghallgatásra invitálunk majd, ahol személyesen is bemutatkozhatsz a Samsung Mob!ler Zsűrinek. </p>";
                $bodyText .= "<p>Ha választásunk nem Rád esik, ne csüggedj: legközelebb újra nekiveselkedhetsz a megmérettetésnek! Addig pedig kövesd a Mob!lereket a <a href='http://mobilers.samsung.hu' target='_blank'>mobilers.samsung.hu</a> oldalon valamint a közösségi portálokon, és tudj meg minél többet a Samsung Mobile izgalmas világáról.</p>";
                $bodyText .= "</body>";


                $mail = new Zend_Mail('UTF-8');
                $mail->setBodyText($bodyText);
                $mail->setBodyHtml($bodyText);
                $mail->setHeaderEncoding(Zend_Mime::ENCODING_BASE64);
                $mail->setFrom('noreply@mobilers.samsung.hu', 'mobilers.samsung.hu');
                $mail->addTo($email);
                //$mail->addBcc("attila.erdei87@gmail.com");
                $mail->setSubject('Samsung Mob!lers regisztráció');
                $mail->send();

                $bodyText = "<body>";
                $bodyText .= "<p>Regisztráció érkezett a Samsung Mob!lers oldalról</p>";
                $bodyText .= "<p>Név: ". $name ."</p>";
                $bodyText .= "<p>E-mail: ". $email ."</p>";
                $bodyText .= "<p>Nem: ". $sex ."</p>";
                $bodyText .= "<p>Város: ". $city ."</p>";
                $bodyText .= "<p>Végzettség: ". $graduate ."</p>";
                $bodyText .= "<p>Foglalkozás: ". $job ."</p>";
                $bodyText .= "<p>Telefon: ". $phone ."</p>";
                $bodyText .= "<p>Hobbi: ". $activate ."</p>";
                $bodyText .= "<p>Facebook user: ". $facebook ."</p>";
                $bodyText .= "<p>Facebook ismerősök: ". $facebookUsers ."</p>";
                $bodyText .= "<p>Twitter: ". $twitter ."</p>";
                $bodyText .= "<p>Twitter követők: ". $twitterUsers ."</p>";
                $bodyText .= "<p>Blog: ". $blog ."</p>";
                $bodyText .= "<p>Blog URL: ". $blogAddress ."</p>";
                $bodyText .= "<p>Kedvenc app: ". $app ."</p>";
                $bodyText .= "<p>Bio: ". $bio ."</p>";
                $bodyText .= "<p>Dátum: ". $date ."</p>";
                $bodyText .= "</body>";

                $mail = new Zend_Mail('UTF-8');
                $mail->setBodyText($bodyText);
                $mail->setBodyHtml($bodyText);
                $mail->setHeaderEncoding(Zend_Mime::ENCODING_BASE64);
                $mail->setFrom('noreply@mobilers.samsung.hu', 'mobilers.samsung.hu');
                $mail->addTo("attila.erdei87@gmail.com");
                $mail->addBcc("fiertelmeister.anita@carpedm.hu");
                $mail->addBcc("egri.denes@carpedm.hu");
                $mail->setSubject('Samsung Mob!lers regisztráció');
                $mail->send();

                $this->_redirect('/registration/successfull');
            } else {
                $errorMessages = $form->getErrors();
                //print_r($form->getErrors());
                $this->view->error = "1";                                
                
                $errorMessage = "Kérlek töltsd ki helyesen az összes kötelező mezőt!<br/>";
                
                if(!$form->getValue("terms")) {
                    $errorMessage .= "A regisztációhoz el kell fogadnod a feltételeket!";                    
                }
                
                if(isset($errorMessages["email"][0]["emailAddressInvalidFormat"])) {
                    $errorMessage .= "Kérjük add meg helyesen az e-mail címed!";
                }
                
//                if(isset($errorMessages["email"][0]["emailAddressInvalidFormat"])) {
//                    $errorMessage .= "Kérjük add meg helyesen az e-mail címed!";
//                }

                
                $this->view->dialog = $this->view->dialogContainer(
                                'complete',
                                $errorMessage,
                                array(
                                    'bgiframe' => true,
                                    'autoOpen' => true,
                                    'draggable' => false,
                                    'modal' => true,
                                    'resizable' => false,
                                    'title' => 'Hiba',
                                    'closeOnEscape' => true,
                                    'buttons' => array(
                                        'OK' => new Zend_Json_Expr('function() {$(this).dialog(\'close\');"}')
                                    ),
                                )
                );
            }
        }
    }

    public function successfullAction() {
        
    }

    public function listAction() {
        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('admin');

        $modelMobiler = new Model_DbTable_Mobiler();
        $mobilers = $modelMobiler->listMobiler();

        $this->view->mobilers = $mobilers;
    }

}

?>
