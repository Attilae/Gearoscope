<?php

class Form_PostEdit extends Zend_Form {

    public function __construct($item) {
        parent::__construct($options);
        $this->setName('Posts');
        $id = new Zend_Form_Element_Hidden('id');

        $title = new Zend_Form_Element_Text('title');
        $title->setLabel('Title')
                ->setValue($item[0]['title'])
                ->setAttrib('title', 'A bejegyzés címe')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty');

        $topic = new Zend_Form_Element_Select('topic');
        $topic->setLabel('Téma')
                ->setValue($item[0]['topic'])
                ->setRequired(true)
                ->setAttrib('title', 'Melyik topicba kerüljön a bejegyzés?');
        $topic->addMultiOptions(array(
            "samsung" => "Samsung",
            "new-phones" => "Új készülékek",
            "smartlife" => "Smartlife"
        ));

         // create the image preview
        $photoPreview = $this->createElement('image', 'photo');
        // element options
        $photoPreview->setLabel('Preview: ');
        $photoPreview->setAttrib('style', 'width:200px;height:auto;');
        // add the element to the form
        $photoPreview->setImage("/../public/uploads/posts/" . $item[0]["photo"]);
        $this->addElement($photoPreview);

// create new element
        $photo = $this->createElement('file', 'photo_preview');
        // element options
        $photo->setLabel('Kép: ');
        $photo->setDestination(APPLICATION_PATH . '/../public/uploads/posts/');
        // ensure only 1 file
        $photo->addValidator('Count', false, 1);
        // limit to 100K
        $photo->addValidator('Size', false, 1024000);
        // only JPEG, PNG, and GIFs
        $photo->addValidator('Extension', false, 'jpg');
        $photo->addFilter('Rename',
                array('source' => $this->file,
                    'target' => APPLICATION_PATH . '/../public/uploads/posts/' . CMS_Validate_Internim::getMicrotime() . '.jpg',
                    'overwrite' => true));

        $allowedTags = array(
            'a' => array('href', 'title'),
            'strong',
            'img' => array('src', 'alt'),
            'ul',
            'ol',
            'li',
            'em',
            'u',
            'p',
            'strike');

        $lead = new Zend_Form_Element_Textarea('lead');
        $lead->setLabel('Bevezető szöveg: ')
                ->setRequired(true)
                ->setValue($item[0]["lead"])
                ->setAttrib('rows', 20)
                ->setAttrib('cols', 50)
                ->setAttrib('title', 'A bejegyzés címe')
                ->addFilter('StringTrim')
                /* 				->addFilter('StripTags', $allowedTags)
                 * 				Don't know why its not working ?
                 */
                ->addValidator('NotEmpty');

        $video = new Zend_Form_Element_Text('video');
        $video->setLabel('Videó: ')
                ->setRequired(true)
                ->setValue($item[0]["video"])
                ->addFilter('StringTrim')
                ->addFilter(new ZC_Filter_HTMLPurifier())
                /* 				->addFilter('StripTags', $allowedTags)
                 * 				Don't know why its not working ?
                 */
                ->addValidator('NotEmpty');

        $gallery = new Zend_Form_Element_Text('gallery');
        $gallery->setLabel('Galéria: ')
                ->setRequired(true)
                //->setValue($item["gallery"])
                ->addFilter('StringTrim')
                ->addFilter(new ZC_Filter_HTMLPurifier())
                /* 				->addFilter('StripTags', $allowedTags)
                 * 				Don't know why its not working ?
                 */
                ->addValidator('NotEmpty');

        $content = new Zend_Form_Element_Textarea('content');
        $content->setLabel('Tartalom: ')
                ->setRequired(true)
                ->setValue($item[0]["content"])
                ->setAttrib('rows', 20)
                ->setAttrib('cols', 50)
                ->addFilter('StringTrim')
                /* 				->addFilter('StripTags', $allowedTags)
                 * 				Don't know why its not working ?
                 */
                ->addValidator('NotEmpty');

        $tags = new Zend_Form_Element_Textarea('tags');
        $tags->setLabel('Címkék: ')
                ->setRequired(true)
                ->setAttrib('rows', 20)
                ->setAttrib('cols', 50)
                ->addFilter('StringTrim')
                /* 				->addFilter('StripTags', $allowedTags)
                 * 				Don't know why its not working ?
                 */
                ->addValidator('NotEmpty');

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
        $this->addElements(array($id, $title, $photo, $photoPreview, $topic, $lead, $video, $gallery,  $content, $tags, $submit));
    }

}