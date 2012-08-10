<?php

class TagController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {

        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('layout');

        $tag = $this->_getParam('tag');

        $posts = new Model_DbTable_Posts();
        $result = $posts->getPostsByTag($tag);

        $page = $this->_getParam('page', 1);
        $paginator = Zend_Paginator::factory($result);
        $paginator->setItemCountPerPage(7);
        $paginator->setCurrentPageNumber($page);
        $this->view->tagPosts = $paginator;
    }

    public function viewAction() {
        // action body
        $post_id = (int) $this->_getParam('id');
        if (empty($post_id)) {
            
        }
        $post = new Model_DbTable_Posts();
        $result = $post->getPost($post_id);
        $this->view->post = $result;
        $commentsObj = new Model_DbTable_Comments();
        $request = $this->getRequest();
        $commentsForm = new Form_Comments();
        /*
         * Check the comment form has been posted
         */
        if ($this->getRequest()->isPost()) {
            if ($commentsForm->isValid($request->getPost())) {
                $model = new Model_DbTable_Comments();
                $model->saveComment($commentsForm->getValues());
                $commentsForm->reset();
            }
        }
        $data = array('id' => $post_id);
        $commentsForm->populate($data);
        $this->view->commentsForm = $commentsForm;
        $comments = $commentsObj->getComments($post_id);
        $this->view->comments = $comments;

        $tagsObj = new Model_DbTable_Tags();
        $tags = $tagsObj->findByPost($post_id);
        $this->view->tags = $tags;

        $this->view->edit = '/posts/edit/id/' . $postid;
    }

    public function commentsAction() {
        // action body
    }

    public function addAction() {
        // action body
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_redirect('index/index');
        }
        $acl = new Model_Acl();
        $identity = Zend_Auth::getInstance()->getIdentity();
        if ($acl->isAllowed($identity['Role'], 'posts', 'add')) {
            $request = $this->getRequest();
            $postForm = new Form_Post();
            if ($this->getRequest()->isPost()) {
                if ($postForm->isValid($request->getPost())) {
                    $model = new Model_DbTable_Posts();
                    $model->savePost($postForm->getValues());
                    $this->_redirect('index/index');
                }
            }
            $this->view->postForm = $postForm;
        }
    }

    public function editAction() {
        // action body
        $request = $this->getRequest();
        $postid = (int) $request->getParam('id');
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_redirect('posts/view/id/' . $postid);
        }
        $identity = Zend_Auth::getInstance()->getIdentity();

        $acl = new Model_Acl();
        if ($acl->isAllowed($identity['Role'], 'posts', 'edit')) {
            $postForm = new Form_Post();
            $postModel = new Model_DbTable_Posts();
            if ($this->getRequest()->isPost()) {
                if ($postForm->isValid($request->getPost())) {
                    $postModel->updatePost($postForm->getValues());
                    $this->_redirect('posts/view/id/' . $postid);
                }
            } else {
                $result = $postModel->getPost($postid);
                $postForm->populate($result);
            }
            $this->view->postForm = $postForm;
        } else {
            var_dump($identity['Role']);
            //$this->_redirect('posts/view/id/'.$postid);
        }
    }

    public function cloudAction() {
        $modelTag = new Model_DbTable_Tags();
        $tags = $modelTag->findForCloud();
        $cloudArray = array();
        $i = 0;
        foreach ($tags as $tag):
            $cloudArray[$i]['title'] = $tag['tag'];
            $cloudArray[$i]['weight'] = $tag['tagCount'];
            $cloudArray[$i]['params'] = array('url' => '/tag/index/tag/' . $tag["tag"]);
            $i++;
        endforeach;
        $cloud = new Zend_Tag_Cloud(
                        array(
                            'tags' =>
                            $cloudArray,
                            'cloudDecorator' => array(
                                'decorator' => 'HtmlCloud',
                                'options' => array(
                                    'htmlTags' => array(
                                        'div' => array('id' => 'tags')),
                                    'separator' => ' ')),
                            'tagDecorator' => array('decorator' => 'HtmlTag',
                                'options' => array('htmlTags' => array('span')))));

        $this->view->cloud = $cloud;
    }

    public function listAction() {

        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('admin');

        $posts = new Model_DbTable_Tags();
        $result = $posts->findAll();

        $this->view->tags = $result;
    }

    public function deleteAction() {

        $id = $this->_request->getParam("id");

        $modelTag = new Model_DbTable_Tags();
        $modelTag->admindeleteTag($id);

        $this->_redirect("tag/list?delete=1");
    }

}