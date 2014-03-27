<?php

class Gestor_HomeController extends App_Controller_Action_Gestor
{
    public function init()
    {
        /* Initialize action controller here */
        parent::init();
        $this->_usuario = new Application_Model_Usuario();
    }

    public function indexAction()
    {
//        var_dump($this->auth);
        if ($this->isAuth && isset($this->auth['gestor'])) {
            $this->_redirect('gestor/inicio');
            /*if (!empty($this->auth['opciones'])) {
                $firstopt = $this->auth['opciones'];
                $this->_redirect($firstopt[0]['nombremod'].'/'.$firstopt[0]['controlador']);
            } else {
                $this->_redirect('gestor/establecimientos');
            }*/
        }
        $err = $this->_getParam('err', 0);
        switch ($err) {
            case 401:
                $this->getMessenger()->error('Su sesión a expirado, Autentíquese nuevamente');
                break;
        }
        $this->view->headTitle('Gestores');
        Zend_Layout::getMvcInstance()->assign(
            'isAuth', false
        );
    }
    
    public function logoutAction()
    {
        $module = $this->getRequest()->getModuleName();
        $next = $this->getRequest()->getParam('next', $module);
        //var_dump($module); exit;
        Zend_Auth::getInstance()->clearIdentity();
        $this->_redirect($next);
    }
    
    public function cambiarContraseniaAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        
        if ( empty($this->auth['gestor']) ) {
            $this->_redirect('/gestor');
        }
        
        $idUsuario = $this->auth['usuario']->id;
        $emailUsuario = $this->auth['usuario']->email;
        $formCambioClave = new Application_Form_CambioClave($idUsuario);
        $formCambioClave->validarAllPswd($emailUsuario, Application_Model_Rol::GESTOR);
        
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
}