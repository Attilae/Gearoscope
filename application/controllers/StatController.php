<?php

class StatController extends Zend_Controller_Action {

    public function indexAction() {

        ini_set("display_errors", "1").

        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('admin');

        $modelPosts = new Model_DbTable_Posts();
        $mostMobilerPosts = $modelPosts->mostMobilerPost();

        $this->view->mostMobilerPost = $mostMobilerPosts;

        $mostCommentedPosts = $modelPosts->mostCommentedPost();

        $this->view->mostCommentedPost = $mostCommentedPosts;

        $mostRatedPosts = $modelPosts->mostRatedPost();

        $this->view->mostRatedPost = $mostRatedPosts;

        $modelRates = new Model_DbTable_Rates();
        $mostRatedMobiler = $modelRates->mostRatedMobiler();

        $this->view->mostRatedMobiler = $mostRatedMobiler;

        $modelComments = new Model_DbTable_Comments();
        $mostCommentedMobiler = $modelComments->mostCommentedMobiler();

        $this->view->mostCommentedMobiler = $mostCommentedMobiler;

        $mostUserComments = $modelComments->mostUserComment();

        $this->view->mostUserComments = $mostUserComments;

        $modelRates = new Model_DbTable_Rates();
        $mostUserRates = $modelRates->mostUserRate();

        $this->view->mostUserRate = $mostUserRates;
    }

}
?>