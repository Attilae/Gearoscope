<?php

class Form_Email extends Zend_Form {

    public function init() {
        $this->setMethod('post');

        $validatorEmail = new Zend_Validate_EmailAddress();
        $validatorEmail->setMessage("Kérjük adja meg helyesen az e-mail címét!", 'emailAddressInvalid');
        $validatorEmail->setMessage("Kérjük adja meg helyesen az e-mail címét!", 'emailAddressInvalidFormat');

        $validatorNotEmpty = new Zend_Validate_NotEmpty();
        $validatorNotEmpty->setMessage("A mező nem lehet üres!");

        $validatorDbEmail = new Zend_Validate_Db_NoRecordExists('users', 'email');
        $validatorDbEmail->setMessage('Ezzel az e-mail címmel már létezik regisztráció!');

        $email = $this->createElement('text', 'email');
        $email->setRequired('true');
        $email->addFilter('StripTags');
        $email->setDecorators(array('ViewHelper', 'Errors'));
        $email->addValidator($validatorEmail);
        $email->addValidator($validatorNotEmpty);
        $email->addValidator($validatorDbEmail);
        $this->addElement($email);

        $submit = $this->addElement('submit', 'submit', array('label' => 'Elküld'));
        $submit->setDecorators(array('ViewHelper'));
    }

}

?>
