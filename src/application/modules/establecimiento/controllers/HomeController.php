<?php

class Establecimiento_HomeController extends App_Controller_Action_Establecimiento
{
    protected $_usuario;
    
    public function init()
    {
        /* Initialize action controller here */
        parent::init();
        $this->_usuario = new Application_Model_Usuario();
    }

    public function indexAction()
    {
        //var_dump($this->auth); exit;
        Zend_Layout::getMvcInstance()->active = 
            App_Controller_Action_Establecimiento::MENU_NAME_INICIO;
        $this->view->headTitle('Establecimiento');
        
        if ($this->isAuth && isset($this->auth['establecimiento'])) {
            $this->_redirect('/establecimiento/redencion-beneficio');
            //$this->_forward('inicio');
        }
        $err = $this->_getParam('err', 0);
        switch ($err) {
            case 401:
                $this->getMessenger()->error('Su sesión a expirado, Autentíquese nuevamente');
                break;
        }
        Zend_Layout::getMvcInstance()->assign('isAuth', false);
    }
    
    public function inicioAction()
    {
        
    }
    
    public function cambiarContraseniaAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        
        if ( empty($this->auth['establecimiento']) ) {
            $this->_redirect('/establecimiento');
        }
        
        $idUsuario = $this->auth['usuario']->id;
        $emailUsuario = $this->auth['usuario']->email;
        $formCambioClave = new Application_Form_CambioClave($idUsuario);
        $formCambioClave->validarEstPswd($emailUsuario);
        
        $envio = array();
        if ($this->_request->isPost()) {

            $allParams = $this->_getAllParams();
            $validClave = $formCambioClave->isValid($allParams);
            if ($validClave) {
                $valuesClave = $formCambioClave->getValues();
                try {
                    $db = $this->getAdapter();
                    $db->beginTransaction();

                    //Captura de los datos de usuario
                    $valuesClave['pswd'] =
                        App_Auth_Adapter_ClubDbTable::generatePassword($valuesClave['pswd']);
                    unset($valuesClave['pswd2']);
                    unset($valuesClave['oldpswd']);

                    $where = $this->_usuario->getAdapter()
                        ->quoteInto('id = ?', $idUsuario);
                    $this->_usuario->update($valuesClave, $where);
                    $db->commit();

                    //$this->getMessenger()->success('Se cambio la clave con éxito!');
                    $envio['message'] = 'Se cambio la clave con éxito!';
                    $envio['state'] = 'success';
                } catch (Exception $e) {
                    $db->rollBack();
                    //$this->getMessenger()->error('Error al cambiar la clave!');
                    $envio['message'] = 'Error al cambiar la clave!';
                    $envio['state'] = 'error';
                    echo $e->getMessage();
                }
            } else {
                /*$this->getMessenger()->error(
                    'Hubo un error al intentar actualizar tus datos, verifica tu información'
                );*/
                $envio['message'] = 'Hubo un error al intentar actualizar tus datos,'
                    .' verifica tu información';
                $envio['state'] = 'error';
            }
        }
        $this->_response->appendBody(Zend_Json::encode($envio));
    }
    
    public function logoutAction()
    {
        $module = $this->getRequest()->getModuleName();
        $next = $this->getRequest()->getParam('next', $module);
        //var_dump($module); exit;
        Zend_Auth::getInstance()->clearIdentity();
        $this->_redirect($next);
    }
}