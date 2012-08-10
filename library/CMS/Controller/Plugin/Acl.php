<?php

class CMS_Controller_Plugin_Acl extends Zend_Controller_Plugin_Abstract {

    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        // set up acl
        $acl = new Zend_Acl();

        // add the roles
        $acl->addRole(new Zend_Acl_Role('guest'));
        $acl->addRole(new Zend_Acl_Role('user'), 'guest');
        $acl->addRole(new Zend_Acl_Role('administrator'), 'user');

        // add the resources

        $acl->add(new Zend_Acl_Resource('gearoscope'));
        $acl->add(new Zend_Acl_Resource('public'));
        $acl->add(new Zend_Acl_Resource('skins'));

        $acl->add(new Zend_Acl_Resource('admin'));
        $acl->add(new Zend_Acl_Resource('facebook'));
        $acl->add(new Zend_Acl_Resource('index'));
        $acl->add(new Zend_Acl_Resource('error'));
        $acl->add(new Zend_Acl_Resource('page'));
        $acl->add(new Zend_Acl_Resource('menu'));
        $acl->add(new Zend_Acl_Resource('news'));
        $acl->add(new Zend_Acl_Resource('bands'));
        $acl->add(new Zend_Acl_Resource('gears'));
        $acl->add(new Zend_Acl_Resource('gearscategory'));
        $acl->add(new Zend_Acl_Resource('gearssubcategory'));
        $acl->add(new Zend_Acl_Resource('gearssubsubcategory'));
        $acl->add(new Zend_Acl_Resource('video'));
        $acl->add(new Zend_Acl_Resource('sidebar'));
        $acl->add(new Zend_Acl_Resource('menuitem'));
        $acl->add(new Zend_Acl_Resource('user'));
        $acl->add(new Zend_Acl_Resource('comments'));
        $acl->add(new Zend_Acl_Resource('search'));
        $acl->add(new Zend_Acl_Resource('registration'));
        $acl->add(new Zend_Acl_Resource('posts'));
        $acl->add(new Zend_Acl_Resource('mobilers'));
        $acl->add(new Zend_Acl_Resource('profile'));
        $acl->add(new Zend_Acl_Resource('phoneindex'));
        $acl->add(new Zend_Acl_Resource('phoneregistration'));
        $acl->add(new Zend_Acl_Resource('addscore'));
        $acl->add(new Zend_Acl_Resource('score'));
        $acl->add(new Zend_Acl_Resource('feed'));
        $acl->add(new Zend_Acl_Resource('activities'));
        $acl->add(new Zend_Acl_Resource('stat'));
        $acl->add(new Zend_Acl_Resource('messages'));
//        $acl->add(new Zend_Acl_Resource('add'), 'mobilers');
//        $acl->add(new Zend_Acl_Resource('edit'), 'mobilers');
        $acl->add(new Zend_Acl_Resource('image'));
        $acl->add(new Zend_Acl_Resource('tag'));
        $acl->add(new Zend_Acl_Resource('archive'));
        $acl->add(new Zend_Acl_Resource('resize'), 'image');
        $acl->add(new Zend_Acl_Resource('activate'));
        $acl->add(new Zend_Acl_Resource('altalanosfeltetelek'));
        $acl->add(new Zend_Acl_Resource('jogitudnivalok'));
        $acl->add(new Zend_Acl_Resource('contact'));

        $acl->add(new Zend_Acl_Resource('uploadify'));

        // set up the access rules
        $acl->allow(null, array('index', 'error'));

        // a guest can only read content and login
        $acl->allow('guest', 'gearoscope');
        $acl->allow('guest', 'skins');
        $acl->allow('guest', 'public');
        $acl->allow('guest', 'facebook');
        $acl->allow('guest', 'admin', array('index', 'login'));

        $acl->allow('guest', 'contact', array('index'));
        $acl->allow('guest', 'sidebar');
        $acl->allow('guest', 'index', array('index'));
        $acl->allow('guest', 'page', array('index', 'open'));
        $acl->allow('guest', 'news', array('index', 'view'));
        $acl->allow('guest', 'bands', array('index', 'view', 'collect', 'activate', 'successfull'));
        $acl->allow('guest', 'gears', array('index', 'view'));
        $acl->allow('guest', 'menu', array('render'));
        $acl->allow('guest', 'user', array('index', 'login', 'logout', 'password', 'successfull', 'passwordsent', 'register'));
        $acl->allow('guest', 'archive', array('index'));
        $acl->allow('guest', 'search', array('index', 'search'));
        $acl->allow('guest', 'image', array('resize'));
        $acl->allow('guest', 'tag', array('index'));
        $acl->allow('guest', 'profile');
        $acl->allow('guest', 'activate');
        $acl->allow('guest', 'feed', array('rss'));

        $acl->allow('user', 'user', array('edit', 'editbio', 'newpassword', 'newemail'));
        $acl->allow('user', 'comments', array('listown', 'delete'));
        $acl->allow('user', 'bands', array('add', 'edit', 'user', 'styles', 'addeditor'));
        $acl->allow('user', 'gears', array('add', 'edit', 'user', 'picture', 'imgdelete', 'thumbnaildelete'));
        $acl->allow('user', 'gearscategory', array('categorychange'));
        $acl->allow('user', 'gearssubcategory', array('categorychange'));
        $acl->allow('user', 'gearssubsubcategory', array('useradd'));
        $acl->allow('user', 'uploadify', array('index', 'uploadify'));

        // administrators can do anything
        $acl->allow('administrator', null);

        // fetch the current user
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $identity = $auth->getIdentity();
            $role = strtolower($identity->user_role);
        } else {
            $role = 'guest';
        }

        $controller = $request->controller;
        $action = $request->action;

        if (!$acl->isAllowed($role, $controller, $action)) {
            if ($role == 'guest') {
                $request->setControllerName('index');
                $request->setActionName('index');
            } else {
                $request->setControllerName('error');
                $request->setActionName('noauth');
            }
        }
    }

}
