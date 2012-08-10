<?php

class Form_User extends Zend_Form {

    public function init() {
        $this->setMethod('post');
        $this->setAttrib("class", "login active");
        
        $translate = Zend_Registry::get('Zend_Translate');            

        $validatorEmail = new Zend_Validate_EmailAddress();
        $validatorEmail->setMessage($translate->_('validatorEmailInvalid'), 'emailAddressInvalid');
        $validatorEmail->setMessage($translate->_('validatorEmailInvalid'), 'emailAddressInvalidFormat');

        $validatorNotEmpty = new Zend_Validate_NotEmpty();
        $validatorNotEmpty->setMessage($translate->_('validatorNotEmpty'));

        $validatorStringLength = new Zend_Validate_StringLength(array('min' => 5));
        $validatorStringLength->setMessage('A mezőben szereplő karakterek nem lehetnek 6-nál rövidebbek!', 'stringLengthTooShort');

        $validatorDbEmail = new Zend_Validate_Db_NoRecordExists('gearoscope_users', 'user_email');
        $validatorDbEmail->setMessage($translate->_('validatorDbEmail'));

        $validatorDbUsername = new Zend_Validate_Db_NoRecordExists('gearoscope_users', 'user_username');
        $validatorDbUsername->setMessage($translate->_('validatorDbUsername'));
                
        $nameReg = $this->createElement('text', 'name_reg');
        $nameReg->setRequired('true');
        $nameReg->addFilter('StripTags');
        $nameReg->setDecorators( array( 'ViewHelper', 'Errors' ) );
        $nameReg->addValidator($validatorNotEmpty);
        $this->addElement($nameReg);
        
        $username = $this->createElement('text', 'username');
        $username->setRequired('true');
        $username->addFilter('StripTags');
        $username->addErrorMessage('The username is required!');
        $this->addElement($username);

        $usernameReg = $this->createElement('text', 'username_reg');
        $usernameReg->setRequired('true');
        $usernameReg->addFilter('StripTags');
        $usernameReg->setDecorators( array( 'ViewHelper', 'Errors' ) );
        $usernameReg->addValidator($validatorNotEmpty);
        $usernameReg->addValidator($validatorDbUsername);
        $this->addElement($usernameReg);

        $password = $this->createElement('password', 'password');
        $password->setLabel('Password: ')
                ->setDecorators(array('ViewHelper'));
        $password->setRequired('true');
        $this->addElement($password);

        $email = $this->createElement('text', 'email');
        $email->setRequired('true');
        $email->addFilter('StripTags');
        $email->setDecorators( array( 'ViewHelper', 'Errors' ) );
        $email->addValidator($validatorEmail);
        $email->addValidator($validatorDbEmail);
        $email->addValidator($validatorNotEmpty);
        $this->addElement($email);

        $passwordConfirmation = new CMS_Validate_PasswordConfirmation();

        $passwordReg = $this->addElement('password', 'password_reg', array(
                    'filters' => array('StringTrim'),
                    'validators' => array(
                        $passwordConfirmation,
                        $validatorNotEmpty,
                    ),
                    'required' => true,
                    'decorators' => array('ViewHelper', 'Errors'),
                    'class' => 'quicktext',
                    'value' => 'Jelsz�'                    
                ));           

        $passwordReg_confirm = $this->addElement('password', 'password_reg_confirm', array(
                    'filters' => array('StringTrim'),
                    'validators' => array(
                        $passwordConfirmation,
                        $validatorNotEmpty,
                    ),
                    'class' => 'input-text',
                    'required' => true,
                    'decorators' => array('ViewHelper', 'Errors'),
                    'class' => 'quicktext'                    
                ));
        $passwordReg_confirm->setDecorators( array( 'ViewHelper', 'Errors' ) );

        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
		$view = $viewRenderer->view;
		
		$baseUrl = $view->baseUrl();
        
        $captcha = new Zend_Form_Element_Captcha('captcha', array(
                    'id' => 'captchas',
                    'title' => 'Security Check.',
        			'class' => 'quicktext',
                    'captcha' => array(
                        'captcha' => 'Image',
                        'required' => true,
                        'font' => APPLICATION_PATH . '/../public/capthca/arial.ttf',
                        'wordlen' => '6',
                        'width' => '100',
                        'height' => '50',
                        'ImgAlign' => 'left',
                        'imgdir' => APPLICATION_PATH . '/../public/capthca',
                        'DotNoiseLevel' => '0',
                        'LineNoiseLevel' => '0',
                        'Expiration' => '1000',
                        'fontsize' => '16',
                        'gcFreq' => '10',
                        'ImgAlt' => 'Gearoscope captcha',
                        'imgurl' => $baseUrl . '/public/capthca',
                        'GcFreq' => '5'
                        )));
        $captcha->setErrorMessages(array('badCaptcha' => 'Hibás ellenőrző kód'));
        $this->addElement($captcha);

        $submit = $this->addElement('submit', 'submit', array('label' => 'Elküld'));
        $submit->setDecorators(array('ViewHelper'));
    }

}

?>
