<?php

class NewsletterController extends Zend_Controller_Action {

    public function subscribeAction() {
        $form = new Form_NewsletterSubscribeForm();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($_POST)) {
                $model = new Model_NewsletterSubscribes();
                // if the form is valid then create the new bug
                $result = $model->create($form->getValue('name'), $form->getValue('email'), time());
                // if the createBug method returns a result
                // then the bug was successfully created
                if ($result) {
                    $this->view->success = "Köszönjük a feliratkozást!";
//                    $this->_forward('confirm');
                }
            } else {
                $this->view->errors = $form->getErrorMessages();
            }
        }
        $form->populate($_POST);
    }

}

?>