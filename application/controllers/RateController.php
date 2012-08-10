<?php

class RateController extends Zend_Controller_Action {

    public function indexAction() {

        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            die();
        }

        $post_id = $this->_request->getParam('id');

        $post = new Model_DbTable_Posts();
        $result = $post->getPost($post_id);

        $rates = $result[0]["rates"];
        $rates = $rates + 1;

        $post->aggregateRates($post_id, $rates);

        $model = new Model_DbTable_Rates();
        $model->saveRate($post_id, $auth->getIdentity()->user_id);

        $this->_redirect("/posts/view/id/".$post_id);
    }

}