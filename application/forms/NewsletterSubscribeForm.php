<?php
class Form_NewsletterSubscribeForm extends Zend_Form
{
    public function init ()
    {
        $validatorEmail = new Zend_Validate_EmailAddress();
        $validatorEmail->setMessage("Kérlek add meg helyesen e-mail címed!");

        $validatorNotEmpty = new Zend_Validate_NotEmpty();
        $validatorNotEmpty->setMessage("A mező nem lehet üres!");

        $this->setAction("/newsletter/subscribe");
        $name = $this->createElement('text', 'name');
        $name->setLabel('Név:');
        $name->setRequired(TRUE);
        $name->addValidator($validatorNotEmpty);
        $name->setAttrib('size', 35);
        $this->addElement($name);
        $email = $this->createElement('text', 'email');
        $email->setLabel('E-mail cím:');
        $email->setRequired(TRUE);
        $email->addValidator($validatorNotEmpty);
        $email->addValidator($validatorEmail);
        $email->addFilters(array(new Zend_Filter_StringTrim() , new Zend_Filter_StringToLower()));
        $email->setAttrib('size', 35);
        $this->addElement($email);        
        $this->addElement('submit', 'submit', array('label' => 'Feliratkozom'));
    }
}

