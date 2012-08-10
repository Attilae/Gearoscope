<?php

class PhoneindexController extends Zend_Controller_Action {

    public function init() {
        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('mobile');

        $this->view->addHelperPath("ZendX/JQuery/View/Helper", "ZendX_JQuery_View_Helper");
    }

    public function indexAction() {
        $posts = new Model_DbTable_Posts();
        $result = $posts->getFirstThree();

        $this->view->firstthree = $result;

        $priors = $posts->getPriorPost();
        $this->view->priors = $priors;

        $modelAct = new Model_DbTable_Activity();
        $activities = $modelAct->getActivities();
    }

    public function impresszumAction() {
        
    }

    public function topicAction() {
        $title = $this->_request->getParam('title');
        $this->view->title = $title;

        $posts = new Model_DbTable_Posts();

        $result = $posts->getPostsByTopic($title);

        $this->view->paginator = $result;
    }

    public function viewAction() {

        $post_id = $this->_request->getParam("id");
        
        $commentsObj = new Model_DbTable_Comments();
        $comments = $commentsObj->getComments($post_id);
        $this->view->comments = $comments;

        $post = new Model_DbTable_Posts();
        $result = $post->getPost($post_id);
        $this->view->post = $result;
    }

    public function mobilerAction() {

        $id = $this->_request->getParam('id');

        $modelMobilers = new Model_DbTable_User();
        $mobiler = $modelMobilers->getMobiler($id);

        $this->view->mobiler = $mobiler;

        $modelPost = new Model_DbTable_Posts();
        $posts = $modelPost->getPostsByMobiler($id);

        $this->view->posts = $posts;
    }

    public function altalanosfeltetelekAction() {

    }

    public function jogitudnivalokAction() {
        
    }

}

