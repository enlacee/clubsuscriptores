<?php

class App_Controller_Action_Establecimiento extends App_Controller_Action
{
    const MENU_NAME_INICIO = 'inicio';
    const MENU_NAME_BENEFICIOS_OFERTADOS = 'beneficios-ofertados';
    const MENU_NAME_REDENCION_BENEFICIO = 'redencion-beneficio';
    const MENU_NAME_REPORTE_CONSUMO = 'reporte-consumo';
    const MENU_NAME_MIS_DATOS = 'mis-datos';
    const MENU_NAME_CAMBIAR_CONTRASENIA = 'cambiar-contrasenia';
    const MENU_NAME_ELIMINADOS = 'cupon';
    
    const MENU_POST_SIDE_INICIO = 'inicio';
    const MENU_POST_SIDE_PROMOCION = 'promocion';
    const MENU_POST_SIDE_REPORTE = 'reporte';
    
    public function init()
    {
        parent::init();
        //var_dump($this->_request->isXmlHttpRequest()); exit;
        if ( empty($this->auth['establecimiento']) ) {
            if ($this->_request->isXmlHttpRequest()) {
                $this->_helper->layout->disableLayout();
                $this->_helper->viewRenderer->setNoRender();
                Zend_Auth::getInstance()->clearIdentity();
                $this->_response->setRedirect(null, 401);
                //var_dump($this->_response->setRedirect(null, 401)); exit;
            } else {
                Zend_Auth::getInstance()->clearIdentity();
                $controllerName = $this->getRequest()->getControllerName();
                if ( $controllerName!='home' ) {
                    //$this->_redirect('/establecimiento/error/401');
                    $this->_redirect('/establecimiento');
                }
            }
        } else {
            $formCambioClave = new Application_Form_CambioClave($this->auth['usuario']->id);
            $formCambioClave->validarEstPswd($this->auth['usuario']->email);
            $formCambioClave->setAction('/establecimiento/home/cambiar-contrasenia');
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
                if (!$validopt && ($actionName<>'logout' && $actionName<>'cambiar-contrasenia')) {
                    $firstopt = $this->auth['opciones'];
                    $this->_redirect($firstopt[0]['nombremod'].'/'.$firstopt[0]['controlador']);
                }
            }
            //=======================================
        }

        $config = $this->getConfig();

        Zend_Layout::getMvcInstance()->assign(
            'recuperarClaveForm', Application_Form_RecuperarClave::factory(
                Application_Form_Login::ROL_ESTABLECIMIENTO
            )
        );
        Zend_Layout::getMvcInstance()->assign(
            'loginForm', Application_Form_Login::factory(
                Application_Form_Login::ROL_ESTABLECIMIENTO
            )
        );
        
        $this->_helper->layout->setLayout('main-establecimiento');
        
        $this->view->headTitle()->set(
            'Establecimiento - ' . $config->app->title
        );
        $this->view->headLink()->appendStylesheet(
            $config->app->mediaUrl . '/css/main.admin.css?159', 'all'
        );
        
        $this->view->headScript()->appendFile(
            $config->app->mediaUrl . '/js/establecimiento/main.js'
        );
        
        $this->view->headScript()->appendFile(
            $config->app->mediaUrl . '/js/establecimiento/cambiar_constrasenia.js'
        );
        
        $this->view->flashMessages = $this->_flashMessenger;
    }
}