<?php

class Admin_HomeController extends App_Controller_Action_Admin
{
    public function init()
    {
        parent::init();
        $this->_usuario = new Application_Model_Usuario();
    }

    public function indexAction()
    {
        if ($this->isAuth && isset($this->auth["admin"])) {
            $this->_redirect("/admin/inicio");
        }
        
        $this->view->headTitle('Administradores');
        $frmadmin = new Application_Form_Login();
        $frmadmin->setType(Application_Form_Login::ROL_ADMIN);
        $this->view->frmadmin = $frmadmin;
    }

    public function cambiarContraseniaAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        
        if ( empty($this->auth['admin']) ) {
            $this->_redirect('/admin');
        }
        
        $idUsuario = $this->auth['usuario']->id;
        $emailUsuario = $this->auth['usuario']->email;
        $formCambioClave = new Application_Form_CambioClave($idUsuario);
        $formCambioClave->validarAllPswd($emailUsuario, Application_Model_Rol::ADMIN);
        
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

