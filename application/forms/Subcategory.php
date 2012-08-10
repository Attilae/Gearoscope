<?php

class Form_Subcategory extends Zend_Form {

	public function __construct() {
		parent::__construct($options);

		$validatorNotEmpty = new Zend_Validate_NotEmpty();
		$validatorNotEmpty->setMessage("A mezõ nem lehet üres!");

		$this->setName('Posts');

		$title = new Zend_Form_Element_Text('title');
		$title->setLabel('Cím')
				->setAttrib("class", "login-inp")
				->setAttrib('title', 'A bejegyzés címe')
				->setRequired(true)
				->addFilter('StripTags')
				->addFilter('StringTrim')
				->addValidator($validatorNotEmpty);

		$category = $this->createElement('select', 'category');
		$modelCategory = new Model_DbTable_Category();
		$categories = $modelCategory->findAll();

		foreach ($categories as $cat):			
			$category->addMultiOptions(array($cat["cat_id"] => $cat["title"]));					
		endforeach;
		$category->setRequired(true);
		$category->addFilter('StripTags');
		$category->addValidator($validatorNotEmpty);

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'submitbutton');
		$submit->setLabel("Elküld");
		$submit->setAttrib("class", "submit-login");
		$submit->setAttrib('style', 'clear: both;');

		$this->addElements(array($title, $category, $submit));
	}

}