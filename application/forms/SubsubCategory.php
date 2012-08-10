<?php

class Form_SubsubCategory extends Zend_Form {

    public function __construct() {
        parent::__construct($options);

        $translate = Zend_Registry::get('Zend_Translate'); 
        
        $validatorNotEmpty = new Zend_Validate_NotEmpty();
        $validatorNotEmpty->setMessage("A mező nem lehet üres!");
        
        //$validatorDbRecord = new Zend_Validate_Db_NoRecordExists('gearoscope_gears_subsubcategories', 'subsubcategory');
        //$validatorDbRecord->setMessage($translate->_('validatorDbSubcategoryRecords'));

        $this->setName('Posts');

        $title = new Zend_Form_Element_Text('title');
        $title->setLabel('Al-alkategória neve')
        		->setAttrib("class", "login-inp form-input")
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator($validatorNotEmpty);
          //      ->addValidator($validatorDbRecord)        
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
        $submit->setLabel("Mentés");
        $submit->setAttrib("class", "submit-login");
        $submit->setAttrib('style', 'clear: both;');

        $this->addElements(array($title, $lead, $content, $photo, $submit));
    }

}