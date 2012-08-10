<?php 
// view helper
class Zend_View_Helper_Locale extends Zend_View_Helper_Abstract
{
    public function locale() {
        $locale = Zend_Registry::get('Zend_Locale'); 
        return $locale->getLanguage();
    }
}
?>