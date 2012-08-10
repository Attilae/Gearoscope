<?php

class GearsSubcategoryController extends Zend_Controller_Action {
	
	public function init()
    {    	
    	$this->view->addHelperPath(
                'ZendX/JQuery/View/Helper'
                ,'ZendX_JQuery_View_Helper');
    }
	
	public function indexAction() {
		$item_id = $this->_request->getParam("id");

		$modelItems = new Model_DbTable_Items();
		$result = $modelItems->getItems($item_id);
		
		$this->view->item = $result;
	}
	
	public function categorychangeAction() {
		
		$this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        
        $category = $_GET["category"];   

        $modelSubsubcategories = new Model_DbTable_GearsSubsubcategories();
        $subsubcategories = $modelSubsubcategories->findByCategory($category);        
        
        $i = 0;
        $subsubcategoriesArray = array();
        foreach ($subsubcategories as $subsubcategory):
            $subsubcategoriesArray[$i] = array(
                "key" => $subsubcategory['gears_subsubcategory_id'],
                "value" => $subsubcategory['subsubcategory']
            );
            $i++;
        endforeach;
        
        print json_encode($subsubcategoriesArray);
	}
	
	public function viewAction() {
			
		$post_id = $this->_request->getParam("id");

		$post = new Model_DbTable_Posts();
		$result = $post->getPost($post_id);

		$result = $post->getPost($post_id);
		$this->view->post = $result;

	}

	public function addAction() {

		parent::init();
		$layout = Zend_Layout::getMvcInstance();
		$layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
		$layout->setLayout('admin');
		
		$baseUrl =  $this->view->baseUrl();
		$locale = Zend_Registry::get('Zend_Locale');
		
		$category_id = $this->_request->getParam('categoryid');

		if (!Zend_Auth::getInstance()->hasIdentity()) {
			$this->_redirect('index/index');
		}

		$form = new Form_Category();

		if ($this->getRequest()->isPost()) {
			if ($form->isValid($this->getRequest()->getPost())) {

				$category = $form->getValue('title');				

				$modelSubcategories = new Model_DbTable_GearsSubcategories();
				$modelSubcategories->saveCategory($category, $category_id);

				$this->_redirect('/' . $locale . '/gearssubcategory/list/categoryid/'.$category_id.'?create=1');
			}
		}

		$this->view->form = $form;
	}

	public function editAction() {

		parent::init();
		$layout = Zend_Layout::getMvcInstance();
		$layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
		$layout->setLayout('admin');
		
		$baseUrl =  $this->view->baseUrl();
		$locale = Zend_Registry::get('Zend_Locale');

		$request = $this->getRequest();
		$subcategory_id = (int) $request->getParam('subcategoryid');

		$modelSubcategories = new Model_DbTable_GearsSubcategories();
		$categoryData = $modelSubcategories->getCategory($subcategory_id);
		
		//megkeressük, a szülőkategória id-ját az átirányításhoz
		$modelCategory = new Model_DbTable_GearsCategories();
		$category_id = $modelCategory->getCategory($categoryData[0]["gears_category_id"]); 

		$form = new Form_Category();
		$form->getElement('title')->setValue($categoryData[0]["subcategory"]);
			
		if ($this->getRequest()->isPost()) {
			if ($form->isValid($request->getPost())) {

				$subcategory = $form->getValue('title');

				$modelSubcategories->updateCategory($subcategory_id, $subcategory);

				$this->_redirect('/' . $locale . '/gearssubcategory/list/categoryid/'.$category_id[0]["gears_category_id"].'?edit=1');
			}
		}
		$this->view->form = $form;
	}

	public function listAction() {
		parent::init();
		$layout = Zend_Layout::getMvcInstance();
		$layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
		$layout->setLayout('admin');
		
		$categoryid = $this->_request->getParam('categoryid');

		$modelSubcategories = new Model_DbTable_GearsSubcategories();

		$subcategories = $modelSubcategories->findByCategory($categoryid);
		$this->view->subcategories = $subcategories;
		
		$this->view->categoryid = $categoryid;
	}

	public function deleteAction() {
			
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->getHelper('layout')->disableLayout();

		$id = $this->_request->getParam('id');
			
		$modelCategories = new Model_DbTable_Category();
		$modelCategories->deleteCategory($id);

		$this->_redirect('/category/list?delete=1');
		
	}	

}
