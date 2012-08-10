<?php

class GearsSubsubcategoryController extends Zend_Controller_Action {
	
	public function indexAction() {
		$item_id = $this->_request->getParam ( "id" );
		
		$modelItems = new Model_DbTable_Items ();
		$result = $modelItems->getItemsActive ( $item_id );
		
		$this->view->item = $result;
	}
	
	public function viewAction() {
		
		$post_id = $this->_request->getParam ( "id" );
		
		$post = new Model_DbTable_Posts ();
		$result = $post->getPost ( $post_id );
		
		$result = $post->getPost ( $post_id );
		$this->view->post = $result;
	
	}
	
	public function addAction() {
		
		parent::init ();
		$layout = Zend_Layout::getMvcInstance ();
		$layout->setLayoutPath ( APPLICATION_PATH . '/layouts/scripts' );
		$layout->setLayout ( 'admin' );
		
		if (! Zend_Auth::getInstance ()->hasIdentity ()) {
			$this->_redirect ( 'index/index' );
		}
		
		$baseUrl = $this->view->baseUrl ();
		$locale = Zend_Registry::get ( 'Zend_Locale' );
		
		$subcategory_id = $this->_request->getParam ( 'subcategoryid' );
		
		$form = new Form_Category ();
		
		if ($this->getRequest ()->isPost ()) {
			if ($form->isValid ( $this->getRequest ()->getPost () )) {
				
				$subsubcategory = $form->getValue ( 'title' );
				
				$modelCategories = new Model_DbTable_GearsSubsubCategories ();
				$modelCategories->saveCategory ( $subsubcategory, $subcategory_id );
				
				$this->_redirect ( $locale . '/gearssubsubcategory/list/subcategoryid/' . $subcategory_id . '?create=1' );
			}
		}
		
		$this->view->form = $form;
	}
	
	public function useraddAction() {
		
		parent::init ();
		$layout = Zend_Layout::getMvcInstance ();
		$layout->setLayoutPath ( APPLICATION_PATH . '/layouts/scripts' );
		$layout->setLayout ( 'iframe' );
		
		if (! Zend_Auth::getInstance ()->hasIdentity ()) {
			$this->_redirect ( 'index/index' );
		}
		
		$baseUrl = $this->view->baseUrl ();
		$locale = Zend_Registry::get ( 'Zend_Locale' );
		
		$subcategory_id = $this->_request->getParam ( 'subcategoryid' );
		
		$modelSubcategories = new Model_DbTable_GearsSubcategories ();
		$subcategory = $modelSubcategories->find ( $subcategory_id )->current ();
		
		if (! $subcategory) {
			throw new Exception ( "Váratlan hiba történt!" );
		}
		
		$form = new Form_SubsubCategory ();
		
		if ($this->getRequest ()->isPost ()) {
			if ($form->isValid ( $this->getRequest ()->getPost () )) {
				
				$auth = Zend_Auth::getInstance ();
				$identity = $auth->getIdentity ();
				$user_id = $identity->user_id;
				
				$subsubcategory = $form->getValue ( 'title' );
				
				$modelCategories = new Model_DbTable_GearsSubsubCategories ();
				$subsubcategoryid = $modelCategories->saveCategory ( $subsubcategory, $subcategory_id, $user_id );
				
				//$this->_redirect($locale . '/gearssubsubcategory/list/subcategoryid/'.$subcategory_id.'?create=1');
				

				$this->view->headScript ()->prependScript ( '
					$(document).ready(function(){
						parent.$("#subsubcategory").append(
        					$("<option></option>").val("' . $subsubcategoryid . '").html("' . $subsubcategory . '")
        				);
        				parent.$("#subsubcategory").val("' . $subsubcategoryid . '");
						parent.$.fn.colorbox.close();
					});' );
			
			}
		}
		
		$this->view->form = $form;
	}
	
	public function editAction() {
		
		parent::init ();
		$layout = Zend_Layout::getMvcInstance ();
		$layout->setLayoutPath ( APPLICATION_PATH . '/layouts/scripts' );
		$layout->setLayout ( 'admin' );
		
		$request = $this->getRequest ();
		$subsubcat_id = ( int ) $request->getParam ( 'id' );
		
		$modelSubsubcategories = new Model_DbTable_Subsubcategory ();
		$category = $modelSubsubcategories->getCategory ( $subsubcat_id );
		
		//megkeressük, a szülőkategória id-ját az átirányításhoz
		$modelSubcategories = new Model_DbTable_Subcategory ();
		$subcategory_id = $modelSubcategories->getCategory ( $category [0] ["subcat_id"] );
		
		$form = new Form_Category ();
		$form->getElement ( 'title' )->setValue ( CMS_Validate_Charactertransform::specToNormal ( $category [0] ["title"] ) );
		
		if ($this->getRequest ()->isPost ()) {
			if ($form->isValid ( $request->getPost () )) {
				
				$title = CMS_Validate_Charactertransform::normalToSpec ( $form->getValue ( 'title' ) );
				
				$modelSubsubcategories->updateCategory ( $subsubcat_id, $title );
				
				$this->_redirect ( '/subsubcategory/list/id/' . $subcategory_id [0] ["subcat_id"] . '?edit=1' );
			}
		}
		$this->view->form = $form;
	}
	
	public function listAction() {
		
		parent::init ();
		$layout = Zend_Layout::getMvcInstance ();
		$layout->setLayoutPath ( APPLICATION_PATH . '/layouts/scripts' );
		$layout->setLayout ( 'admin' );
		
		$subcategory_id = $this->_request->getParam ( "subcategoryid" );
		
		$modelSubsubcategories = new Model_DbTable_GearsSubsubcategories ();
		
		$subsubcategories = $modelSubsubcategories->findByCategory ( $subcategory_id );
		$this->view->subsubcategories = $subsubcategories;
		
		$this->view->subcategory_id = $subcategory_id;
	
	}
	
	public function deleteAction() {
		
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->getHelper ( 'layout' )->disableLayout ();
		
		$id = $this->_request->getParam ( 'id' );
		
		$modelCategories = new Model_DbTable_Category ();
		$modelCategories->deleteCategory ( $id );
		
		$this->_redirect ( '/category/list?delete=1' );
	}
	
	public function activeAction() {
		$id = $this->_request->getParam ( "id" );
		
		$modelItems = new Model_DbTable_Items ();
		$modelItems->setActive ( $id );
		
		$this->_redirect ( "/item/list?active=1" );
	}
	
	public function deactiveAction() {
		$id = $this->_request->getParam ( "id" );
		
		$modelItems = new Model_DbTable_Items ();
		$modelItems->setDeactive ( $id );
		
		$this->_redirect ( "/item/list?deactive=1" );
	}

}
