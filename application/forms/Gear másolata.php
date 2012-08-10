<?php

class Form_Gear extends Zend_Form {

    public function __construct() {
        parent::__construct($options);

        $validatorNotEmpty = new Zend_Validate_NotEmpty();
        $validatorNotEmpty->setMessage("A mező nem lehet üres!");

        $gear_name = new Zend_Form_Element_Text('gear_name');
        $gear_name->setRequired(true)
                ->setAttrib('class', 'form-input')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator($validatorNotEmpty);
                
		$serial_number = new Zend_Form_Element_Text('serial_number');
		$serial_number->setAttrib('class', 'form-input')					
			->setRequired(true);		
			
		$category = $this->createElement('select', 'category');
		$modelCategory = new Model_DbTable_GearsCategories();
		$categories = $modelCategory->findAll();
		$category->addMultiOptions(array('' => ''));
		foreach ($categories as $cat):			
			$category->addMultiOptions(array($cat["gears_category_id"] => $cat["category"]));					
		endforeach;
		$category->setRequired(true);
		$category->addFilter('StripTags');
		$category->addValidator($validatorNotEmpty);
		
		$subcategory = $this->createElement('select', 'subcategory');
		$subcategory->setRequired(true);
		$subcategory->addFilter('StripTags');
		$subcategory->setRegisterInArrayValidator(false);
		$subcategory->addValidator($validatorNotEmpty);
		
		$subsubcategory = $this->createElement('select', 'subsubcategory');
		$subsubcategory->setRequired(true);
		$subsubcategory->addFilter('StripTags');
		$subsubcategory->setRegisterInArrayValidator(false);
		$subsubcategory->addValidator($validatorNotEmpty);

        $photo = $this->createElement('text', 'photo');

        $thumbnail = $this->createElement('text', 'thumbnail');  

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
        $submit->setLabel("Elküld");
        $submit->setAttrib('style', 'clear: both;');

        $this->addElements(array($id, $gear_name, $serial_number, $category, $subcategory, $subsubcategory, $photo, $thumbnail, $submit));
    }

}