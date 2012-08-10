<?php

class NewsController extends Zend_Controller_Action {

    public function init() {
        $this->view->addHelperPath(
                'ZendX/JQuery/View/Helper'
                , 'ZendX_JQuery_View_Helper');
    }

    public function indexAction() {

        $newsModel = new Model_DbTable_News();
        $news = $newsModel->getActive();

        $page = $this->_getParam('page', 1);
        $paginator = Zend_Paginator::factory($news);
        $paginator->setItemCountPerPage(7);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;
    }

    public function viewAction() {

        $post_id = $this->_request->getParam("id");

        $post = new Model_DbTable_Posts();
        $result = $post->getPost($post_id);

        $result = $post->getPost($post_id);
        $this->view->post = $result;
    }

    public function addAction() {

        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('admin');

        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_redirect('index/index');
        }

        $postForm = new Form_Post();
        //$postForm->setDecorators(array(array('ViewScript', array('viewScript' => 'posts/addform.phtml'))));

        if ($this->getRequest()->isPost()) {
            if ($postForm->isValid($this->getRequest()->getPost())) {

                $title = $postForm->getValue('title');
                $lead = $postForm->getValue('lead');
                $content = $postForm->getValue('content');
                $date = date("Y-m-d H:i:s", time());

                $newsModel = new Model_DbTable_News();
                $news_id = $newsModel->saveNews($title, $lead, $content, $date);

                $this->_redirect('news/view/id/' . $news_id);
            }
        }

        $this->view->postForm = $postForm;
    }

    public function editAction() {

        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('admin');

        $request = $this->getRequest();
        $newsid = (int) $request->getParam('id');

        $modelNews = new Model_DbTable_News();
        $news = $modelNews->getNews($news_id);

        $postForm = new Form_Post();
        $postForm->getElement('title')->setValue($posts[0]["title"]);
        $postForm->getElement('lead')->setValue($posts[0]["lead"]);
        $postForm->getElement('content')->setValue($posts[0]["content"]);

        if ($this->getRequest()->isPost()) {
            if ($postForm->isValid($request->getPost())) {

                $title = $postForm->getValue('title');
                $topic = $postForm->getValue('topic');
                $lead = $postForm->getValue('lead');
                $content = $postForm->getValue('content');

                $modelPosts->updateNews($newsid, $title, $topic, $lead, $content);

                $this->_redirect('/news/view/id/' . $postid);
            }
        } else {
            $result = $modelNews->getNews($newsid);
            $newsForm->populate($result);
        }
        $this->view->newsForm = $newsForm;
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

    public function collectAction() {

        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('admin');

        $baseUrl = $this->view->baseUrl();
        $locale = Zend_Registry::get('Zend_Locale');

        //$cache = Zend_Registry::get('cache');
        //Zend_Feed_Reader::setCache($cache);
        //Zend_Feed_Reader::useHttpConditionalGet();

        $feed = Zend_Feed_Reader::import('http://langologitarok.blog.hu/rss');

        $newsModel = new Model_DbTable_News();

        $theLastTime = $newsModel->getLastRowTime();

        foreach ($feed as $entry) {

            $getDescription = $entry->getDescription();
            $endPos = strpos($getDescription, '<div class="page-break">');
            $descriptionData = substr($getDescription, 0, $endPos);

            $getContent = $entry->getContent();
            $endPos = strpos($getContent, '<div class="page-break">');
            $contentData = substr($getContent, 0, $endPos);

            $edata = array(
                'title' => $entry->getTitle(),
                'guid' => $entry->getId(),
                'description' => $descriptionData,
                'dateModified' => $entry->getDateModified(),
                'authors' => $entry->getAuthors(),
                'link' => $entry->getLink(),
                'content' => $contentData
            );
            $data['entries'][] = $edata;
        }


        $feedReverse = array_reverse($data["entries"]);

        foreach ($feedReverse as $entry):
            $guid = $entry["guid"];
            $title = CMS_Validate_Charactertransform::normalToSpec($entry["title"]);
            $description = $entry["description"];
            $dateModified = $entry["dateModified"];
            $authors = $entry["authors"][0]["name"];
            $link = $entry["link"];
            $content = $entry["content"];

            $date = CMS_Validate_MakeTime::timeToStamp($dateModified);

            $active = "1";
            if ($date > $theLastTime["dateModified"]) {
                $news_id = $newsModel->addNews($guid, $active, $title, $description, $date, $authors, $link, $content);
            }

        endforeach;
    }

    public function deleteAction() {

        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->getHelper('layout')->disableLayout();

        $id = $this->_request->getParam('id');

        $modelNews = new Model_DbTable_News();
        $modelNews->deleteNews($id);

        $this->_redirect('/posts/adminlist?delete=1');
    }

}
