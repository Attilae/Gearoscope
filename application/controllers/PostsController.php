<?php

class PostsController extends Zend_Controller_Action {

    public function init() {
        $this->_helper->ajaxContext->addActionContext('view', 'html')
                ->initContext();
    }

    public function indexAction() {

        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('layout');

        $posts = new Model_DbTable_Posts();
        $result = $posts->getFirstThree();

        $this->view->firstthree = $result;

        $priors = $posts->getPriorPost();
        $this->view->priors = $priors;

        $modelAct = new Model_DbTable_Activity();
        $activities = $modelAct->getActivities();

        $this->view->activities = $activities;
//        $page = $this->_getParam('page', 1);
//        $paginator = Zend_Paginator::factory($result);
//        $paginator->setItemCountPerPage(4);
//        $paginator->setCurrentPageNumber($page);
//        $this->view->paginator = $paginator;
    }

    public function topicAction() {

        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('layout');

        $title = $this->_request->getParam('title');

        $posts = new Model_DbTable_Posts();

        $result = $posts->getPostsByTopic($title);

        $page = $this->_getParam('page', 1);
        $paginator = Zend_Paginator::factory($result);
        $paginator->setItemCountPerPage(7);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;
    }

    public function viewAction() {

        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('layout');

        // action body
        $post_id = $this->_request->getParam("id");

        $post = new Model_DbTable_Posts();
        $result = $post->getPost($post_id);

        if ($result[0]["post_id"] != "145" AND $result[0]["postactive"] != "1" AND $_SESSION["Zend_Auth"]["storage"]->user_id != $result[0]["user_id"] AND $_SESSION["Zend_Auth"]["storage"]->user_id != "11" AND $_SESSION["Zend_Auth"]["storage"]->user_id != "13") {
            $this->_redirect('/');
        }

        $commentsObj = new Model_DbTable_Comments();
        $request = $this->getRequest();
        $commentsForm = new Form_Comments();

        if ($this->getRequest()->isPost()) {
            if ($commentsForm->isValid($request->getPost())) {

                $auth = Zend_Auth::getInstance();
                $identity = $auth->getIdentity();
                $username = $identity->username;
                $user_id = $identity->user_id;
                $email = $identity->email;

                $description = $commentsForm->getValue('comment');

                $model = new Model_DbTable_Comments();
                $model->saveComment($username, $email, $description, $post_id, $user_id);

                $comments = $result[0]["comments"];
                $comments = $comments + 1;

                $model = new Model_DbTable_Posts();
                $model->aggregateComments($post_id, $comments);

                $commentsForm->reset();

                $this->_redirect("/posts/view/id/" . $post_id);
            };
        }
        $data = array('id' => $post_id);
        $commentsForm->populate($data);
        $this->view->commentsForm = $commentsForm;

        $comments = $commentsObj->getComments($post_id);
        $this->view->comments = $comments;
        $page = $this->_getParam('page', 1);
        $paginator = Zend_Paginator::factory($comments);
        $paginator->setItemCountPerPage(4);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;

        $morePostsByMobiler = $post->getMorePostsByMobiler($result[0]["user_id"], $post_id);
        $this->view->morePostsByMobiler = $morePostsByMobiler;

        $morePostsByTopic = $post->getPostsByTopic($result[0]["topic"]);
        $this->view->morePostsByMobiler = $morePostsByTopic;

        $tagsObj = new Model_DbTable_Tags();
        $tags = $tagsObj->findByPost($post_id);
        $this->view->tags = $tags;

//        $modelPicasa = new Model_Picasa();
//        $picasaArray = $modelPicasa->generatePics($result[0]["gallery"]);
//        $this->view->picasaArray = $picasaArray;

        $result = $post->getPost($post_id);
        $this->view->post = $result;

        $this->view->edit = '/posts/edit/id/' . $postid;
    }

    public function addAction() {

        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('layout');

        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_redirect('index/index');
        }

        $postForm = new Form_Post();
        //$postForm->setDecorators(array(array('ViewScript', array('viewScript' => 'posts/addform.phtml'))));

