<?php
class CMS_Layout_Plugin_Sidebar extends Zend_Controller_Plugin_Abstract
{
   public function preDispatch(Zend_Controller_Request_Abstract $request)
   {
      $layout = Zend_Layout::getMvcInstance();
      $view = $layout->getView();

      $view->whatever = 'foo';
   }
}