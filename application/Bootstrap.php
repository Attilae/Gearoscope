<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    protected function _initDoctype() {
        $doctypeHelper = new Zend_View_Helper_Doctype ();
        $doctypeHelper->doctype('XHTML1_STRICT');
    }

    protected function _initView() {
        $view = new Zend_View ();
        $view->doctype('XHTML1_STRICT');
        $view->headTitle('CMS');
        $view->skin = 'gearoscope';
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
        $viewRenderer->setView($view);
        return $view;
    }

    protected function _initEmailServer() {
        ini_set("SMTP", "mail.tersege.hu");
        /*   ini_set("smtp_port", "2225");
          ini_set("username", "info@tersege.hu");
          ini_set("password", "atersegge10");
          //2225 */
        $config = array('auth' => 'login',
            'username' => 'info@tersege.hu',
            'password' => 'atersegge10',
            'port' => 2225);

        $transport = new Zend_Mail_Transport_Smtp('mail.tersege.hu', $config);
        
        $registry = Zend_Registry::getInstance();
        $registry->set('Zend_SMTP_Transport', $transport);
    }

    protected function _initViewHelpers() {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->addHelperPath('Application\View\Helper', 'Application_View_Helper_');
        return $view;
    }

    protected function _initConfig() {
        # get config
        $config = new Zend_Config_Ini(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'configs' . DIRECTORY_SEPARATOR . 'application.ini', APPLICATION_ENV);

        # get registery
        $this->_registry = Zend_Registry::getInstance();

        # save new database adapter to registry
        $this->_registry->config = new stdClass ();
        $this->_registry->config->application = $config;
    }

    protected function _initTmpDirectory() {
        # check tmp directory is writable
        if (!is_writable($this->_registry->config->application->logs->tmpDir)) {
            throw new Exception('Error: tmp dir is not writable ( ' . $this->_registry->config->application->logs->tmpDir . '), check folder/file permissions');
        }
    }

    protected function _initLogger() {
        # log file
        $error_log = $this->_registry->config->application->logs->tmpDir . DIRECTORY_SEPARATOR . $this->_registry->config->application->logs->error;

        # create log file if does not exist
        if (!file_exists($error_log)) {
            $date = new Zend_Date ();
            file_put_contents($error_log, 'Error log file created on: ' . $date->toString('YYYY-MM-dd HH:mm:ss') . "\n\n");
        }

        # check log file is writable
        if (!is_writable($error_log)) {
            throw new Exception('Error: log file is not writable ( ' . $error_log . '), check folder/file permissions');
        }

        # create logger object
        $writer = new Zend_Log_Writer_Stream($error_log);
        $logger = new Zend_Log($writer);

        $this->_registry->logger = $logger;
        
        $registry = Zend_Registry::getInstance();
        $registry->set('Zend_Logger', $logger);
    }

    protected function _initAutoload() {
        $autoLoader = Zend_Loader_Autoloader::getInstance();
        $autoLoader->registerNamespace('CMS_');
        $autoLoader->registerNamespace('FB_');
        $autoLoader->registerNamespace('ZendX_');
        $resourceLoader = new Zend_Loader_Autoloader_Resource(array('basePath' => APPLICATION_PATH, 'namespace' => '', 'resourceTypes' => array('form' => array('path' => 'forms/', 'namespace' => 'Form_'), 'model' => array('path' => 'models/', 'namespace' => 'Model_'))));
        return $autoLoader;
    }

    protected function _initTranslate() {
        $translate = new Zend_Translate('tmx', APPLICATION_PATH . '/../data/langs/translation.xml', 'hu');
        $registry = Zend_Registry::getInstance();
        $registry->set('Zend_Translate', $translate);
        $translate->setLocale('hu');
    }

    protected function _initRoutes() {
        $this->bootstrap('FrontController');
        $this->_frontController = $this->getResource('FrontController');
        $router = $this->_frontController->getRouter();

        $langRoute = new Zend_Controller_Router_Route(':lang/', array('lang' => 'hu'));

        $defaultRoute = new Zend_Controller_Router_Route(':controller/:action/*', array('module' => 'default', 'controller' => 'index', 'action' => 'index'));

        $defaultRoute = $langRoute->chain($defaultRoute);

        $router->addRoute('langRoute', $langRoute);
        $router->addRoute('defaultRoute', $defaultRoute);
    }

    protected function _initLanguage() {
        $front = Zend_Controller_Front::getInstance();
        $front->registerPlugin(new CMS_Controller_Plugin_Language());
    }

    protected function _initLocale() {
        $request = explode("/", $_SERVER ["REQUEST_URI"]);
        if ($request [1] == "en") {
            $locale = new Zend_Locale('en');
        } else {
            $locale = new Zend_Locale('hu');
        }
        $registry = Zend_Registry::getInstance();
        $registry->set('Zend_Locale', $locale);
    }

}

