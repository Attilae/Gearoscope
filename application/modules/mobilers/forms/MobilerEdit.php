<?php

class Mobilers_Form_MobilerEdit extends Zend_Form {

    public function __construct($item) {
        parent::__construct($options);

        $this->setAttrib('enctype', 'multipart/form-data');

        // create new element
        $id = $this->createElement('hidden', 'id');
        // element options
        $id->setDecorators(array('ViewHelper'));
        // add the element to the form
        $this->addElement($id);

//        $username = $this->createElement('text','username');
//        $username->setLabel('Felhasználónév: ');
//        $username->setRequired('true');
//        $username->addFilter('StripTags');
//        $username->addErrorMessage('The username is required!');
//        $username->setAttrib("class", "login-inp");
//        $this->addElement($username);
//
//        $password = $this->createElement('password', 'password');
//        $password->setLabel('Jelszó: ');
//        $password->setRequired('true');
//        $password->setAttrib("class", "login-inp");
//        $this->addElement($password);
//        $firstName = $this->createElement('text','first_name');
//        $firstName->setLabel('Vezetéknév: ');
//        $firstName->setRequired('true');
//        $firstName->addFilter('StripTags');
//        $firstName->setValue($item["first_name"]);
//        $this->addElement($firstName);
//
//        $lastName = $this->createElement('text','last_name');
//        $lastName->setLabel('Keresztnév: ');
//        $lastName->setRequired('true');
//        $lastName->addFilter('StripTags');
//        $lastName->setValue($item["last_name"]);
//        $this->addElement($lastName);

        $role = $this->createElement('select', 'role');
        $role->setLabel("Select a role:");
        $role->addMultiOption('User', 'user');
        $role->addMultiOption('Administrator', 'administrator');
//        $this->addElement($role);
        // create new element
        $email = $this->createElement('text', 'email');
        // element options
        $email->setLabel('Email cím: ');
        $email->setRequired(TRUE);
        $email->setAttrib('size', 40);
        $email->setAttrib("class", "login-inp");
        $email->setValue($item["email"]);
        // add the element to the form
        $this->addElement($email);

        // create new element
        $bio = $this->createElement('textarea', 'bio');
        // element options
        $bio->setLabel('Rövid leírás: ');
        $bio->setRequired(TRUE);
        $bio->setAttrib("class", "form-textarea mceEditor");
        $bio->setAttrib('cols', 40);
        $bio->setAttrib('rows', 4);
        $bio->setAttrib("style", "width: 625px");
        $bio->setValue($item["bio"]);
        // add the element to the form
        $this->addElement($bio);

        // create new element
        $job = $this->createElement('text', 'job');
        // element options
        $job->setLabel('Foglalkozás: ');
        $job->setRequired(TRUE);
        $job->setAttrib('size', 40);
        $job->setAttrib("class", "login-inp");
        $job->setValue($item["job"]);
        // add the element to the form
        $this->addElement($job);

        // create new element
        $birth_year = $this->createElement('text', 'birth_year');
        // element options
        $birth_year->setLabel('Születési év: ');
        $birth_year->setRequired(TRUE);
        $birth_year->setAttrib('size', 40);
        $birth_year->setAttrib("class", "login-inp");
        $birth_year->setValue($item["birth_year"]);
        // add the element to the form
        $this->addElement($birth_year);

        // create the image preview
        $photoPreview = $this->createElement('image', 'cover_thumb_preview');
        // element options
        $photoPreview->setLabel('Fotó: ');
        $photoPreview->setAttrib('style', 'width:200px;height:auto;cursor:default;');
        // add the element to the form
        $photoPreview->setImage("/../public/uploads/users/" . $item["photo_url"]);
        $this->addElement($photoPreview);

        // create new element
        $photo = $this->createElement('file', 'photo');
        // element options
        $photo->setLabel('Photo: ');
        $photo->setRequired(FALSE);
        $photo->setDestination(APPLICATION_PATH . '/../public/uploads/users');
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
        // add the element to the form
        $this->addElement($photo);

        $submit = $this->addElement('submit', 'submit', array('label' => 'Mentés'));
    }

}

?>
