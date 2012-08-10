<?php 
// view helper
class Application_View_Helper_Locale extends Zend_View_Helper_Abstract {
    function getLocale() {    	
    	$locale = Zend_Registry::get('Zend_Locale');    	      
        return $locale->getLanguage();
    }
}
?>