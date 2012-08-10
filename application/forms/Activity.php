<?php

class Form_Activity extends Zend_Form {

    public function __construct() {
        parent::__construct($options);

        $validatorNotEmpty = new Zend_Validate_NotEmpty();
        $validatorNotEmpty->setMessage("A mező nem lehet üres!");

        $this->setName('Posts');

        $title = new Zend_Form_Element_Text('title');
        $title->setLabel('Cím')
                ->setAttrib('title', 'A bejegyzés címe')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator($validatorNotEmpty);

        $link = new Zend_Form_Element_Text('link');
        $link->setLabel('Link')
                ->setAttrib('title', 'Link')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator($validatorNotEmpty);

        $photo = $this->createElement('file', 'photo');
        $photo->setLabel('Kép: ');
        $photo->setDestination(APPLICATION_PATH . '/../public/uploads/activities/');
        $photo->addFilter('Rename',
                array('source' => $this->file,
                    'target' => APPLICATION_PATH . '/../public/uploads/activities/' . CMS_Validate_Internim::getMicrotime() . '.jpg',
                    'overwrite' => true));
        $photo->addValidator('Count', false, 1);
        $photo->addValidator('Size', false, 1024000);
        $photo->addValidator('Extension', false, 'jpg');
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
        $submit->setLabel("Elküld");
        $submit->setAttrib('style', 'clear: both;');

        $this->addElements(array($title, $photo, $link, $submit, $presave, $delete));
    }

}