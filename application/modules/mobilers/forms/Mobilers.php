<?php
class Mobilers_Form_Mobilers extends Zend_Form
{
    public function init()
    {
        $this->setAttrib('enctype', 'multipart/form-data');

        // create new element
        $id = $this->createElement('hidden', 'id');
        // element options
        $id->setDecorators(array('ViewHelper')); 
        // add the element to the form
        $this->addElement($id);

        $username = $this->createElement('text','username');
        $username->setLabel('Felhasználónév: ');
        $username->setRequired('true');
        $username->addFilter('StripTags');
        $username->addErrorMessage('The username is required!');
        $username->setAttrib("class", "login-inp");
        $this->addElement($username);

        $password = $this->createElement('password', 'password');
        $password->setLabel('Jelszó: ');
        $password->setRequired('true');
        $password->setAttrib("class", "login-inp");
        $this->addElement($password);

        $firstName = $this->createElement('text','first_name');
        $firstName->setLabel('Vezetéknév: ');
        $firstName->setRequired('true');
        $firstName->addFilter('StripTags');
        $this->addElement($firstName);

        $lastName = $this->createElement('text','last_name');
        $lastName->setLabel('Keresztnév: ');
        $lastName->setRequired('true');
        $lastName->addFilter('StripTags');
        $this->addElement($lastName);

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
        $email->setAttrib('size',40);
        $email->setAttrib("class", "login-inp");
         // add the element to the form
        $this->addElement($email);

        // create new element
        $bio = $this->createElement('textarea', 'bio');
        // element options
        $bio->setLabel('Rövid leírás: ');
        $bio->setRequired(TRUE);
        $bio->setAttrib("class", "form-textarea mceEditor");
        $bio->setAttrib('cols',40);
        $bio->setAttrib('rows',4);
        // add the element to the form
        $this->addElement($bio);

         // create new element
        $job = $this->createElement('text', 'job');
        // element options
        $job->setLabel('Foglalkozás: ');
        $job->setRequired(TRUE);
        $job->setAttrib('size',40);
        $job->setAttrib("class", "login-inp");
         // add the element to the form
        $this->addElement($job);

        // create new element
        $birth_year = $this->createElement('text', 'birth_year');
        // element options
        $birth_year->setLabel('Születési év: ');
        $birth_year->setRequired(TRUE);
        $birth_year->setAttrib('size',40);
        $birth_year->setAttrib("class", "login-inp");
         // add the element to the form
        $this->addElement($birth_year);        

        // create new element
        $photo = $this->createElement('file', 'photo');
        // element options
        $photo->setLabel('Photo: ');        
        $photo->setRequired(FALSE);
        $photo->setDestination(APPLICATION_PATH . '/../public/uploads/mobilers');
        // ensure only 1 file
        $photo->addValidator('Count', false, 1);
        // limit to 100K
        $photo->addValidator('Size', false, 1024000);
        // only JPEG, PNG, and GIFs
        $photo->addValidator('Extension', false, 'jpg,png,gif');
        // add the element to the form
        $this->addElement($photo);

        $submit = new Zend_Form_Element_Image("submit");
        $path = "/public/admin/images/login/submit_login.gif";
        $submit->setImage($path);
        $submit->setAttrib("class", "submit-login");
        $submit = $this->addElement($submit);
    }
}
?>
