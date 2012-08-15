<?php

class CommentsController extends Zend_Controller_Action {

    public function init() {
        $this->view->addHelperPath(
                'ZendX/JQuery/View/Helper'
                , 'ZendX_JQuery_View_Helper');
        
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('gear', 'html')
                ->initContext();
    }
    
    public function gearAction() {        
        
        $gear_id = $this->_request->getParam("id");
        $this->view->gear_id = $gear_id;
        
        $commentsModel = new Model_DbTable_Comments();
        
        $comments = $commentsModel->getComments($gear_id);        
        
        $paginator = Zend_Paginator::factory($comments);
        $paginator->setItemCountPerPage(4);
        $paginator->setCurrentPageNumber($this->getRequest()->getParam('page', 1));
        $this->view->paginator = $paginator;
    }
    
    public function listAction() {

        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('admin');

        $posts = new Model_DbTable_Comments();
        $result = $posts->findAll();

        $this->view->comments = $result;
    }

    public function listownAction() {

        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('layout');

        $auth = Zend_Auth::getInstance();
        $identity = $auth->getIdentity();

        $posts = new Model_DbTable_Comments();
        $result = $posts->getCommentsByUser($identity->user_id);

        $this->view->comments = $result;
    }

    public function deleteAction() {

        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->getHelper('layout')->disableLayout();

        $id = $this->_request->getParam('id');

        $modelComment = new Model_DbTable_Comments();
        $comment = $modelComment->getComment($id);

        $modelPosts = new Model_DbTable_Posts();
        $post = $modelPosts->getPost($comment[0]["post_id"]);

        if ($comment[0]["user_id"] != $_SESSION["Zend_Auth"]["storage"]->user_id) {
            $this->_redirect('/posts/view/id/' . $post[0]["post_id"]);
        }

        $comments = $post[0]["comments"];
        $comments = $comments - 1;

        $modelPosts->aggregateComments($post[0]["post_id"], $comments);

        $modelComment->deleteComment($id);

        $this->_redirect('/comments/listown');
    }

    public function postdeleteAction() {

        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->getHelper('layout')->disableLayout();

        $id = $this->_request->getParam('id');

        $modelComment = new Model_DbTable_Comments();
        $comment = $modelComment->getComment($id);

        $modelPosts = new Model_DbTable_Posts();
        $post = $modelPosts->getPost($comment[0]["post_id"]);


        $comments = $post[0]["comments"];
        $comments = $comments - 1;

        $modelPosts->aggregateComments($post[0]["post_id"], $comments);

        $modelComment->deleteComment($id);

        $this->_redirect($_SERVER["HTTP_REFERER"]);
    }

    public function admindeleteAction() {

        $id = $this->_request->getParam('id');

        $modelComment = new Model_DbTable_Comments();
        $comment = $modelComment->getComment($id);

        $modelPosts = new Model_DbTable_Posts();
        $post = $modelPosts->getPost($comment[0]["post_id"]);

        $comments = $post[0]["comments"];
        $comments = $comments - 1;

        $modelPosts->aggregateComments($post[0]["post_id"], $comments);
        $modelComment->deleteComment($id);
    }

}