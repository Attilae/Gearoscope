<?php

class GearsController extends Zend_Controller_Action {

    public function init() {
        $this->view->addHelperPath(
                'ZendX/JQuery/View/Helper'
                , 'ZendX_JQuery_View_Helper');
        
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('images', 'html')
                ->initContext();
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

        $gearsModel = new Model_DbTable_Gears();
        $gears = $gearsModel->getItemsActive();

        $this->view->gears = $gears;
    }

    public function viewAction() {
        
        $baseUrl = $this->view->baseUrl();
        
        $gear_id = $this->_request->getParam("id");
        $this->view->gear_id = $gear_id;
        
        $this->view->headScript()->prependScript('
			$(document).ready(function(){                                                        
                        
                                $("#gallery-images").html("<div class=\"loading\"></div>");

                                $("#gallery-images").load(
                                                    "'.$baseUrl.'/hu/gears/images/format/html",
                                                    {"id": '.$gear_id.' }, 
                                                    function(response, status, xhr) {
                                                        if (status == "error") {
                                                          var msg = "Sorry but there was an error: ";
                                                          $("#gallery-images").html(msg + xhr.status + " " + xhr.statusText);
                                                        }
                                                     }
                                );
                                
                                $("#comments").html("<div class=\"loading\"></div>");

                                $("#comments").load(
                                                    "'.$baseUrl.'/hu/comments/gear/format/html",
                                                    {"id": '.$gear_id.' }, 
                                                    function(response, status, xhr) {
                                                        if (status == "error") {
                                                          var msg = "Sorry but there was an error: ";
                                                          $("#gallery-images").html(msg + xhr.status + " " + xhr.statusText);
                                                        }
                                                     }
                                );
                                
                                
                            });'
                );
        
        $this->view->headScript()->prependFile($baseUrl . '/public/skins/gearoscope/js/_gearoscope.js');
        

        $gearsModel = new Model_DbTable_Gears();
        $gear = $gearsModel->getGear($gear_id);

        $auth = Zend_Auth::getInstance();
        $identity = $auth->getIdentity();
        $user_id = $identity->user_id;
        
        if($gear["user_id"]==$user_id) {
            $this->view->editable = "1";
        }                
        
        $userModel = new Model_DbTable_User();
        $currentUser = $userModel->find($gear["user_id"])->current();
        $user = $currentUser->toArray();
        
        $this->view->user = $user;
        
        $this->view->gear = $gear;
        
        $commentsModel = new Model_DbTable_Comments();
        $request = $this->getRequest();
        $commentsForm = new Form_Comments();

        if ($this->getRequest()->isPost()) {
            if ($commentsForm->isValid($request->getPost())) {

                $auth = Zend_Auth::getInstance();
                $identity = $auth->getIdentity();
                $user_id = $identity->user_id;

                $description = $commentsForm->getValue('comment');

                $commentsModel->saveComment($gear_id, $user_id, $description);

                /*$comments = $result[0]["comments"];
                $comments = $comments + 1;

                $model = new Model_DbTable_Posts();
                $model->aggregateComments($post_id, $comments);*/

                $commentsForm->reset();

                $locale = Zend_Registry::get('Zend_Locale');
                
                $this->_redirect($locale . "/gears/view/id/" . $gear_id);
            };
        }
        $data = array('id' => $gear_id);
        $commentsForm->populate($data);
        $this->view->commentsForm = $commentsForm;                   
                 
    }

    public function listAction() {

        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('admin');

        $subsubcategoryid = $this->_request->getParam('subsubcategoryid');

        $modelCategories = new Model_DbTable_Gears();
        $items = $modelCategories->getItems($subsubcategoryid);

        $this->view->items = $items;

        $this->view->subsubcategoryid = $subsubcategoryid;
    }

