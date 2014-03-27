<?php

class App_Controller_Action extends Zend_Controller_Action
{
    protected $_hash = null;
    protected $_flashMessenger = null;

    const ESTABLECIMIENTO = "establecimiento";
    const SUSCRIPTOR = "suscriptor";
    const ADMIN = "admin";
    const GESTOR = "gestor";
    public function init()
    {
        // Auth Storage
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $authStorage = Zend_Auth::getInstance()->getStorage()->read();
            $isAuth = true;
        } else {
            $authStorage = null;
            $isAuth = false;
        }

        $this->auth = $authStorage;
        $this->view->assign('auth', $authStorage);
        Zend_Layout::getMvcInstance()->assign('auth', $authStorage);
        Zend_Layout::getMvcInstance()->assign(
            'modulo', $this->getRequest()->getModuleName()
        );
        $config = $this->getConfig();
        Zend_Layout::getMvcInstance()->assign(
            'title', $config->app->title
        );
        Zend_Layout::getMvcInstance()->assign(
            'staticCache', $config->confpaginas->staticCache
        );
        $this->isAuth = $isAuth;
        $this->view->assign('isAuth', $isAuth);
        Zend_Layout::getMvcInstance()->assign('isAuth', $isAuth);


        defined('MODULE') || define('MODULE', $this->_getParam('module'));
        defined('CONTROLLER') || define('CONTROLLER', $this->_getParam('controller'));
        defined('ACTION') || define('ACTION', $this->_getParam('action'));

        $js = "var modulo_actual='" . MODULE . "';";
        $this->view->headScript()->appendScript($js);

        parent::init();
    }

    /**
     * Pre-dispatch routines
     * Asignar variables de entorno
     *
     * @return void
     */
    public function preDispatch()
    {
        parent::preDispatch();
        $config = $this->getConfig();

        $this->view->isPost = $this->_request->isPost();
        $this->config = $this->getConfig();
        $this->log = $this->getLog();
        $this->mediaUrl = $this->config->app->mediaUrl;
        $this->siteUrl = $this->config->app->siteUrl;

        $this->view->assign('mediaUrl', $config->app->mediaUrl);
        $this->view->assign('elementsUrl', $config->app->elementsUrl);
        $this->view->assign('siteUrl', $config->app->siteUrl);
        
        $helper = $this->_helper->getHelper('FlashMessengerCustom');
        $this->_flashMessenger = $helper;
    }

    /**
     * Post-dispatch routines
     * 
     * @return void
     */
    public function postDispatch()
    {
        $messages = $this->_flashMessenger->getMessages();
        if ($this->_flashMessenger->hasCurrentMessages()) {
            $messages = $this->_flashMessenger->getCurrentMessages();
            $this->_flashMessenger->clearCurrentMessages();
        }
        $this->view->assign('flashMessages', $messages);
        Zend_Layout::getMvcInstance()->assign('flashMessages', $messages);
    }

    protected $_loginRequiredFor = array();
    protected $_loginUrl = '/auth/login';
    protected $_authCheckEnabled = true;

    public function checkAuth()
    {
        $action = $this->getRequest()->getActionName();
        if ($this->_authCheckEnabled == true
            && false == Zend_Auth::getInstance()->hasIdentity()
            && in_array($action, $this->_loginRequiredFor)
        ) {
            $url = $this->getRequest()->getRequestUri();
            $this->_redirect($this->_loginUrl . '?next=' . $url);
        }
    }

    /**
     * Retorna la instancia personalizada de FlashMessenger
     * Forma de uso:
     * $this->getMessenger()->info('Mensaje de información');
     * $this->getMessenger()->success('Mensaje de información');
     * $this->getMessenger()->error('Mensaje de información');
     * 
     * @return App_Controller_Action_Helper_FlashMessengerCustom
     */
    public function getMessenger()
    {
        return $this->_flashMessenger;
    }

    /**
     * 
     * @see Zend/Controller/Zend_Controller_Action::getRequest()
     * @return Zend_Controller_Request_Http
     */
    public function getRequest()
    {
        return parent::getRequest();
    }

    /**
     * Retorna un objeto Zend_Config con los parámetros de la aplicación
     * 
     * @return Zend_Config
     */
    public function getConfig()
    {
        return Zend_Registry::get('config');
    }

    /**
     * Retorna el objeto cache de la aplicación
     * 
     * @return Zend_Cache_Core
     */
    public function getCache()
    {
        return Zend_Registry::get('cache');
    }

    /**
     * Retorna el adaptador 
     * 
     * @return Zend_Db_Adapter_Abstract
     */
    public function getAdapter()
    {
        return Zend_Registry::get('db');
    }

    /**
     * Retorna el objeto Zend_Log de la aplicación
     * 
     * @return Zend_Log
     */
    public function getLog()
    {
        return Zend_Registry::get('log');
    }

    public function getSession()
    {
        $session = new Zend_Session_Namespace('cs');
        return $session;
    }

}