<?php

class Form_Messages extends Zend_Form {

    public function __construct() {
        parent::__construct($options);

        $validatorNotEmpty = new Zend_Validate_NotEmpty();
        $validatorNotEmpty->setMessage("A mező nem lehet üres!");

        $subject = new Zend_Form_Element_Text('subject');
        $subject->setLabel('Cím')
                ->setAttrib('subject', 'A bejegyzés címe')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator($validatorNotEmpty);

        $receiver = new Zend_Form_Element_Multiselect('receiver');
        $receiver->setLabel('Címzettek')
                ->setRequired(true)
                ->addValidator($validatorNotEmpty);

        $user = new Model_DbTable_User();
        $mobilers = $user->getMobilers();

        foreach ($mobilers as $mobiler):
            $receiver->addMultiOption($mobiler["user_id"], $mobiler["username"]);
        endforeach;

        $lead = new Zend_Form_Element_Textarea('lead');
        $lead->addValidator($validatorNotEmpty);
        $lead->setLabel('Üzenet: ')
                ->setRequired(true)
                ->setAttrib('rows', 20)
                ->setAttrib('cols', 50)
                ->setAttrib('class', 'mceNoEditor')
                ->setAttrib('title', 'A bejegyzés címe')
                ->setAttrib('style', 'resize: none; width: 625px;height: 150px;')
                ->addFilter('StringTrim');



        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
        $submit->setLabel("Elküld");
        $submit->setAttrib('style', 'clear: both;');

        $this->addElements(array($subject, $title, $receiver, $lead, $submit));
    }

}