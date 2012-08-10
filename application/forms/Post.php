<?php

class Form_Post extends Zend_Form {

    public function __construct() {
        parent::__construct($options);

        $validatorNotEmpty = new Zend_Validate_NotEmpty();
        $validatorNotEmpty->setMessage("A mező nem lehet üres!");

        $this->setName('Posts');
//        $id = new Zend_Form_Element_Hidden('id');
        $title = new Zend_Form_Element_Text('title');
        $title->setLabel('Cím')
                ->setAttrib('title', 'A bejegyzés címe')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator($validatorNotEmpty);

        $topic = new Zend_Form_Element_Select('topic');
        $topic->setLabel('Kategória')
                ->setRequired(true)
                ->setAttrib('title', 'Melyik topicba kerüljön a bejegyzés?')
                ->addValidator($validatorNotEmpty);
        $topic->addMultiOptions(array(
            "" => "",
            "samsung" => "Samsung",
            "smartphones" => "Okostelefonok",
            "blog" => "blog"
        ));

        $photo = $this->createElement('file', 'photo');
        $photo->setLabel('Kép: ');
        $photo->setDestination(APPLICATION_PATH . '/../public/uploads/posts/');
//        $photo->addFilter('Rename',
//                array('source' => $this->file,
//                    'target' => APPLICATION_PATH . '/../public/uploads/posts/' . CMS_Validate_Internim::getMicrotime() . '.jpg',
//                    'overwrite' => true));
        $photo->addValidator('Count', false, 1);
        $photo->addValidator('Size', false, 1024000);
        $photo->addValidator('Extension', false, 'jpg, gif, png');

        $lead = new Zend_Form_Element_Textarea('lead');
        $lead->addValidator($validatorNotEmpty);
        $lead->setLabel('Bevezető szöveg: ')
                ->setRequired(true)
                ->setAttrib('rows', 20)
                ->setAttrib('cols', 50)
                ->setAttrib('title', 'A bejegyzés címe')
                ->setAttrib('style', 'resize: none; width: 625px;height: 150px;')
                ->addFilter('StringTrim')
                ->setAttrib('onKeyDown', 'textCounter(this,this.form.counter,400);')
                ->setAttrib('onKeyUp', 'textCounter(this,this.form.counter,400);');

        $video = new Zend_Form_Element_Text('video');
        $video->setLabel('Videó: ')
                ->addFilter('StringTrim')
                ->addFilter(new ZC_Filter_HTMLPurifier());

//        $gallery = new Zend_Form_Element_Text('gallery');
//        $gallery->setLabel('Galéria: ')
//                ->setRequired(true)
//                ->addFilter('StringTrim')
//                ->addFilter(new ZC_Filter_HTMLPurifier())
//                ->addValidator('NotEmpty');

        $content = new Zend_Form_Element_Textarea('content');
        $content->setLabel('Tartalom: ')
                ->setRequired(true)
                ->setAttrib('rows', 20)
                ->setAttrib('cols', 50)
                ->addFilter('StringTrim')
                ->addValidator($validatorNotEmpty)
                ->setAttrib('class', 'mceEditor')
                ->setAttrib('style', 'resize: none; width: 625px;');

        $tags = new Zend_Form_Element_Textarea('tags');
        $tags->setLabel('Címkék: ')
                ->setRequired(true)
                ->setAttrib("style", "width: 625px; height: 100px;")
                ->addFilter('StringTrim')
                ->addValidator($validatorNotEmpty);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
        $submit->setLabel("Elküld");
        $submit->setAttrib('style', 'clear: both;');

        $presave = new Zend_Form_Element_Submit('presave');
        $presave->setAttrib('id', 'presavebutton');
        $presave->setLabel("Előnézet");
        $presave->setAttrib('style', 'clear: both;');

        $delete = new Zend_Form_Element_Submit('delete');
        $delete->setLabel("Törlés");
        $delete->setAttrib('id', 'deletebutton');
        $delete->setAttrib('style', 'clear: both;');

        $this->addElements(array($id, $title, $photo, $topic, $lead, $video,
//            $gallery,
            $content, $tags, $submit, $presave, $delete));
    }

}