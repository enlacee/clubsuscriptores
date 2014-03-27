<?php

class App_Controller_Action_Admin extends App_Controller_Action
{
    const MENU_NAME_INICIO = 'inicio';
    const MENU_NAME_USUARIOS = 'usuarios';
    const MENU_NAME_PERFILES = 'perfiles';
    const MENU_NAME_OPCIONES = 'opciones';
    
    public function init()
    {
        parent::init();

        if (empty($this->auth['admin'])) {
            if ($this->_request->isXmlHttpRequest()) {
                $this->_helper->layout->disableLayout();
                $this->_helper->viewRenderer->setNoRender();
                Zend_Auth::getInstance()->clearIdentity();
                $this->_response->setRedirect(null, 401);
            } else {
                Zend_Auth::getInstance()->clearIdentity();
                $controllerName = $this->getRequest()->getControllerName();
                if ($controllerName != 'home') {
                    $this->_redirect('/admin');
                }
            }
        } else {
            $formCambioClave = new Application_Form_CambioClave($this->auth['usuario']->id);
            $formCambioClave->validarEstPswd($this->auth['usuario']->email);
            $formCambioClave->setAction('/admin/home/cambiar-contrasenia');
            Zend_Layout::getMvcInstance()->assign('frmChangePassE', $formCambioClave);
            
            //==validacion de acceso a la pagina====
            $controllerName = $this->getRequest()->getControllerName();
            $moduleName = $this->getRequest()->getModuleName();
            $actionName = $this->getRequest()->getActionName();
            if (!empty($this->auth['opciones'])) {
                $options = $this->auth['opciones']; $validopt = false;
                foreach ($options as $opt) {
                    if ($opt['nombremod']==$moduleName && $opt['controlador']==$controllerName) {
                        $validopt = true;
                    }
                }
                //var_dump($this->getRequest()->getParams(),$validopt); exit;
                if (!$validopt && $actionName<>'logout' && $actionName<>'cambiar-contrasenia') {
                    $firstopt = $this->auth['opciones'];
                    $this->_redirect($firstopt[0]['nombremod'].'/'.$firstopt[0]['controlador']);
                }
            }
            //=======================================
        }
        
        Zend_Layout::getMvcInstance()->assign(
            'recuperarClaveForm', Application_Form_RecuperarClave::factory(
                Application_Form_Login::ROL_GESTOR
            )
        );
        /*Zend_Layout::getMvcInstance()->assign(
            'loginForm', Application_Form_Login::factory(
                Application_Form_Login::ROL_GESTOR
            )
        );*/
        
        $config = $this->getConfig();
        $this->_helper->layout->setLayout('main-admin');
        $this->view->headTitle()->set(
            'Admin - ' . $config->app->title
        );
        $this->view->headLink()->appendStylesheet(
            $this->view->S($config->app->mediaUrl . '/css/admin/layout.css?nohayversion'), 'all'
        );
        $this->view->headLink()->appendStylesheet(
            $this->view->S($config->app->mediaUrl . '/css/admin/class.css?nohayversion'), 'all'
        );
        $this->view->headScript()->appendFile(
            $config->app->mediaUrl . '/js/admin/main.js'
        );
        
        $this->view->headScript()->appendFile(
            $config->app->mediaUrl . '/js/admin/cambiar_contrasenia.js'
        );
        
        $this->view->flashMessages = $this->_flashMessenger;
    }

}