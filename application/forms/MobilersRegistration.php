<?php

class Form_MobilersRegistration extends Zend_Form {

    public function init() {
        $this->setMethod('post');
        $this->setAttrib("id", "login_form");

        $validatorEmail = new Zend_Validate_EmailAddress();
        $validatorEmail->setMessage("Kérjük adja meg helyesen az e-mail címét!", 'emailAddressInvalid');
        $validatorEmail->setMessage("Kérjük adja meg helyesen az e-mail címét!", 'emailAddressInvalidFormat');

        $validatorNotEmpty = new Zend_Validate_NotEmpty();
        $validatorNotEmpty->setMessage("A mező nem lehet üres!");

        $validatorStringLength = new Zend_Validate_StringLength(array('max' => 5));

        $validatorStringBio = new Zend_Validate_StringLength(array('max' => 200));

        // create new element
        $id = $this->createElement('hidden', 'id');
        // element options
        $id->setDecorators(array('ViewHelper'));
        // add the element to the form
        $this->addElement($id);

        //create the form elements
        $name = $this->createElement('text', 'name');
        //$username->setLabel('Username: ');
        $name->setRequired(true);
        $name->addFilter('StripTags');
        $name->setAttrib("class", "required");
        $name->setDecorators(array('ViewHelper'));
        $this->addElement($name);

        $sex = $this->createElement('select', 'sex');
        $sex->setRequired(true);
        $sex->addMultioptions(array(
            "" => "",
            "Férfi" => "Férfi",
            "Nő" => "Nő"
        ));
        $sex->setAttrib("class", "required");
        $sex->setAttrib("title", "Select one");
        $sex->setAttrib("style", "width: 400px;");
        $sex->setDecorators(array('ViewHelper'));
        $this->addElement($sex);

        $city = $this->createElement('text', 'city');
        $city->setRequired(true);
        $city->addFilter('StripTags');
        $city->setDecorators(array('ViewHelper'));
        $city->setAttrib("class", "required");
        $this->addElement($city);

        $age = $this->createElement('text', 'age');
        $age->setRequired(true);
        $age->addFilter('StripTags');
        $age->addValidator($validatorStringLength);
        $age->setDecorators(array('ViewHelper'));
        $age->setAttrib("class", "required");
        $age->setAttrib("width", "400");
        $this->addElement($age);

        $phone = $this->createElement('text', 'phone');
        $phone->setRequired(true);
        $phone->addFilter('StripTags');
        $phone->setAttrib("class", "required");
        $phone->setDecorators(array('ViewHelper'));
        $this->addElement($phone);

        $email = $this->createElement('text', 'email');
        $email->setRequired(true);
        $email->addFilter('StripTags');
        $email->addValidator($validatorEmail);
        $email->setDecorators(array('ViewHelper'));
        $email->setAttrib("class", "required");
        $this->addElement($email);

        $graduate = $this->createElement('text', 'graduate');
        $graduate->setRequired(true);
        $graduate->addFilter('StripTags');
        $graduate->setDecorators(array('ViewHelper'));
        $graduate->setAttrib("class", "required");
        $this->addElement($graduate);

        $job = $this->createElement('text', 'job');
        $job->setRequired(true);
        $job->addFilter('StripTags');
        $job->setDecorators(array('ViewHelper'));
        $job->setAttrib("class", "required");
        $this->addElement($job);

        $activate = $this->createElement('select', 'activate');
        $activate->setRequired(true);
        $activate->setAttrib("class", "required")
                ->setAttrib("style", "width: 400px;")
                ->setAttrib("width", "400");
        $activate->addMultioptions(array(
            "" => "",
            "zenét írok" => "Zenét írok",
            "hangszeren játszom" => "Hangszeren játszom",
            "Képzőművészettel foglalkozom (rajzolok, festek, street art)" => "Képzőművészettel foglalkozom",
            "Írok/publikálok" => "Írok/publikálok",
            "Alkalmazást fejlesztek" => "Alkalmazást fejlesztek",
            "Gamer vagyok /PC-n, konzolon, online játékokkal játszom/" => "Gamer vagyok",
            "Fotózom/videózom" => "Fotózom/videózom",
            "Egyik sem" => "Egyik sem"
        ));
        $activate->setDecorators(array('ViewHelper'));        
        $this->addElement($activate);

        $facebook = $this->createElement('select', 'facebook');
        $facebook->setRequired(true);
        $facebook->setAttrib("class", "required");
        $facebook->setAttrib("style", "width: 400px;");
        $facebook->addMultioptions(array(
            "" => "",
            "Igen" => "Igen",
            "Nem" => "Nem"
        ));
        $facebook->setDecorators(array('ViewHelper'));
        $this->addElement($facebook);

        $facebookUsers = $this->createElement('select', 'facebook_users');
        $facebookUsers->addMultioptions(array(
            "" => "",
            "0-200" => "0-200",
            "201-500" => "201-500",
            "501-1000" => "501-1000",
            "1000 – " => "1000 – "
        ));
        $facebookUsers->setDecorators(array('ViewHelper'))
                ->setAttrib("style", "width: 400px;");;
        $this->addElement($facebookUsers);

        $twitter = $this->createElement('select', 'twitter');
        $twitter->setRequired(true);
        $twitter->setAttrib("class", "required")
                ->setAttrib("style", "width: 400px;");;
        $twitter->addMultioptions(array(
            "" => "",
            "Igen" => "Igen",
            "Nem" => "Nem"
        ));
        $twitter->setDecorators(array('ViewHelper'));
        $this->addElement($twitter);

        $twitterUsers = $this->createElement('select', 'twitter_users');
        $twitterUsers->addMultioptions(array(
            "" => "",
            "0-200" => "0-200",
            "201-500" => "201-500",
            "Charlie Sheen" => "Charlie Sheen vagyok",
            "Lindsay Lohan" => "Lindsay Lohan vagyok"
        ));
        $twitterUsers->setAttrib("style", "width: 400px;");
        $twitterUsers->setDecorators(array('ViewHelper'));
        $this->addElement($twitterUsers);

        $blog = $this->createElement('select', 'blog');
        $blog->setRequired(true);
        $blog->setAttrib("class", "required")
                ->setAttrib("style", "width: 400px;");;
        $blog->addMultioptions(array(
            "" => "",
            "Van" => "Van",
            "Nincs" => "Nincs"
        ));
        $blog->setDecorators(array('ViewHelper'));
        $this->addElement($blog);

        $blogAddress = $this->createElement('text', 'blog_address');
        $blogAddress->addFilter('StripTags');
        $blogAddress->setDecorators(array('ViewHelper'));
        $this->addElement($blogAddress);

        $app = $this->createElement('text', 'app');
        $app->addFilter('StripTags');
        $app->setAttrib("onfocus", "$('#twitterBox > select').attr('disabled', 'true');");
        $app->setAttrib("onblur", "$('#twitterBox > select').attr('disabled', false);");
        $app->setDecorators(array('ViewHelper'));
        $this->addElement($app);

        $bio = $this->createElement('textarea', 'bio');
        $bio->setRequired(true);
        $bio->addFilter('StripTags');
        $bio->addValidator($validatorStringBio);
        $bio->setDecorators(array('ViewHelper'));
        $bio->setAttrib("class", "required");        
        $bio->setAttrib("style", "resize: none;");
        $bio->setAttrib('onKeyDown', 'textCounter(this,this.form.counter,160);');
        $bio->setAttrib('onKeyUp', 'textCounter(this,this.form.counter,160);');
        $this->addElement($bio);
        
        $terms = new Zend_Form_Element_Checkbox('terms');
        $terms->setDecorators(array('ViewHelper'));
        $terms->setAttrib("class", "required");
        $terms->setRequired(true);
        $this->addElement($terms);

        $submit = new Zend_Form_Element_Submit("submit");
        $submit->setAttrib("id", "registerButton");
        $submit = $this->addElement($submit);
    }

}

?>