        if (isset($_SESSION["presave"])) {
            $postForm->getElement('title')->setValue($_SESSION["presave"]["title"]);
            $postForm->getElement('lead')->setValue($_SESSION["presave"]["lead"]);
            $postForm->getElement('video')->setValue($_SESSION["presave"]["video"]);
            $postForm->getElement('topic')->setValue($_SESSION["presave"]["topic"]);
            $postForm->getElement('tags')->setValue($_SESSION["presave"]["tags"]);
//            $postForm->getElement('gallery')->setValue($_SESSION["presave"]["gallery"]);
            $postForm->getElement('content')->setValue($_SESSION["presave"]["content"]);
            if ($_SESSION["presave"]["photo"]) {
//                $photoPreview = $postForm->createElement('image', 'photo_preview');
//                $photoPreview->setLabel('Preview Photo: ');
//                $photoPreview->setAttrib('style', 'width:200px;height:auto;cursor: default;');
////                $photoPreview->setAttrib('onclick', 'window.location="/posts/delpic"');
//                $photoPreview->setImage("/../public/uploads/posts/" . $_SESSION["presave"]["photo"]);
//                $postForm->addElement($photoPreview);
//                $photoPreview->setOrder("2");
//                $postForm->removeElement('photo');
                $postForm->getElement('photo')->setRequired(false);
            }
        }

        if ($this->getRequest()->isPost()) {
            if (isset($_POST["presave"])) {

                $photoFromSession = $_SESSION["presave"]["photo"];

                $_SESSION["presave"] = $_POST;
                if ($postForm->photo->isUploaded()) {
//                    $postForm->photo->receive();
//                    $photo_url = basename($postForm->photo->getFileName());
                    $adapter = $postForm->photo->getTransferAdapter();
                    $receivingOK = true;
                    foreach ($adapter->getFileInfo() as $file => $info) {
                        $extension = pathinfo($info['name'], PATHINFO_EXTENSION);
                        $photo_url = (int) CMS_Validate_Internim::getMicrotime() . '.' . $extension;
                        $adapter->addFilter('Rename', "uploads/posts/" . $photo_url, $file);
                        if (!$adapter->receive($file)) {
                            $receivingOK = false;
                        }
                    }
                    $_SESSION["presave"]["photo"] = $photo_url;
                } else {
                    $_SESSION["presave"]["photo"] = $photoFromSession;
                }
                $_SESSION["presave"]["date"] = date("Y-m-d", time());
                $this->_redirect('/posts/presave');
            } elseif (isset($_POST["delete"])) {
                unlink("/public/uploads/posts/" . $_SESSION["presave"]["photo"]);
                unset($_SESSION["presave"]);
                $this->_redirect("/posts/add");
            } elseif (isset($_POST["submit"])) {
                if ($postForm->isValid($this->getRequest()->getPost())) {
                    $auth = Zend_Auth::getInstance();
                    $identity = $auth->getIdentity();
                    $user_id = $identity->user_id;
                    $title = $postForm->getValue('title');
                    $topic = $postForm->getValue('topic');
                    $lead = $postForm->getValue('lead');
                    $content = $postForm->getValue('content');
                    $video = $postForm->getValue('video');
//                    $gallery = $postForm->getValue('gallery');
                    $date = date("Y-m-d H:i:s", time());
                    $tags = nl2br($postForm->getValue('tags'));
                    if ($postForm->photo->isUploaded()) {
//                        $postForm->photo->receive();
//                        $photo_url = basename($postForm->photo->getFileName());

                        $adapter = $postForm->photo->getTransferAdapter();
                        $receivingOK = true;
                        foreach ($adapter->getFileInfo() as $file => $info) {
                            $extension = pathinfo($info['name'], PATHINFO_EXTENSION);
                            $photo_url = (int) CMS_Validate_Internim::getMicrotime() . '.' . $extension;
                            $adapter->addFilter('Rename', "uploads/posts/" . $photo_url, $file);
                            if (!$adapter->receive($file)) {
                                $receivingOK = false;
                            }
                        }
                    } else {
                        $photo_url = $_SESSION["presave"]["photo"];
                    }

                    $postModel = new Model_DbTable_Posts();
                    $post_id = $postModel->savePost($user_id, $title, $topic, $lead, $content, $photo_url, $video,
//                            $gallery,
                                    $date);

                    $tagsArray = explode(" ", $tags);
                    //die(print_r($tagsArray));
                    $modelTag = new Model_DbTable_Tags();
                    for ($i = 0; $i <= count($tagsArray); $i++) {
                        $tag = strip_tags(trim($tagsArray[$i]));
                        if ($tag != "") {
                            $modelTag->addTag($post_id, $tag);
                        }
                    }

                    unset($_SESSION["presave"]);

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
                    $mail->addBcc("egri.denes@carpedm.hu");
                    $mail->addBcc("gakocsis@gmail.com");
                    $mail->addBcc("kastner.peter88@gmail.com");
                    $mail->setSubject('Samsung Mob!lers új bejegyzés');
                    $mail->send();

                    $this->_redirect('posts/view/id/' . $post_id);
                } else {
//                    print_r($postForm->getErrorMessages());
//                    print_r($postForm->getErrors());
                }
            }
        }

