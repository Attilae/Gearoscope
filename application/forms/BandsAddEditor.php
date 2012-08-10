<?php

class Form_BandsAddEditor extends Zend_Form {
	
	public function __construct() {
		parent::__construct ( $options );
		
		$validatorNotEmpty = new Zend_Validate_NotEmpty ();
		$validatorNotEmpty->setMessage ( "A mező nem lehet üres!" );
		
		$validatorEmail = new Zend_Validate_EmailAddress ();
		$validatorEmail->setMessage ( "Kérjük adja meg helyesen az e-mail címét!", 'emailAddressInvalid' );
		$validatorEmail->setMessage ( "Kérjük adja meg helyesen az e-mail címét!", 'emailAddressInvalidFormat' );				
		
		$this->setName ( 'Posts' );
		
		$editor = new Zend_Form_Element_Text ( 'editor' );
		$editor->setLabel ( 'Admin neve' )->setAttrib ( "class", "login-inp form-input" )->setAttrib ( 'title', 'Admin neve' )->setRequired ( true )->addFilter ( 'StripTags' )->addFilter ( 'StringTrim' )->addValidator ( $validatorNotEmpty )->addValidator ( $validatorEmail );
		
		$submit = new Zend_Form_Element_Submit ( 'submit' );
		$submit->setAttrib ( 'id', 'submitbutton' );
		$submit->setLabel ( "Mentés" );
		$submit->setAttrib ( "class", "submit-login" );
		$submit->setAttrib ( 'style', 'clear: both;' );
		
		$this->addElements ( array ($editor, $submit ) );
	}

}