    public function addAction() {

        $baseUrl = $this->view->baseUrl();

        $this->view->headScript()->prependScript('
			$(document).ready(function(){
				$("a.addsubsubcategory").colorbox({iframe:true,
					width:"800px",
				 	height:"220px"
				});
				$("#picture_upload").colorbox({
					iframe:true,
					width:"800px",
				 	height:"600px"				 	
				});
                                $(".info-tooltip").tooltip({
                                    track: true,
                                    delay: 0,
                                    fixPNG: true,
                                    showURL: false,
                                    showBody: " - ",
                                    top: -35,
                                    left: 5
                                });
			});'
        );

        /*
         * onClosed : function() {
          $("dd#uploader").hide();
          $("dd#uploaded").fadeIn();
          $("dd#uploaded").append("<img src=\"'.$baseUrl.'/public/uploads/gears/'.$_SESSION["uploader"]["thumbnail"].'\">");
          $("#photo").val("'.$_SESSION["uploader"]["photo"].'");
          $("#thumbnail").val("'.$_SESSION["uploader"]["thumbnail"].'");
          }
         */

        $this->view->headScript()->prependFile($baseUrl . "/public/skins/gearoscope/js/jquery.colorbox-min.js");

        $this->view->headLink()->appendStylesheet($baseUrl . "/public/skins/gearoscope/css/colorbox.css");

        $this->view->headScript()->prependFile($baseUrl . "/public/skins/gearoscope/js/ajax.js");
        
        $this->view->headScript()->prependFile($baseUrl . "/public/admin/js/jquery/jquery.dimensions.js");
        
        $this->view->headScript()->prependFile($baseUrl . "/public/admin/js/jquery/jquery.tooltip.js");               

        $form = new Form_Gear();
        $form->setDecorators(array(array('ViewScript', array('viewScript' => 'gears/form.phtml'))));

        $locale = Zend_Registry::get('Zend_Locale');
        $this->view->locale = $locale;

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $auth = Zend_Auth::getInstance();
                $identity = $auth->getIdentity();
                $user_id = $identity->user_id;
                $gear_name = $form->getValue('gear_name');
                $serial_number = $form->getValue('serial_number');
                $category = $form->getValue('category');
                $subcategory = $form->getValue('subcategory');
                $subsubcategory = $form->getValue('subsubcategory');
                $description = $form->getValue('description');                
                $photo = $form->getValue('photo');                
                $thumbnail = $form->getValue('thumbnail');
                $featured = "0";
                $active = "1";
                $create_date = time();
                $last_edit_date = time();

                /* if ($form->photo->isUploaded()) {
                  $adapter = $form->photo->getTransferAdapter();
                  $receivingOK = true;
                  foreach ($adapter->getFileInfo() as $file => $info) {
                  $extension = pathinfo($info['name'], PATHINFO_EXTENSION);
                  $photo_url = (int) CMS_Validate_Internim::getMicrotime() . '.' . $extension;
                  $adapter->addFilter('Rename', "uploads/gears/" . $photo_url, $file);
                  if (!$adapter->receive($file)) {
                  $receivingOK = false;
                  }
                  }
                  } */

                $gearsModel = new Model_DbTable_Gears();
                $gear_id = $gearsModel->addGear($user_id, $active, $gear_name, $serial_number, $category, $subcategory, $subsubcategory, $featured, $photo, $thumbnail, $create_date, $last_edit_date, $description);

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

                unset($_SESSION["uploader"]);

                $this->_redirect('/' . $locale . '/gears/view/id/' . $gear_id);
            }
        }

        $this->view->form = $form;
    }

    public function editAction() {

        $baseUrl = $this->view->baseUrl();
        
        $gear_id = $this->_request->getParam("id");

        $this->view->gear_id = $gear_id;
        
        $gearsModel = new Model_DbTable_Gears();
        $gear = $gearsModel->getGear($gear_id);
        
        $gearPicture = '<img id=\"picture_delete\" src=\"'.$baseUrl.'/public/uploads/gears/'.$gear["gear_thumbnail_url"].'\" />';                

        $this->view->headScript()->prependScript('
			$(document).ready(function(){                                                        
                        
                                $("#gallery-images").html("Loading...");

                                $("#gallery-images").load(
                                                    "'.$baseUrl.'/hu/gears/images/format/html",
                                                    {"id": '.$gear_id.' }, 
                                                    function(response, status, xhr) {
                                                        if (status == "error") {
                                                          var msg = "Sorry but there was an error: ";
                                                          $("#gallery-images").html(msg + xhr.status + " " + xhr.statusText);
                                                        }
                                                     }
                                );

                                $("#div-subcategory").fadeIn();
                                $("#div-subsubcategory").fadeIn();
				$("a.inline").colorbox({inline:true, width:"50%"});                                
                                var image = "'.$gear["gear_thumbnail_url"].'";
                                console.log(image);
                                if(image!="dummy_thumbnail.jpg") {
                                    //$("#picture_upload").fadeOut();                                                                
                                    $("dd#uploader").append("'.$gearPicture.'");                                
                                    /*$("#picture_delete").click(
                                        function() {                                            
                                                $("dd#uploader").html(\'Loading...\');
                                                $.ajax({
                                                    type: "POST",
                                                    url: "'.$baseUrl.'/hu/gears/imgdelete/id/'.$gear_id.'/url/'.$gear["gear_thumbnail_url"].'",
                                                    error: function(){                                             
                                                        $("dd#uploader").html(\'Error! Please try again later!\');
                                                    },
                                                    success: function(responseText){                                                                                                        
                                                        console.log("deleted");
                                                        $("dd#uploader").hide();
                                                        $("#photo").val("");
		
                                                    }
                                                });
                                                $.ajax({
                                                    type: "POST",
                                                    url: "'.$baseUrl.'/hu/gears/thumbnaildelete/id/'.$gear_id.'/url/'.$gear["gear_thumbnail_url"].'",
                                                    error: function(){
                                                        $("dd#uploader").html(\'Error! Please try again later!\');
                                                    },
                                                    success: function(responseText){
                                                        console.log("deleted");
                                                        $("dd#uploader").hide();
                                                        $("#picture_upload").fadeIn();
                                                        $("#thumbnail").val(""); 
                                                    }
                                                });
                                        }
                                    );*/
                                }
                                
				$("#picture_upload").colorbox({
					iframe:true,
					width:"800px",
				 	height:"600px",
				 	onClosed : function() {
                                                uploadedThumbnail = $("#thumbnail").val();;
                                                if(uploadedThumbnail!="") {
                                                    randomnumber=Math.floor(Math.random()*1100);
                                                    $("dd#uploader").hide();
                                                    $("dd#uploaded").fadeIn();
                                                    $("dd#uploaded").html("<img src=\"'.$baseUrl.'/public/uploads/gears/"+uploadedThumbnail+"?key="+randomnumber+"\">"); 		
                                                }    
				 	}
				});
                                
                                $("#addgallery").colorbox({
					iframe:true,
					width:"800px",
				 	height:"600px",
                                        onClosed : function() {
                                                $("#gallery-images").html("Loading...");
                                                $("#gallery-images").load(
                                                    "'.$baseUrl.'/hu/gears/images/format/html",
                                                    {"id": '.$gear_id.' }, 
                                                    function(response, status, xhr) {
                                                        if (status == "error") {
                                                          var msg = "Sorry but there was an error: ";
                                                          $("#gallery-images").html(msg + xhr.status + " " + xhr.statusText);
                                                        }
                                                     }
                                                ); 
                                               }
                                });
                           });'
        );

        $this->view->headScript()->prependFile($baseUrl . "/public/skins/gearoscope/js/jquery.colorbox-min.js");

        $this->view->headLink()->appendStylesheet($baseUrl . "/public/skins/gearoscope/css/colorbox.css");

        $this->view->headScript()->prependFile($baseUrl . "/public/skins/gearoscope/js/ajax.js");    
        
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getIdentity();
        $user_id = $identity->user_id;
        
        if($gear["user_id"]!=$user_id) {
            throw new Exception("Váratlan hiba történt");
        }

        $form = new Form_Gear();
        $form->setDecorators(array(array('ViewScript', array('viewScript' => 'gears/form.phtml'))));

        $form->getElement('gear_name')->setValue($gear["gear_name"]);
        $form->getElement('serial_number')->setValue($gear["serial_number"]);
        $form->getElement('category')->setValue($gear["gears_category_id"]);
        
        $form->getElement('description')->setValue($gear["description"]);
        
        $form->getElement('photo')->setValue($gear["gear_photo_url"]);
        $form->getElement('thumbnail')->setValue($gear["gear_thumbnail_url"]);

        $subcategoryFormElement = $form->getElement('subcategory');
        $gearsSubcategoriesModel = new Model_DbTable_GearsSubcategories();
        $subcategories = $gearsSubcategoriesModel->findByCategory($gear["gears_category_id"]);
        foreach ($subcategories as $subcat):
            $subcategoryFormElement->addMultiOptions(array($subcat["gears_subcategory_id"] => $subcat["subcategory"]));
        endforeach;
        $subcategoryFormElement->setValue($gear["gears_subcategory_id"]);

        $subsubcategoryFormElement = $form->getElement('subsubcategory');
        $gearsSubsubcategoriesModel = new Model_DbTable_GearsSubsubcategories();
        $subsubcategories = $gearsSubsubcategoriesModel->findByCategory($gear["gears_subcategory_id"]);
        foreach ($subsubcategories as $subsubcat):            
            $subsubcategoryFormElement->addMultiOptions(array($subsubcat["gears_subsubcategory_id"] => $subsubcat["subsubcategory"]));
        endforeach;
        $subsubcategoryFormElement->setValue($gear["gears_subsubcategory_id"]);

        
        
        $locale = Zend_Registry::get('Zend_Locale');

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $auth = Zend_Auth::getInstance();
                $identity = $auth->getIdentity();
                $user_id = $identity->user_id;
                $gear_name = $form->getValue('gear_name');
                $serial_number = $form->getValue('serial_number');
                $category = $form->getValue('category');
                $subcategory = $form->getValue('subcategory');
                $subsubcategory = $form->getValue('subsubcategory');
                $description = $form->getValue('description');
                $photo = $form->getValue('photo');
                $thumbnail = $form->getValue('thumbnail');
                $featured = "0";
                $active = "1";
                $last_edit_date = time();

                /* if ($form->photo->isUploaded()) {
                  $adapter = $form->photo->getTransferAdapter();
                  $receivingOK = true;
                  foreach ($adapter->getFileInfo() as $file => $info) {
                  $extension = pathinfo($info['name'], PATHINFO_EXTENSION);
                  $photo_url = (int) CMS_Validate_Internim::getMicrotime() . '.' . $extension;
                  $adapter->addFilter('Rename', "uploads/gears/" . $photo_url, $file);
                  if (!$adapter->receive($file)) {
                  $receivingOK = false;
                  }
                  }
                  } */

                $gearsModel = new Model_DbTable_Gears();
                $gearsModel->editGear($gear_id, $gear_name, $serial_number, $subsubcategory, $featured, $photo, $thumbnail, $last_edit_date, $description);

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

                $this->_redirect('/' . $locale . '/gears/view/id/' . $gear_id);
            }
        }

        $this->view->form = $form;
    }
    
    public function imagesAction() {
        
        $gear_id = $this->_request->getParam("id");
        
        $imagesModel = new Model_DbTable_Images();
        $images = $imagesModel->findByGear($gear_id);
        
        $this->view->images = $images;
    }
    
    
    public function pictureAction() {

        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        if (isset($_POST["upload"])) {
            //Get the file information
            $userfile_name = $_FILES["image"]["name"];
            $userfile_tmp = $_FILES["image"]["tmp_name"];
            $userfile_size = $_FILES["image"]["size"];
            $filename = basename($_FILES["image"]["name"]);
            $file_ext = substr($filename, strrpos($filename, ".") + 1);

            //Only process if the file is a JPG and below the allowed limit
            if ((!empty($_FILES["image"])) && ($_FILES["image"]["error"] == 0)) {
                if (($file_ext != "jpg") && ($userfile_size > $max_file)) {
                    $error = "ONLY jpeg images under 1MB are accepted for upload";
                }
            } else {
                $error = "Select a jpeg image for upload";
            }
            //Everything is ok, so we can upload the image.
            if (strlen($error) == 0) {

                if (isset($_FILES["image"]["name"])) {

                    move_uploaded_file($userfile_tmp, $large_image_location);
                    chmod($large_image_location, 0777);

                    $width = getWidth($large_image_location);
                    $height = getHeight($large_image_location);
                    //Scale the image if it is greater than the width set above
                    if ($width > $max_width) {
                        $scale = $max_width / $width;
                        $uploaded = resizeImage($large_image_location, $width, $height, $scale);
                    } else {
                        $scale = 1;
                        $uploaded = resizeImage($large_image_location, $width, $height, $scale);
                    }
                    //Delete the thumbnail file so the user can create a new one
                    if (file_exists($thumb_image_location)) {
                        unlink($thumb_image_location);
                    }
                }
                //Refresh the page to show the new uploaded image
                header("location:" . $_SERVER["PHP_SELF"]);
                exit();
            }
        }
    }

    public function thumbnailAction() {
        if (isset($_POST["upload_thumbnail"])) {
            //Get the new coordinates to crop the image.  
            $x1 = $_POST["x1"];
            $y1 = $_POST["y1"];
            $x2 = $_POST["x2"]; // not really required  
            $y2 = $_POST["y2"]; // not really required  
            $w = $_POST["w"];
            $h = $_POST["h"];
            //Scale the image to the 100px by 100px  
            $scale = 100 / $w;
            $cropped = resizeThumbnailImage($thumb_image_location, $large_image_location, $w, $h, $x1, $y1, $scale);
            //Reload the page again to view the thumbnail  
            header("location:" . $_SERVER["PHP_SELF"]);
            exit();
        }
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

        $gearsModel = new Model_DbTable_Gears();
        $gears = $gearsModel->getByUser($user_id);

        $this->view->gears = $gears;
    }
    
    public function imgdeleteAction() {
        
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        
        $gear_id = $this->_request->getParam("id");

        print $gear_id;
        
        $picture_url = $this->_request->getParam("url");                
        
        $baseUrl = $this->view->baseUrl();
        
        if($picture_url!="dummy.jpg") {
            $unlink = unlink($baseUrl . '/public/uploads/gears/' . $picture_url);
        }
                
        $modelGears = new Model_DbTable_Gears();
        $modelGears->deleteImage($gear_id);
        
    }
    
    public function thumbnaildeleteAction() {
        
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        
        $gear_id = $this->_request->getParam("id");
        
        $picture_url = $this->_request->getParam("url");
        
        $baseUrl = $this->view->baseUrl();
        
        if($picture_url!="dummy_thumnail.jpg") {
            $unlink = unlink($baseUrl . '/public/uploads/gears/' . $picture_url);
        }
        
        
        $modelGears = new Model_DbTable_Gears();        
        $modelGears->deleteThumbnail($gear_id);
        
    }

}
