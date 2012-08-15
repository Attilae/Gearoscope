<?php

class Form_Band extends Zend_Form {

    public function __construct() {
        parent::__construct($options);

        $validatorNotEmpty = new Zend_Validate_NotEmpty();
        $validatorNotEmpty->setMessage("A mező nem lehet üres!");

        $band_name = new Zend_Form_Element_Text('band_name');
        $band_name->setLabel('Zenekar neve')
                ->setRequired(true)
                ->setAttrib('class', 'form-input')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator($validatorNotEmpty)
                ->setDecorators( array( 'ViewHelper', 'Errors' ) );

        $formation_year = new Zend_Form_Element_Text('formation_year');
        $formation_year->setLabel('Alakulás éve:')
                ->setAttrib('class', 'form-input')
                ->addValidator(new Zend_Validate_Date(
                                array(
                                    'format' => 'yyyy',
                        )))
                ->setRequired(true)
                ->setDecorators( array( 'ViewHelper', 'Errors' ) );

		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
		$view = $viewRenderer->view;
		
		$baseUrl = $view->baseUrl();                
                
        $style = new ZendX_JQuery_Form_Element_AutoComplete('style');
        $style->setAttrib('autocomplete', 'off');
        $style->setLabel('Stílus')
                ->setAttrib('class', 'form-input')
                ->setJQueryParam("source", $baseUrl.'/hu/bands/styles')
                ->setJQueryParams(array("select" => new Zend_Json_Expr(
                            'function(event,ui) { $("#autoid").val(ui.item.id) }')
                ));
        $style->setDecorators(
            array(
                'UiWidgetElement'            
            )
        );

        $website = new Zend_Form_Element_Text('website');
        $website->setLabel('Honlap')
                ->setAttrib('class', 'form-input')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator($validatorNotEmpty)
                ->setDecorators( array( 'ViewHelper', 'Errors' ) );
        
        $description = new Zend_Form_Element_Textarea('description');
        $description->setAttrib('class', 'textarea')
                    ->setAttrib('rows', '30')
                    ->setAttrib('resize', 'none')
                    ->setDecorators(array('Viewhelper', 'Errors'))				
                    ->setRequired(true);

        $photo = $this->createElement('file', 'photo');
        $photo->setLabel('Kép: ');
        $photo->setDestination(APPLICATION_PATH . '/../public/uploads/bands/');
        $photo->addValidator('Count', false, 1);
        $photo->addValidator('Size', false, 2048000);
        $photo->addValidator('Extension', false, 'jpg, gif, png');
        $photo->setDecorators( array( 'File', 'Errors' ) );

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel("Elküld");
        $submit->setDecorators(array('ViewHelper'));


        $this->addElements(array($id, $band_name, $formation_year, $style, $website, $description, $photo, $submit));
    }

}