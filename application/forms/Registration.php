<?php

class Form_Registration extends Zend_Form {

    public function init() {
        $this->setMethod('post');
        $this->setAttrib("id", "login_form");

        $validatorEmail = new Zend_Validate_EmailAddress();
        $validatorEmail->setMessage("Kérjük adja meg helyesen az e-mail címét!", 'emailAddressInvalid');
        $validatorEmail->setMessage("Kérjük adja meg helyesen az e-mail címét!", 'emailAddressInvalidFormat');

        $validatorNotEmpty = new Zend_Validate_NotEmpty();
        $validatorNotEmpty->setMessage("A mező nem lehet üres!");

        $validatorStringLength = new Zend_Validate_StringLength(array('max' => 5));

        // create new element
        $id = $this->createElement('hidden', 'id');
        // element options
        $id->setDecorators(array('ViewHelper'));
        // add the element to the form
        $this->addElement($id);

        //create the form elements
        $name = $this->createElement('text', 'name');
        $name->setLabel('Username: ');
        $name->setRequired('true');
        $name->addFilter('StripTags');        
//        $username->setAttrib("class", "login-inp");
        $this->addElement($name);

        $email = $this->createElement('text', 'email');
        $email->setLabel('E-mail: ');
        $email->setRequired('true');
        $email->addFilter('StripTags');
        $email->addValidator($validatorEmail);
        $this->addElement($email);

        $passwordConfirmation = new CMS_Validate_PasswordConfirmation();

        $password = $this->addElement('password', 'password', array(
                    'filters' => array('StringTrim'),
                    'validators' => array(
                        $passwordConfirmation,
                        array('Alnum'),
                        array('StringLength', false, array(6, 100)),
                    ),
                    'class' => 'input-text',
                    'required' => true,
                    'label' => 'Password',
                ));

        $password_confirm = $this->addElement('password', 'password_confirm', array(
                    'filters' => array('StringTrim'),
                    'validators' => array(
                        $passwordConfirmation,
                        array('Alnum'),
                        array('StringLength', false, array(6, 100)),
                    ),
                    'class' => 'input-text',
                    'required' => true,
                    'label' => 'Confirm Password',
                ));

        // configure the captcha service
        $privateKey = '6Lf-LwcAAAAAAMSOrrjbogfM6ytHs0u3oLI3Zuv0';
        $publicKey = '6Lf-LwcAAAAAAO9aLI2lhXdcEe6l5PSAtKOo7k4K';
        $recaptcha = new Zend_Service_ReCaptcha($publicKey, $privateKey);

        $captcha = new Zend_Form_Element_Captcha('captcha',
            array('captcha'        => 'ReCaptcha',
    		'captchaOptions' => array('captcha' => 'ReCaptcha', 'service' => $recaptcha)));
        $this->addElement($captcha);


        $submit = new Zend_Form_Element_Submit("submit");
        $submit->setAttrib("id", "registerButton");
        $submit = $this->addElement($submit);
    }
}

?>