        $modelTag = new Model_DbTable_Tags();
        $tags = $modelTag->findAll();

        $this->view->tags = $tags;
        $this->view->postForm = $postForm;
    }

    public function editAction() {

        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('layout');

        $request = $this->getRequest();
        $postid = (int) $request->getParam('id');

        $modelPosts = new Model_DbTable_Posts();
        $posts = $modelPosts->getPost($postid);

        if ($posts[0]["user_id"] != $_SESSION["Zend_Auth"]["storage"]->user_id AND $_SESSION["Zend_Auth"]["storage"]->user_id != "11" AND $_SESSION["Zend_Auth"]["storage"]->user_id != "13") {
            $this->_redirect('posts/view/id/' . $postid);
        }

        $postForm = new Form_Post();
        $postForm->getElement('title')->setValue($posts[0]["title"]);
        $postForm->getElement('photo')->setRequired(false);
        $postForm->getElement('topic')->setValue($posts[0]["topic"]);
        $postForm->getElement('lead')->setValue($posts[0]["lead"]);
//        $postForm->getElement('gallery')->setValue($posts[0]["gallery"]);
        $postForm->getElement('video')->setValue($posts[0]["video"]);
        $postForm->getElement('content')->setValue($posts[0]["content"]);

        $modelTag = new Model_DbTable_Tags();
        $tags = $modelTag->findByPost($postid);
        for ($i = 0; $i <= count($tags); $i++) {
            $tagsData .= $tags[$i]["tag"] . " ";
        }
        //$_SESSION["mobilers"]["tagsData"] = $tagsData;
        $postForm->getElement('tags')->setValue($tagsData);

        if ($this->getRequest()->isPost()) {
            if (isset($_POST["presave"])) {
                $_SESSION["presave"] = $_POST;
                if ($postForm->photo->isUploaded()) {
//                    $postForm->photo->receive();
//                    $photo_url = basename($postForm->photo->getFileName());
                    $adapter = $postForm->photo->getTransferAdapter();
                    $receivingOK = true;
                    foreach ($adapter->getFileInfo() as $file => $info) {
                        $extension = pathinfo($info['name'], PATHINFO_EXTENSION);
                        $photo_url = (int) CMS_Validate_Internim::getMicrotime() . '.' . $extension;
                        $adapter->addFilter('Rename', "uploads/posts/" . $photo_url, $file);
                        if (!$adapter->receive($file)) {
                            $receivingOK = false;
                        }
                    }
                } else {
                    $photo_url = $posts[0]["photo"];
                }
                $_SESSION["presave"]["photo"] = $photo_url;
                $_SESSION["presave"]["date"] = date("Y-m-d", time());
                $_SESSION["presave"]["post_id"] = $postid;
                $this->_redirect('/posts/presave');
            } elseif (isset($_POST["delete"])) {
                unlink("/public/uploads/posts/" . $_SESSION["presave"]["photo"]);
                unset($_SESSION["presave"]);
                $this->_redirect("/posts/add");
            } elseif (isset($_POST["submit"])) {
                if ($postForm->isValid($request->getPost())) {

                    $title = $postForm->getValue('title');
                    $topic = $postForm->getValue('topic');
                    $lead = $postForm->getValue('lead');
                    $content = $postForm->getValue('content');
                    $video = $postForm->getValue('video');
//                $gallery = $postForm->getValue('gallery');
                    $tags = $postForm->getValue('tags');

                    $modelPosts->updatePost($postid, $title, $topic, $lead, $content,
                            $video
//                        $gallery
                    );

                    if ($postForm->photo->isUploaded()) {
//                        $postForm->photo->receive();
//                        $photo_url = basename($postForm->photo->getFileName());
                        $adapter = $postForm->photo->getTransferAdapter();
                        $receivingOK = true;
                        foreach ($adapter->getFileInfo() as $file => $info) {
                            $extension = pathinfo($info['name'], PATHINFO_EXTENSION);
                            $photo_url = (int) CMS_Validate_Internim::getMicrotime() . '.' . $extension;
                            $adapter->addFilter('Rename', "uploads/posts/" . $photo_url, $file);
                            if (!$adapter->receive($file)) {
                                $receivingOK = false;
                            }
                        }
                        $modelPosts->updatePhoto($postid, $photo_url);
                    }

                    $tagsArray = $modelTag->findByPost($postid);

                    for ($i = 0; $i <= count($tagsArray); $i++) {
                        $tag = strip_tags(trim($tagsArray[$i]["tag"]));
                        if ($tag != "") {
                            $modelTag->deleteTag($tag, $postid);
                        }
                    }

                    $tagsArray = array();
                    $tagsArray = explode(" ", $tags);
                    for ($i = 0; $i <= count($tagsArray); $i++) {
                        $tag = strip_tags(trim($tagsArray[$i]));
                        if ($tag != "") {
                            $modelTag->addTag($postid, $tag);
                        }
                    }

                    $this->_redirect('/posts/view/id/' . $postid);
                }
            }
        } else {
            $result = $modelPosts->getPost($postid);
            $postForm->populate($result);
        }
        $this->view->postForm = $postForm;
    }

    public function admineditAction() {

        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('admin');

        $request = $this->getRequest();
        $postid = (int) $request->getParam('id');

        $modelPosts = new Model_DbTable_Posts();
        $posts = $modelPosts->getPost($postid);

        $postForm = new Form_Post();
        $postForm->removeElement('presave');
        $postForm->removeElement('delete');
        $postForm->getElement('title')->setValue($posts[0]["title"]);
        $postForm->getElement('photo')->setRequired(false);
        $postForm->getElement('topic')->setValue($posts[0]["topic"]);
        $postForm->getElement('lead')->setValue($posts[0]["lead"]);
//        $postForm->getElement('gallery')->setValue($posts[0]["gallery"]);
        $postForm->getElement('video')->setValue($posts[0]["video"]);
        $postForm->getElement('content')->setValue($posts[0]["content"]);

        $modelTag = new Model_DbTable_Tags();
        $tags = $modelTag->findByPost($postid);
        for ($i = 0; $i <= count($tags); $i++) {
            $tagsData .= $tags[$i]["tag"] . " ";
        }
        //$_SESSION["mobilers"]["tagsData"] = $tagsData;
        $postForm->getElement('tags')->setValue($tagsData);

        if ($this->getRequest()->isPost()) {
            if ($postForm->isValid($request->getPost())) {

                $title = $postForm->getValue('title');
                    $topic = $postForm->getValue('topic');
                    $lead = $postForm->getValue('lead');
                    $content = $postForm->getValue('content');
                    $video = $postForm->getValue('video');
//                $gallery = $postForm->getValue('gallery');
                    $tags = $postForm->getValue('tags');

                    $modelPosts->updatePost($postid, $title, $topic, $lead, $content,
                            $video
//                        $gallery
                    );

                    if ($postForm->photo->isUploaded()) {
//                        $postForm->photo->receive();
//                        $photo_url = basename($postForm->photo->getFileName());
                        $adapter = $postForm->photo->getTransferAdapter();
                        $receivingOK = true;
                        foreach ($adapter->getFileInfo() as $file => $info) {
                            $extension = pathinfo($info['name'], PATHINFO_EXTENSION);
                            $photo_url = (int) CMS_Validate_Internim::getMicrotime() . '.' . $extension;
                            $adapter->addFilter('Rename', "uploads/posts/" . $photo_url, $file);
                            if (!$adapter->receive($file)) {
                                $receivingOK = false;
                            }
                        }
                        $modelPosts->updatePhoto($postid, $photo_url);
                    }

                    $tagsArray = $modelTag->findByPost($postid);

                    for ($i = 0; $i <= count($tagsArray); $i++) {
                        $tag = strip_tags(trim($tagsArray[$i]["tag"]));
                        if ($tag != "") {
                            $modelTag->deleteTag($tag, $postid);
                        }
                    }

                    $tagsArray = array();
                    $tagsArray = explode(" ", $tags);
                    for ($i = 0; $i <= count($tagsArray); $i++) {
                        $tag = strip_tags(trim($tagsArray[$i]));
                        if ($tag != "") {
                            $modelTag->addTag($postid, $tag);
                        }
                    }

                    //$this->_redirect('/posts/view/id/' . $postid);

                $this->_redirect('/posts/adminlist');
            }
        } else {
            $result = $modelPosts->getPost($postid);
            $postForm->populate($result);
        }
        $this->view->postForm = $postForm;
    }

    public function presaveAction() {
        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('layout');

        $this->view->date = $_SESSION["presave"]["date"];
        $this->view->post = $_SESSION["presave"];
        $this->view->photo = $_SESSION["presave"]["photo"];

        if (strlen(strstr($_SERVER["HTTP_REFERER"], "edit")) > 0) {
            $this->view->editLink = "edit/id/" . $_SESSION["presave"]["post_id"];
        } else {
            $this->view->editLink = "add";
        }
    }

    public function delpicAction() {
        unlink("/public/uploads/posts/" . $_SESSION["presave"]["photo"]);
        unset($_SESSION["presave"]["photo"]);
        $this->_redirect("/posts/add");
    }

    public function picasaAction() {

        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->getHelper('layout')->disableLayout();

        $gTitle = "KennedyMeadows"; // title of your gallary, if empty it will show: "your nickname' Photo Gallary"
        $uName = "111907009595057957530";  // your picasaweb user name
        /*
          The following values are valid for the thumbsize and imgmax query parameters and are embeddable on a webpage. These images are available as both cropped(c) and uncropped(u) sizes by appending c or u to the size. As an example, to retrieve a 72 pixel image that is cropped, you would specify 72c, while to retrieve the uncropped image, you would specify 72u for the thumbsize or imgmax query parameter values.
         */
        $tSize = "72c"; // thumbnail size can be 32, 48, 64, 72, 144, 160. cropt (c) and uncropt (u)
        $maxSize = "720u";

        if (isset($_POST['aID'])) {
            $aID = $_POST['aID']; // let's put album id here so it is easie to use later
            $file = file_get_contents('http://picasaweb.google.com/data/feed/api/user/' . $uName . '/albumid/' . $aID . '?kind=photo&access=public&thumbsize=72c&imgmax=' . $maxSize); // get the contents of the album
            $xml = new SimpleXMLElement($file); // convert feed into simplexml object
            $xml->registerXPathNamespace('media', 'http://search.yahoo.com/mrss/'); // define namespace media
            foreach ($xml->entry as $feed) { // go over the pictures
                $group = $feed->xpath('./media:group/media:thumbnail'); // let's find thumbnail tag
                $description = $feed->xpath('./media:group/media:description');
                if (str_word_count($description[0]) > 0) { // if picture has description, we'll use it as title
                    $description = $feed->title . ": " . $description[0]; // file name appended by image captioning
                } else {
                    $description = $feed->title; // if not will use file name as title
                }
                $a = $group[0]->attributes(); // now we need to get attributes of thumbnail tag, so we can extract the thumb link
                $b = $feed->content->attributes(); // now we convert "content" attributes into array
                echo '<a rel="' . $aID . '" href="' . $b['src'] . '" title="' . $description . '"><img src="' . $a['url'] . '" alt="' . $feed->title . '" width="' . $a['width'] . '" height="' . $a['height'] . '"/></a>';
            }
        } else {
            echo 'Error! Please provide album id.';
        }
    }

    public function listAction() {
        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('layout');

        $modelPosts = new Model_DbTable_Posts();
        $posts = $modelPosts->getPostsByMobilerForEdit($_SESSION["Zend_Auth"]["storage"]->user_id);



        if ($_SESSION["Zend_Auth"]["storage"]->user_id == "11" OR $_SESSION["Zend_Auth"]["storage"]->user_id == "13") {
            $postsAdmin = $modelPosts->getPosts();
            $this->view->postsAdmin = $postsAdmin;
        }

        $this->view->posts = $posts;
    }

    public function adminlistAction() {

        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('admin');

        $modelPosts = new Model_DbTable_Posts();
        $posts = $modelPosts->getPostsActive();

        $this->view->posts = $posts;
    }

    public function adminlistmoderatedAction() {

        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('admin');

        $modelPosts = new Model_DbTable_Posts();
        $posts = $modelPosts->getPostsNonActive();

        $this->view->posts = $posts;
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

    public function seteditedAction() {
        $id = $this->_request->getParam("id");

        $modelPosts = new Model_DbTable_Posts();
        $modelPosts->setEdited($id);

        $post = $modelPosts->getPost($id);

        $bodyText = "<body>";
        $bodyText .= "<p>Moderáltak egy bejegyzést a Samsung Mobilers oldalon.</p>";
        $bodyText .= "<p>Beküldő: " . $post[0]["username"] . "</p>";
        $bodyText .= "<p>Cím: " . $post[0]["title"] . "</p>";
        $bodyText .= "<p>Üdv,<br/>Samsung Mob!lers csapat</p>";
        $bodyText .= "</body>";

        $mail = new Zend_Mail('UTF-8');
        $mail->setBodyText($bodyText);
        $mail->setBodyHtml($bodyText);
        $mail->setHeaderEncoding(Zend_Mime::ENCODING_BASE64);
        $mail->setFrom('noreply@mobilers.samsung.hu', 'mobilers.samsung.hu');
        $mail->addTo("erdei.attila@carpedm.hu");
        $mail->addBcc("egri.denes@carpedm.hu");
//        $mail->addBcc("gakocsis@gmail.com");
//        $mail->addBcc("kastner.peter88@gmail.com");
        $mail->setSubject('Samsung Mob!lers moderált bejegyzés');
        $mail->send();

        $this->_redirect("/posts/list");
    }

    public function setuneditedAction() {
        $id = $this->_request->getParam("id");

        $modelPosts = new Model_DbTable_Posts();
        $modelPosts->setUnedited($id);

        $post = $modelPosts->getPost($id);

        $bodyText = "<body>";
        $bodyText .= "<p>Visszavonták a moderált státuszt egy bejegyzésen a Samsung Mobilers oldalon.</p>";
        $bodyText .= "<p>Beküldő: " . $post[0]["username"] . "</p>";
        $bodyText .= "<p>Cím: " . $post[0]["title"] . "</p>";
        $bodyText .= "<p>Üdv,<br/>Samsung Mob!lers csapat</p>";
        $bodyText .= "</body>";

        $mail = new Zend_Mail('UTF-8');
        $mail->setBodyText($bodyText);
        $mail->setBodyHtml($bodyText);
        $mail->setHeaderEncoding(Zend_Mime::ENCODING_BASE64);
        $mail->setFrom('noreply@mobilers.samsung.hu', 'mobilers.samsung.hu');
        $mail->addTo("erdei.attila@carpedm.hu");
        $mail->addBcc("egri.denes@carpedm.hu");
//        $mail->addBcc("gakocsis@gmail.com");
//        $mail->addBcc("kastner.peter88@gmail.com");
        $mail->setSubject('Samsung Mob!lers már nem moderált bejegyzés');
        $mail->send();

        $this->_redirect("/posts/list");
    }

    public function setpriorAction() {
        $id = $this->_request->getParam("id");

        $modelUser = new Model_DbTable_Posts();
        $modelUser->setPrior($id);

        $this->_redirect("/posts/adminlist?priored=1");
    }

    public function setsimpleAction() {
        $id = $this->_request->getParam("id");

        $modelUser = new Model_DbTable_Posts();
        $modelUser->setsimple($id);

        $this->_redirect("/posts/adminlist?simple=1");
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

        $this->_redirect('/posts/adminlist?delete=1');
    }

}
