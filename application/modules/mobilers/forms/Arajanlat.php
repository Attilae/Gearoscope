<?php

class Arajanlat_Form_Arajanlat extends Zend_Form {

    public function init() {
        $this->setMethod('post')
                ->setAttrib('class', 'box')
                ->setAttrib('accept-charset', 'utf-8')
                ->setAction("/arajanlat/index");

        $validatorEmail = new Zend_Validate_EmailAddress();
        $validatorEmail->setMessage("Kérjük adja meg helyesen az e-mail címét!", 'emailAddressInvalid');
        $validatorEmail->setMessage("Kérjük adja meg helyesen az e-mail címét!", 'emailAddressInvalidFormat');

        $validatorNotEmpty = new Zend_Validate_NotEmpty();
        $validatorNotEmpty->setMessage("A mező nem lehet üres!");

# Név
        $name = new Zend_Form_Element_Text('name');
        $name->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator($validatorNotEmpty)
                ->setAttrib("class", "text_input");

        $name->removeDecorator('Errors');

# E-mail

        $email = new Zend_Form_Element_Text('email');
        $email->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addFilter('StringToLower')
                ->addValidator($validatorNotEmpty)
                ->setAttrib("class", "text_input");

        $email->removeDecorator('Errors');

# Beosztás
        $position = new Zend_Form_Element_Text('position');
        $position->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator($validatorNotEmpty)
                ->setAttrib("class", "text_input");

        $position->removeDecorator('Errors');

# Munkahely neve
        $jobName = new Zend_Form_Element_Text('job_name');
        $jobName->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator($validatorNotEmpty)
                ->setAttrib("class", "text_input");

        $jobName->removeDecorator('Errors');

# Cím
        $address = new Zend_Form_Element_Text('address');
        $address->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator($validatorNotEmpty)
                ->setAttrib("class", "text_input");

        $address->removeDecorator('Errors');


# Telefon
        $phone = new Zend_Form_Element_Text('phone');
        $phone->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator($validatorNotEmpty)
                ->setAttrib("class", "text_input");

        $phone->removeDecorator('Errors');

# Üzenet
        $message = new Zend_Form_Element_Textarea('message');
        $message->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator($validatorNotEmpty)
                ->setAttrib("class", "textarea");

        $message->removeDecorator('Errors');



# Submit
        $submit = new Zend_Form_Element_Image('küldés');
        //$submit->setAttrib("class", "suggest rightbox-submit");
        //$submit->setValue("");
        $path = "/public/skins/blues/images/elkuld.jpg";
        $submit->setImage($path);
        $submit->setAttrib("style", "margin-top: 12px");

        $this->setDecorators(array(
            'FormElements',
            array(array('data' => 'HtmlTag'), array('tag' => 'table', 'width' => '730', 'cellpadding' => '1', 'cellspacing' => '2')),
            'Form'
        ));

        $this->addElements(
                array(
                    $name,
                    $email,
                    $position,
                    $jobName,
                    $address,
                    $phone,
                    $message,
                    $submit
        ));
    }

}

?>
