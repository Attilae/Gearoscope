<?php

class VideoController extends Zend_Controller_Action {

    public function indexAction() {

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
   
    public function deleteAction() {
    	
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->getHelper('layout')->disableLayout();

        $id = $this->_request->getParam('id');
       
        $modelNews = new Model_DbTable_News();
        $modelNews->deleteNews($id);

        $this->_redirect('/posts/adminlist?delete=1');
    }

}
