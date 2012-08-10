<?php

class Form_GearsItem extends Zend_Form {

	public function __construct() {
		parent::__construct($options);

		$validatorNotEmpty = new Zend_Validate_NotEmpty();
		$validatorNotEmpty->setMessage("A mez� nem lehet �res!");

		$this->setName('Posts');

		$title = new Zend_Form_Element_Text('title');
		$title->setLabel('Cím')
			->setAttrib("class", "login-inp")
			->setAttrib('title', 'A bejegyzés címe')
			->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator($validatorNotEmpty);

		$subcategories = $this->createElement('select', 'subcategory');		
		$modelCategory = new Model_DbTable_GearsCategory();
		$categories = $modelGearsCategory->findAll();
		
		$modelSubcategory = new Model_DbTable_GearsSubcategory();
		$modelSubsubcategory = new Model_DbTable_GearsSubsubcategory();
		
		$i=0;
		foreach ($categories as $category):
			$subcategory = $modelSubcategory->findByCategory($category["cat_id"]);
			foreach($subcategory as $subcat):
				$subsubcategory = $modelSubsubcategory->findByCategory($subcat["subcat_id"]);
				foreach($subsubcategory as $subsubcat):
					$categoryTitle = $category["title"];
					$subcategoryTitle = $subcat["title"];
					$subsubcategoryTitle = $subsubcat["title"];					
					$subcategories->addMultiOptions(array($subsubcat["sub_subcat_id"] => $categoryTitle . " - " . $subcategoryTitle . " - " . $subsubcategoryTitle));			
					$i++;
				endforeach;
			endforeach;
		endforeach;	
		//$subcategories->addMultiOptions($categoriesArray);
		$subcategories->setRequired(true);
		$subcategories->addFilter('StripTags');
		$subcategories->addValidator($validatorNotEmpty);

		$photo = $this->createElement('file', 'photo');
		$photo->setLabel('Kép: ');
		$photo->setDestination(APPLICATION_PATH . '/../public/uploads/activities/');
		$photo->addFilter('Rename',
		array('source' => $this->file,
                    'target' => APPLICATION_PATH . '/../public/uploads/activities/' . CMS_Validate_Internim::getMicrotime() . '.jpg',
                    'overwrite' => true));
		$photo->addValidator('Count', false, 1);
		$photo->addValidator('Size', false, 1024000);
		$photo->addValidator('Extension', false, 'jpg');

		$lead = new Zend_Form_Element_Textarea('lead');
		$lead->addValidator($validatorNotEmpty);
		$lead->setLabel('Bevezető szöveg: ')
		->setRequired(true)
		->setAttrib('rows', 5)
		->setAttrib('cols', 50)
		->setAttrib('class', 'mceEditor')
		->setAttrib('title', 'A bejegyzés címe')
		->addFilter('StringTrim')
		->setAttrib('onKeyDown', 'textCounter(this,this.form.counter,400);')
		->setAttrib('onKeyUp', 'textCounter(this,this.form.counter,400);');

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'submitbutton');
		$submit->setLabel("Elküld");
		$submit->setAttrib("class", "submit-login");
		$submit->setAttrib('style', 'clear: both;');

		$this->addElements(array($title, $lead, $subcategories, $content, $photo, $submit));
	}

}