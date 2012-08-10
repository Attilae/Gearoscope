<?php

class BandsController extends Zend_Controller_Action {

    public function init() {
        $this->view->addHelperPath(
                'ZendX/JQuery/View/Helper'
                , 'ZendX_JQuery_View_Helper');
    }

    public function indexAction() {

        $baseUrl = $this->view->baseUrl();

        $this->view->headScript()->prependFile($baseUrl . "/public/skins/gearoscope/js/highlight.pack.js");
        $this->view->headScript()->prependFile($baseUrl . "/public/skins/gearoscope/js/script.js");
        $this->view->headScript()->prependFile($baseUrl . "/public/skins/gearoscope/js/filtrify.min.js");

        $this->view->headScript()->prependScript('$(function() {
			$.filtrify("artists", "placeHolder", {
				close : true
			});
		});');

        $this->view->headLink()->appendStylesheet($baseUrl . "/public/skins/gearoscope/css/filtrify.css");

        $bandsModel = new Model_DbTable_Bands();
        $bands = $bandsModel->getActive();

        $this->view->bands = $bands;
    }

    public function viewAction() {

        $band_id = $this->_request->getParam("id");

        $bandsModel = new Model_DbTable_Bands();
        $band = $bandsModel->getBand($band_id);

        $this->view->band = $band;
    }

    public function collectAction() {

        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $tag = $_GET["term"];

        $modelBands = new Model_DbTable_Bands();
        $bands = $modelBands->findForAutocomplete($tag);

        $i = 0;
        $bandsArray = array();
        foreach ($bands as $band):
            $bandsArray[$i] = array(
                "key" => $band['band_id'],
                "value" => $band['band_name']
            );
            $i++;
        endforeach;

        print json_encode($bandsArray);

        //print '[{"key": "hello world", "value": "hello world"}, {"key": "movies", "value": "movies"}, {"key": "ski", "value": "ski"}, {"key": "snowbord", "value": "snowbord"}, {"key": "computer", "value": "computer"}, {"key": "apple", "value": "apple"}, {"key": "pc", "value": "pc"}, {"key": "ipod", "value": "ipod"}, {"key": "ipad", "value": "ipad"}, {"key": "iphone", "value": "iphone"}, {"key": "iphon4", "value": "iphone4"}, {"key": "iphone5", "value": "iphone5"}, {"key": "samsung", "value": "samsung"}, {"key": "blackberry", "value": "blackberry"}]';
    }

    public function addAction() {    	

        $form = new Form_Band();
        $form->setDecorators(array(array('ViewScript', array('viewScript' => 'bands/addform.phtml'))));

        $locale = Zend_Registry::get('Zend_Locale');

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $auth = Zend_Auth::getInstance();
                $identity = $auth->getIdentity();
                $user_id = $identity->user_id;
                $band_name = $form->getValue('band_name');
                $formation_year = $form->getValue('formation_year');
                $style = $form->getValue('style');
                $website = $form->getValue('website');
                $date = time();
                $active = "1";

                if ($form->photo->isUploaded()) {
                    $adapter = $form->photo->getTransferAdapter();
                    $receivingOK = true;
                    foreach ($adapter->getFileInfo() as $file => $info) {
                        $extension = pathinfo($info['name'], PATHINFO_EXTENSION);
                        $photo_url = (int) CMS_Validate_Internim::getMicrotime() . '.' . $extension;
                        $adapter->addFilter('Rename', "uploads/bands/" . $photo_url, $file);
                        if (!$adapter->receive($file)) {
                            $receivingOK = false;
                        }
                    }
                }

                $styleModel = new Model_DbTable_Styles();
                $styleFromDb = $styleModel->getStyle($style);
                if (empty($styleFromDb)) {
                    $style_id = $styleModel->addStyle($style);
                } else {
                    $style_id = $styleFromDb[0]["style_id"];
                }

                $bandModel = new Model_DbTable_Bands();
                $band_id = $bandModel->addBand($user_id, $active, $band_name, $formation_year, $style_id, $website, $photo_url, $date);

                $random = md5($_SERVER['REMOTE_ADDR'] . time());
                
                $editorsModel = new Model_DbTable_BandEditors();
                $editor = $editorsModel->addEditor($user_id, $band_id, $active, $random);

                $this->_redirect('/' . $locale . '/bands/view/id/' . $band_id);
            }
        }

        $this->view->form = $form;
    }

    public function editAction() {

        $baseUrl = $this->view->baseUrl();

        $this->view->headScript()->prependScript('
			$(document).ready(function(){
				$("a.addeditor").colorbox({
					iframe:true,
					width:"800px",
				 	height:"600px"				 				 
				});
				$(".picture_upload").colorbox({
					iframe:true,
					width:"800px",
				 	height:"300px"				 	
				});
			});'
        );

        $this->view->headScript()->prependFile($baseUrl . "/public/skins/gearoscope/js/jquery.colorbox-min.js");

        $this->view->headLink()->appendStylesheet($baseUrl . "/public/skins/gearoscope/css/colorbox.css");

        $form = new Form_Band();
        $form->setDecorators(array(array('ViewScript', array('viewScript' => 'bands/addform.phtml'))));

        $band_id = $this->_request->getParam("id");
        $this->view->band_id = $band_id;

        $bandsModel = new Model_DbTable_Bands();
        $band = $bandsModel->getBand($band_id);

        $form->getElement('band_name')->setValue($band[0]["band_name"]);
        $form->getElement('formation_year')->setValue($band[0]["formation_year"]);
        $form->getElement('style')->setValue($band[0]["style"]);
        $form->getElement('website')->setValue($band[0]["website"]);

        $locale = Zend_Registry::get('Zend_Locale');

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $auth = Zend_Auth::getInstance();
                $identity = $auth->getIdentity();
                $user_id = $identity->user_id;
                $band_name = $form->getValue('band_name');
                $formation_year = $form->getValue('formation_year');
                $style = $form->getValue('style');
                $website = $form->getValue('website');
                $date = time();
                $active = "1";

                if ($form->photo->isUploaded()) {
                    $adapter = $form->photo->getTransferAdapter();
                    $receivingOK = true;
                    foreach ($adapter->getFileInfo() as $file => $info) {
                        $extension = pathinfo($info['name'], PATHINFO_EXTENSION);
                        $photo_url = (int) CMS_Validate_Internim::getMicrotime() . '.' . $extension;
                        $adapter->addFilter('Rename', "uploads/bands/" . $photo_url, $file);
                        if (!$adapter->receive($file)) {
                            $receivingOK = false;
                        }
                        if ($receivingOK) {
                            $bandsModel->updatePhoto($band_id, $photo_url);
                        }
                    }
                }

                $styleModel = new Model_DbTable_Styles();
                $styleFromDb = $styleModel->getStyle($style);
                if (empty($styleFromDb)) {
                    $style_id = $styleModel->addStyle($style);
                } else {
                    $style_id = $styleFromDb[0]["style_id"];
                }

                $bandModel = new Model_DbTable_Bands();
                $bandModel->editBand($band_id, $user_id, $band_name, $formation_year, $style_id, $website);
                $bodyText = "<body>";
                $bodyText .= "<p>Új bejegyzést küldtek be a Samsung Mobilers oldalon.</p>";
                $bodyText .= "<p>Beküldő: " . $identity->username . "</p>";
                $bodyText .= "<p>Cím: <a href='http://mobilers.samsung.hu/posts/view/id/" . $post_id . "' target='_blank'>" . $title . "</a></p>";
                $bodyText .= "<p>Üdv,<br/>Samsung Mob!lers csapat</p>";
                $bodyText .= "</body>";

                $mail = new Zend_Mail('UTF-8');
                $mail->setBodyText($bodyText);
                $mail->setBodyHtml($bodyText);
                $mail->setHeaderEncoding(Zend_Mime::ENCODING_BASE64);
                $mail->setFrom('noreply@mobilers.samsung.hu', 'mobilers.samsung.hu');
                $mail->addTo("attila.erdei87@gmail.com");
                $mail->setSubject('Samsung Mob!lers új bejegyzés');
                //$mail->send();

                $this->_redirect('/' . $locale . '/bands/view/id/' . $band_id);
            }
        }

        $modelBandEditors = new Model_DbTable_BandEditors();
        $editors = $modelBandEditors->getEditors($band_id);

        $this->view->editors = $editors;

        $this->view->form = $form;
    }

    public function stylesAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $tag = $_GET["term"];

        $modelStyles = new Model_DbTable_Styles();
        $styles = $modelStyles->findForAutocomplete($tag);

        $i = 0;
        $stylesArray = array();
        foreach ($styles as $style):
            $stylesArray[$i] = array(
                "key" => $style['style_id'],
                "value" => $style['style']
            );
            $i++;
        endforeach;

        print json_encode($stylesArray);
    }

    public function userAction() {

        $baseUrl = $this->view->baseUrl();

        $this->view->headScript()->prependFile($baseUrl . "/public/skins/gearoscope/js/highlight.pack.js");
        $this->view->headScript()->prependFile($baseUrl . "/public/skins/gearoscope/js/script.js");
        $this->view->headScript()->prependFile($baseUrl . "/public/skins/gearoscope/js/filtrify.min.js");

        $this->view->headScript()->prependScript('$(function() {
			$.filtrify("artists", "placeHolder", {
				close : true
			});
		});');

        $this->view->headLink()->appendStylesheet($baseUrl . "/public/skins/gearoscope/css/filtrify.css");

        $auth = Zend_Auth::getInstance();
        $identity = $auth->getIdentity();

        $locale = Zend_Registry::get('Zend_Locale');

        $user_id = $identity->user_id;

        $bandsModel = new Model_DbTable_Bands();
        $bands = $bandsModel->getByUser($user_id);

        $this->view->bands = $bands;
    }

    public function addeditorAction() {

        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('iframe');       

        $baseUrl = $this->view->baseUrl();

        $auth = Zend_Auth::getInstance();
        $identity = $auth->getIdentity();
        $user_id = $identity->user_id;

        $band_id = $this->_request->getParam("band_id");

        $locale = Zend_Registry::get('Zend_Locale');

        $form = new Form_BandsAddEditor();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {

                $editor = $form->getValue('editor');

                $modelUsers = new Model_DbTable_User();
                try {
                	/*
                	 * 
                	 * Megnézzük, hogy szerepel-e az e-mail cím a regisztrált userek között
                	 * 
                	 */
                    $editorData = $modelUsers->getUserEmail($editor);
                    if (!$editorData) {
                        throw new Exception("Nincs ilyen e-mail címmel regisztrált felhasználó!");
                    }
                    $active = "0";
                    
                    /*
                	 * 
                	 * Megnézzük, hogy meghívták-e már editornak
                	 * 
                	 */
                    $modelBandEditors = new Model_DbTable_BandEditors();
                    $editorExists = $modelBandEditors->getEditorByUserAndBandId($editorData[0]["user_id"], $band_id);
                    
                    if($editorExists) {
                    	throw new Exception("A felhasználónak már lett admin felkérés küldve!");
                    }
                    
                    $random = md5($_SERVER['REMOTE_ADDR'] . time());
                    
                    
                    $bandeditorid = $modelBandEditors->addEditor($editorData[0]["user_id"], $band_id, $active, $random);                   
                    
                    $message_type_id = "1";

                    $sender_id = $user_id;

                    //$modelMessages = new Model_DbTable_Messages();
                    //$modelMessages->addMessage($message_type_id, $sender_id, $receiver_id);
                    
                    $bodyText = "<body>";
                    $bodyText .= "<p>Kedves " . $editorData[0]["user_username"] . "!</p>";
                    $bodyText .= "<p>" . $identity->user_username . " nevű zenészt barátod meghívott, hogy szerkessz egy zenekart a Gearoscope-on!</p>";
                    $bodyText .= "<p>Az alábbi linkre kattintva Te is a banda adminja lehetsz:</p>";
                    $bodyText .= "<p><a href='http://superbutt.net/gearoscope/" . $locale->getLanguage() . "/bands/activate/editor/" . $bandeditorid . "/code/" . $random . "/' target='_blank'>Aktiválás</a></p>";
                    $bodyText .= "<p>Üdv,<br/>gearoscope.com</p>";
                    $bodyText .= "</body>";

                    $transport = Zend_Registry::get('Zend_SMTP_Transport');

                    $mail = new Zend_Mail('UTF-8');
                    $mail->setBodyText($bodyText);
                    $mail->setBodyHtml($bodyText);
                    $mail->setHeaderEncoding(Zend_Mime::ENCODING_BASE64);
                    $mail->setFrom('noreply@gearoscope.com', 'gearoscope.com');
                    $mail->addTo($editorData[0]["user_email"]);
                    $mail->addBcc("attila.erdei87@gmail.com");
                    $mail->setSubject('Gearoscope meghívás zenekari adminnak');
                    $mail->send($transport);

                    $this->view->headScript()->prependScript('
						$(document).ready(function(){							
                                                        parent.$("#subsubcategory").val("' . $subsubcategoryid . '");
							parent.$.fn.colorbox.close();
                                                        parent.$(".box-indent").append("<p>' . $editorData[0]["user_username"] . ' (Pending)</p>");
						});'
                    );
                } catch (Exception $e) {
                    $this->view->errorMessage = "Nincs ilyen regisztrált felhasználó";
                    $this->view->errorMessage = $e->getMessage();
                }
            }
        }
        $this->view->form = $form;
    }

    public function activateAction() {

        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('layout');

        $locale = Zend_Registry::get('Zend_Locale');

        $id = (int) $this->_getParam('editor');

        $code = $this->_getParam('code');

        $modelEditor = new Model_DbTable_BandEditors();
        $editor = $modelEditor->getEditor($id);

        if ($editor["code"] == $code) {
            $modelEditor->setActive($code);
            $this->_redirect($locale->getLanguage() . '/bands/successfull');
        } else {
            throw new Exception("Varátlan hiba történt!");
        }
    }
    
    public function successfullAction() {
        
    }

}

?>