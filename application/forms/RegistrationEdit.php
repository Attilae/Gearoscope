<?php

class Form_RegistrationEdit extends Zend_Form {

    public function __construct($item) {
        parent::__construct($options);
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

        $passwordConfirmation = new CMS_Validate_PasswordConfirmation();

        $newPassword = $this->addElement('password', 'password', array(
                    'filters' => array('StringTrim'),
                    'validators' => array(
                        $passwordConfirmation,
                        array('Alnum'),
                        array('StringLength', false, array(6, 100)),
                    ),
                    'class' => 'input-text',
                    'label' => 'Password',
                ));

        $newPassword_confirm = $this->addElement('password', 'password_confirm', array(
                    'filters' => array('StringTrim'),
                    'validators' => array(
                        $passwordConfirmation,
                        array('Alnum'),
                        array('StringLength', false, array(6, 100)),
                    ),
                    'class' => 'input-text',
                    'label' => 'Confirm Password',
                ));

        // create the image preview
        $photoPreview = $this->createElement('image', 'photo_preview');
        // element options
        $photoPreview->setLabel('Preview: ');
        $photoPreview->setAttrib('style', 'width:200px;height:auto;');
        // add the element to the form
        $photoPreview->setImage("/../public/uploads/users/" . $item[0]["photo_url"]);
        $this->addElement($photoPreview);

// create new element
        $photo = $this->createElement('file', 'photo');
        // element options
        $photo->setLabel('Kép: ');
        $photo->setDestination(APPLICATION_PATH . '/../public/uploads/users/');
        // ensure only 1 file
        $photo->addValidator('Count', false, 1);
        // limit to 100K
        $photo->addValidator('Size', false, 1024000);
        // only JPEG, PNG, and GIFs
        $photo->addValidator('Extension', false, 'jpg');
        $photo->addFilter('Rename',
                array('source' => $this->file,
                    'target' => APPLICATION_PATH . '/../public/uploads/users/' . CMS_Validate_Internim::getMicrotime() . '.jpg',
                    'overwrite' => true));
        $this->addElement($photo);


        $submit = new Zend_Form_Element_Submit("submit");
        $submit->setAttrib("id", "registerButton");
        $submit = $this->addElement($submit);
    }

}

?>
