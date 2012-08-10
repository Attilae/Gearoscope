<?php

/**
 * Auth Bootstrap
 *
 * @author          Eddie Jaoude
 * @package       Auth Module
 *
 */
class Xls_Bootstrap extends Zend_Application_Module_Bootstrap {

    /**
     * Auto load default module classes
     *
     * @author          Eddie Jaoude
     * @param           void
     * @return           object $moduleLoader
     *
     */
    protected function _initAutoload() {
        $moduleLoader = new Zend_Application_Module_Autoloader(array(
                    'namespace' => 'Xls_',
                    'basePath' => APPLICATION_PATH . '/modules/xls'));

        $moduleLoader->addResourceType('controllerhelper',
                'controllers/helpers', 'Controller_Helper');

        return $moduleLoader;
    }

    /**
     * Load BaseController
     *
     * @author          Eddie Jaoude
     * @param           void
     * @return           void
     *
     */
//    protected function _initBaseController() {
//        # base controller - can this be moved and autoloaded?
//        require_once('controllers/Xls2BaseController.php');
//    }


//
//    protected function _initActionHelpers() {
//
//        // path for module-specific controller helpers
//        Zend_Controller_Action_HelperBroker::addPath( APPLICATION_PATH . '/modules/admin/controllers/helpers', 'Xls_Controller_Helper_');
//
//        // initialize the event helper with entity manager
//        $this->bootstrap('autoload');
//        $application = $this->getApplication();
//        $application->bootstrap('doctrine');
//        if (isset($application->_registry->doctrine->_em)){
//            Xls_Controller_Helper_Event::$defaultEntityManager = $application->_registry->doctrine->_em;
//        }
//    }
}

