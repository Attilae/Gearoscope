<?php

class UploadifyController extends Zend_Controller_Action {

    public function init() {
        $this->view->addHelperPath(
                'ZendX/JQuery/View/Helper'
                , 'ZendX_JQuery_View_Helper');
    }

    public function indexAction() {
        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('iframe');

        $baseUrl = $this->view->baseUrl();
        $locale = Zend_Registry::get('Zend_Locale');

        $gear = $this->_request->getParam('gear');

        $auth = Zend_Auth::getInstance();
        $identity = $auth->getIdentity();
        
        $this->view->headScript()->prependScript('
			$(function() {
                            $("#file_upload_1").uploadify({
                                "formData"      : {"gear" : ' . $gear . ', "user" : ' . $identity->user_id .'},
                                "height"        : 30,
                                "swf"           : "' . $this->view->baseUrl() . '/public/uploadify/uploadify.swf",
                                "uploader"      : "'.$baseUrl.'/'.$locale.'/uploadify/uploadify",
                                "width"         : 120
                            });
                        });'
        );


        $this->view->headScript()->prependFile($baseUrl . "/public/uploadify/jquery.uploadify-3.1.min.js");

        $this->view->headLink()->appendStylesheet($baseUrl . "/public/uploadify/uploadify.css");
    }
    
    public function uploadifyAction() {
        
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        
        $targetFolder = '/_gearoscope/public/uploads/gears/'; // Relative to the root

        if (!empty($_FILES)) {
            
            $modelImages = new Model_DbTable_Images();
            $image = $modelImages->addImage('gallery_' . $_POST["gear"] . "_" . $_POST["user"] . "_" . $_FILES['Filedata']['name'], $_POST["gear"]);
            
            $tempFile = $_FILES['Filedata']['tmp_name'];
            $targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
            
            // Validate the file type
            $fileTypes = array('jpg', 'JPG', 'jpeg', 'JPEG', 'gif', 'GIF', 'png', 'PNG'); // File extensions
            $fileParts = pathinfo($_FILES['Filedata']['name']);
            
            $targetFile = rtrim($targetPath, '/') . '/gallery_' . $_POST["gear"] . "_" . $_POST["user"] . "_" . $_FILES['Filedata']['name'];                                    

            if (in_array($fileParts['extension'], $fileTypes)) {
                move_uploaded_file($tempFile, $targetFile);
                echo '1';
            } else {
                echo 'Invalid file type.';
            }
}
    }

}

?>
