<?php

class Form_AdminLogin extends Zend_Form {

    public function __construct() {
        parent::__construct($options);
        $this->setMethod('post');
        $this->setAttrib("id", "login-form");

        //create the form elements
        $username = $this->createElement('text', 'username');
        $username->setRequired(true);
        $username->setLabel('Felhasználónév: ');
        $username->addFilter('StripTags');
        $username->setAttrib('class', 'login-inp');
        $this->addElement($username);

        $password = $this->createElement('password', 'password');
        $password->setLabel('Jelszó: ');
        $password->setRequired(true);
        $password->setAttrib('class', 'login-inp');
        $this->addElement($password);

        $submit = $this->createElement('submit', 'submit');
        $submit->setLabel('Elküld');
        $submit->setAttrib('class', 'submit-login');
        $this->addElement($submit);
    }

}

?>
