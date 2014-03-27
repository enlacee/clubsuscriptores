<?php

class App_Controller_Action_Gestor extends App_Controller_Action
{
    const MENU_NAME_INICIO = 'inicio';
    const MENU_NAME_ESTABLECIMIENTOS = 'establecimientos';
    const MENU_NAME_BENEFICIOS = 'beneficios';
    const MENU_NAME_VIDA_SOCIAL = 'vida-social';
    const MENU_NAME_ENCUESTAS = 'encuestas';
    const MENU_NAME_CATEGORIAS = 'categorias';
    const MENU_NAME_CONFIGURACION = 'configuracion';
    const MENU_NAME_CONCILIACION = 'conciliacion';
    const MENU_NAME_ANUNCIANTE = 'anunciante';
    
    public function init()
    {
        parent::init();

        if (empty($this->auth['gestor'])) {
            if ($this->_request->isXmlHttpRequest()) {
                $this->_helper->layout->disableLayout();
                $this->_helper->viewRenderer->setNoRender();
                Zend_Auth::getInstance()->clearIdentity();
                $this->_response->setRedirect(null, 401);
            } else {
                Zend_Auth::getInstance()->clearIdentity();
                $controllerName = $this->getRequest()->getControllerName();
                if ($controllerName != 'home') {
                    $this->_redirect('/gestor');
                }
            }
        } else {
            $formCambioClave = new Application_Form_CambioClave($this->auth['usuario']->id);
            $formCambioClave->validarEstPswd($this->auth['usuario']->email);
            $formCambioClave->setAction('/gestor/home/cambiar-contrasenia');
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

        $config = $this->getConfig();

        Zend_Layout::getMvcInstance()->assign(
            'recuperarClaveForm', Application_Form_RecuperarClave::factory(
                Application_Form_Login::ROL_GESTOR
            )
        );
        Zend_Layout::getMvcInstance()->assign(
            'loginForm', Application_Form_Login::factory(
                Application_Form_Login::ROL_GESTOR
            )
        );

        $this->_helper->layout->setLayout('main-gestor');
        $this->view->headTitle()->set(
            'Gestor - ' . $config->app->title
        );
        $this->view->headLink()->appendStylesheet(
            $config->app->mediaUrl . '/css/main.admin.css', 'all'
        );
        $this->view->headScript()->appendFile(
            $config->app->mediaUrl . '/js/gestor/main.js'
        );
        $this->view->headScript()->appendFile(
            $config->app->mediaUrl . '/js/gestor/cambiar_constrasenia.js'
        );
    }

}