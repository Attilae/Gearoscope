<?php

class Form_Password extends Zend_Form {

    public function init() {
        $this->setMethod('post');

        $validatorEmail = new Zend_Validate_EmailAddress();
        $validatorEmail->setMessage("Kérjük adja meg helyesen az e-mail címét!", 'emailAddressInvalid');
        $validatorEmail->setMessage("Kérjük adja meg helyesen az e-mail címét!", 'emailAddressInvalidFormat');

        $validatorNotEmpty = new Zend_Validate_NotEmpty();
        $validatorNotEmpty->setMessage("A mező nem lehet üres!");

        $email = $this->createElement('text', 'email');
        $email->setRequired('true');
        $email->addFilter('StripTags');
        $email->setDecorators( array( 'ViewHelper', 'Errors' ) );
        $email->addValidator($validatorEmail);        
        $email->addValidator($validatorNotEmpty);
        $this->addElement($email);

        $submit = $this->addElement('submit', 'submit', array('label' => 'Elküld'));
        $submit->setDecorators(array('ViewHelper'));
    }

}

?>
