<?php

class Form_Category extends Zend_Form {

    public function __construct() {
        parent::__construct($options);

        $validatorNotEmpty = new Zend_Validate_NotEmpty();
        $validatorNotEmpty->setMessage("A mező nem lehet üres!");

        $this->setName('Posts');

        $title = new Zend_Form_Element_Text('title');
        $title->setLabel('Cím')
        		->setAttrib("class", "login-inp")
                ->setAttrib('title', 'A bejegyzés címe')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator($validatorNotEmpty);                         
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
        $submit->setLabel("Elküld");
        $submit->setAttrib("class", "submit-login");
        $submit->setAttrib('style', 'clear: both;');

        $this->addElements(array($title, $lead, $content, $photo, $submit));
    }

}