<?php

class GearscategoryController extends Zend_Controller_Action {

    public function init() {
        $this->view->addHelperPath(
                'ZendX/JQuery/View/Helper'
                , 'ZendX_JQuery_View_Helper');
    }

    public function indexAction() {
        $item_id = $this->_request->getParam("id");

        $modelItems = new Model_DbTable_GearsItems();
        $result = $modelItems->getItems($item_id);

        $this->view->item = $result;
    }

    public function categorychangeAction() {
        
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $category = $_GET["category"];

        $modelSubcategories = new Model_DbTable_GearsSubcategories();
        $subcategories = $modelSubcategories->findByCategory($category);

        $i = 0;
        $subcategoriesArray = array();
        foreach ($subcategories as $subcategory):
            $subcategoriesArray[$i] = array(
                "key" => $subcategory['gears_subcategory_id'],
                "value" => $subcategory['subcategory']
            );
            $i++;
        endforeach;

        print json_encode($subcategoriesArray);
    }

    public function viewAction() {
        
    }

    public function addAction() {

        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('admin');

        $baseUrl = $this->view->baseUrl();
        $locale = Zend_Registry::get('Zend_Locale');

        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_redirect('index/index');
        }

        $form = new Form_Category();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {

                $category = $form->getValue('title');

                $modelCategories = new Model_DbTable_GearsCategories();
                $modelCategories->saveCategory($category);

                $this->_redirect($locale . '/gearscategory/list?create=1');
            }
        }

        $this->view->form = $form;
    }

    public function editAction() {

        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('admin');

        $baseUrl = $this->view->baseUrl();
        $locale = Zend_Registry::get('Zend_Locale');

        $request = $this->getRequest();
        $cat_id = (int) $request->getParam('id');

        $modelCategories = new Model_DbTable_GearsCategories();
        $categoryData = $modelCategories->getCategory($cat_id);

        $form = new Form_Category();
        $form->getElement('title')->setValue($categoryData[0]["category"]);

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($request->getPost())) {

                $category = $form->getValue('title');

                $modelCategories->updateCategory($cat_id, $category);

                $this->_redirect($locale . '/gearscategory/list?edit=1');
            }
        }
        $this->view->form = $form;
    }

    public function listAction() {
        parent::init();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts');
        $layout->setLayout('admin');

        $modelCategories = new Model_DbTable_GearsCategories();

        $categories = $modelCategories->getCategories();
        $this->view->categories = $categories;
    }

    public function deleteAction() {

        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->getHelper('layout')->disableLayout();

        $id = $this->_request->getParam('id');

        $modelCategories = new Model_DbTable_Category();
        $modelCategories->deleteCategory($id);

        $this->_redirect('/category/list?delete=1');
    }

    public function activeAction() {
        $id = $this->_request->getParam("id");

        $modelItems = new Model_DbTable_Items();
        $modelItems->setActive($id);

        $this->_redirect("/item/list?active=1");
    }

    public function deactiveAction() {
        $id = $this->_request->getParam("id");

        $modelItems = new Model_DbTable_Items();
        $modelItems->setDeactive($id);

        $this->_redirect("/item/list?deactive=1");
    }

}
