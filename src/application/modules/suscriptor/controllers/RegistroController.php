<?php

class Suscriptor_RegistroController extends App_Controller_Action_Suscriptor
{
    protected $_suscriptor;
    protected $_usuario;
    protected $_url;
    protected $_noSuscriptor;

    protected $_pswd;

    public function init()
    {
        parent::init();

        $this->_suscriptor = new Application_Model_Suscriptor();
        $this->_usuario = new Application_Model_Usuario();
        $this->_url = '/mi-cuenta';
        $this->_noSuscriptor = '/mi-cuenta/mis-datos-personales';
        $this->idSuscriptor = null;
        Zend_Layout::getMvcInstance()->assign(
            'bodyAttr', array('id' => 'perfilReg', 'class' => 'noMenu')
        );
    }

    public function indexAction()
    {
        if ($this->isAuth) {
            $this->_redirect($this->_url);
        }
        
        $this->view->headTitle()->prepend('Regístrate');
        $this->view->headMeta()->setName("description", "Completa tu suscripción y accede a los beneficios y 
            promociones del Club De Suscriptores El Comercio Perú");
        $this->view->headMeta()->setName("keywords", "registro, registrarse, beneficios de el comercio, 
            Club De Suscriptores El Comercio Perú");
        
        $invitacion = $this->_getParam('inv', null);
        $sid = $this->_getParam('sid', null);
        $this->view->invitado = $invitacion;
        $this->view->hash = $sid;
        $this->view->modulo = $this->getRequest()->getModuleName();
        $this->view->controlador = $this->getRequest()->getControllerName();
        $this->view->headScript()->appendFile(
            $this->mediaUrl . '/js/registro.js'
        );

        $this->view->idSuscriptor = $id = $this->idSuscriptor;
        $idUsuario = null;

        $formSuscriptor = new Application_Form_Suscriptor($id);
        $formUsuario = new Application_Form_Usuario($idUsuario);
        $formCategorias = new Application_Form_SuscribirCategorias();

        $formUsuario->validadorEmail($idUsuario, 'suscriptor');
        $formSuscriptor->validadorNumDoc($id);
        $formSuscriptor->docSuscriptor();
        if (!empty($sid)) {
            $suscriptorByHash = Application_Model_Suscriptor::getSuscriptorByHash($sid);
            if (!empty($suscriptorByHash)) {
                if ($suscriptorByHash['suscriptor']['origen'] != 'web') {
                    foreach (array_keys(Application_Form_Suscriptor::$valorDocumento) as $valor) {
                        $valor = explode('#', $valor);
                        if (strtoupper($suscriptorByHash['suscriptor']['tipo_documento']) == $valor[0]) {
                            $suscriptorByHash['suscriptor']['tipo_documento']
                                = strtoupper($suscriptorByHash['suscriptor']['tipo_documento']) . '#' . $valor[1];
                        }
                    }
                    $suscriptorByHash['suscriptor']['fecha_nacimiento']
                        = date('d/m/Y', strtotime($suscriptorByHash['suscriptor']['fecha_nacimiento']));
                    $suscriptorByHash['suscriptor']['sexoMF'] = $suscriptorByHash['suscriptor']['sexo'];
                    $suscriptorByHash['suscriptor']['tipo_documento_suscriptor']
                        = $suscriptorByHash['suscriptor']['tipo_documento'];
                    $suscriptorByHash['suscriptor']['documento_suscriptor']
                        = $suscriptorByHash['suscriptor']['numero_documento'];
                    $distritoActivo = Application_Model_SuscriptorDireccion
                        ::getSuscriptorDistritoByCodigo(
                            $suscriptorByHash['suscriptor']['codigo_suscriptor']
                        );
                    $suscriptorByHash['suscriptor']['distrito_entrega'] =
                        $distritoActivo['cod_distrientrega'];
                    $formSuscriptor->setDefaults($suscriptorByHash['suscriptor']);
                    $formSuscriptor->disableElementensWhenSuscriptor();
                } else {
                    $this->getMessenger()->error('El usuario ya está registrado en el Club Suscriptor.');
                    $this->_redirect('/registro');
                }
            } else {
                $this->getMessenger()->error('El URL proporcionado no es válido');
                $this->_redirect('/registro');
            }
        }

        if ($this->_request->isPost()) {
            $posted = true;
            $ms = "";
            $allParams = $this->_getAllParams();
            $validSuscriptor = $formSuscriptor->isValid($allParams);
            $validUsuario = $formUsuario->isValid($allParams);
            $formCategorias->setDefaults($allParams);
            $valuesSuscriptor = $formSuscriptor->getValues();
            $valuesUsuario = $formUsuario->getValues();
            $pswd = $valuesUsuario['pswd'];
            //$valuesUsuario['email'] = strtolower($valuesUsuario['email']);
            $suscriptor = new Application_Model_Suscriptor();
            $esSuscriptor = false;
            $tdoc = '';
            $ndoc = '';

            $date = date('Y-m-d H:i:s');

            $tpdoc = empty($valuesSuscriptor['documento_suscriptor'])?explode('#', $valuesSuscriptor['tipo_documento'])
                :explode('#', $valuesSuscriptor['tipo_documento_suscriptor']);
            $tipo = $tpdoc[0];
            $numero = empty($valuesSuscriptor['documento_suscriptor'])? 
                $allParams['numero_documento']:$valuesSuscriptor['documento_suscriptor'];
            $esSuscriptor = Application_Model_Suscriptor::getSuscriptorByDocumento($tipo, $numero, true);
            $distrito = $valuesSuscriptor['distrito_entrega'];
            $codigo = empty($esSuscriptor)? 0:$esSuscriptor['codigo_suscriptor'];
            $valuesCategorias = $formCategorias->getValues();
            $hasDistrito = Application_Model_SuscriptorDireccion::getSuscriptorDistrito($codigo, $distrito);
            if (!empty($esSuscriptor) && !empty($hasDistrito) && $validUsuario) {
                //ES SUSCRIPTOR
                try {
                    $db = $this->getAdapter();
                    $db->beginTransaction();
                    $usuario = new Application_Model_Usuario();
                    $where = $db->quoteInto('id = ?', $esSuscriptor['usuario_id']);
                    $valuesUsuario['pswd'] = App_Auth_Adapter_ClubDbTable::generatePassword($valuesUsuario['pswd']);
                    $usuario->update(
                        array(
                        'email' => $valuesUsuario['email'],
                        'pswd' => $valuesUsuario['pswd'],
                        'activo' => 1,
                        'fecha_actualizacion' => $date
                        ), $where
                    );
                    $whereSuscriptor = $db->quoteInto('id = ?', $esSuscriptor['id']);
                    $suscriptor->update(
                        array(
                        'enviar_alertas_email' => $valuesCategorias['enviar_alertas_email'],
                        'origen' => 'web',
                        'fecha_actualizacion' => $date
                        ), $whereSuscriptor
                    );
                    for ($i = 1; $i < 4; $i++) {
                        if ($valuesCategorias['categoria_' . $i] > 0) {
                            $_alerta = new Application_Model_Alerta();
                            $alerta = array(
                                'categoria_id' => $valuesCategorias['categoria_' . $i],
                                'suscriptor_id' => $esSuscriptor['id'],
                                'fecha_afiliacion' => $date
                            );
                            $_alerta->insert($alerta);
                        }
                    }
                    $db->commit();

                    if ($esSuscriptor['sexo'] == 'M') {
                        $subjectMessage = 'Bienvenido';
                    } else {
                        $subjectMessage = 'Bienvenida';
                    }
                    $this->_helper->mail->nuevoUsuarioSuscriptor(
                        array(
                            'to' => $valuesUsuario['email'],
                            'user' => $valuesUsuario['email'],
                            'pswd' => $pswd,
                            'fr' => $date,
                            'slug' => $esSuscriptor['slug'],
                            'nombre' => ucwords($esSuscriptor['nombres']),
                            'subjectMessage' => $subjectMessage
                        )
                    );
                    $valuesUsuario['rol'] = Application_Model_Rol::SUSCRIPTOR;
                    if ($esSuscriptor['id'] != null || $id != null) {
                        Application_Model_Usuario::auth($valuesUsuario['email'], $pswd, $valuesUsuario['rol']);
                        if (($this->auth['suscriptor']['es_suscriptor']
                            == 1 || $this->auth['suscriptor']['es_invitado'] == 1)
                            && ($this->auth['suscriptor']['activo'] == 1)) {
                            $this->_redirect($this->_url);
                        } else {
                            $this->_redirect($this->_noSuscriptor);
                        }
                    }
                } catch (Exception $exc) {
                    $db->rollBack();
                    $this->getMessenger()->error($exc->getMessage());
                    $this->_redirect('/');
                }
                $this->getMessenger()->success('Te has registrado correctamente.');
                //FIN ES SUSCRIPTOR
            } elseif ($esSuscriptor['es_invitado'] == 1 && $esSuscriptor['es_suscriptor'] == 0 && $validUsuario) {
                try {
                    $db = $this->getAdapter();
                    $db->beginTransaction();
                    $usuario = new Application_Model_Usuario();
                    $where = $db->quoteInto('id = ?', $esSuscriptor['usuario_id']);
                    $valuesUsuario['pswd'] = App_Auth_Adapter_ClubDbTable::generatePassword($valuesUsuario['pswd']);
                    $usuario->update(
                        array(
                        'email' => $valuesUsuario['email'],
                        'pswd' => $valuesUsuario['pswd'],
                        'activo' => 1,
                        'fecha_actualizacion' => $date
                        ), $where
                    );
                    $whereSuscriptor = $db->quoteInto('id = ?', $esSuscriptor['id']);
                    $suscriptor->update(
                        array(
                        'origen' => 'web',
                        'fecha_actualizacion' => $date
                        ), $whereSuscriptor
                    );
                    $db->commit();
                    if ($esSuscriptor['sexo'] == 'M') {
                        $subjectMessage = 'Bienvenido';
                    } else {
                        $subjectMessage = 'Bienvenida';
                    }
                    $this->_helper->mail->nuevoUsuarioSuscriptor(
                        array(
                            'to' => $valuesUsuario['email'],
                            'user' => $valuesUsuario['email'],
                            'pswd' => $pswd,
                            'fr' => $date,
                            'slug' => $esSuscriptor['slug'],
                            'nombre' => ucwords($esSuscriptor['nombres']),
                            'subjectMessage' => $subjectMessage
                        )
                    );
                    $valuesUsuario['rol'] = Application_Model_Rol::SUSCRIPTOR;
                    if ($esSuscriptor['id'] != null || $id != null) {
                        Application_Model_Usuario::auth($valuesUsuario['email'], $pswd, $valuesUsuario['rol']);
                        if (($this->auth['suscriptor']['es_suscriptor']
                            == 1 || $this->auth['suscriptor']['es_invitado'] == 1)
                            && ($this->auth['suscriptor']['activo'] == 1)) {
                            $this->_redirect($this->_url);
                        } else {
                            $this->_redirect($this->_noSuscriptor);
                        }
                    }
                }catch(Exception $e) {
                    $db->rollBack();
                    $this->getMessenger()->error($e->getMessage());
                    $this->_redirect('/');
                }
            } elseif ($validSuscriptor && $validUsuario) {
                //ES NUEVO USUARIO
                try {
                    $db = $this->getAdapter();
                    $db->beginTransaction();

                    // Datos adicionales q no vienen del form
                    //
                    $valuesUsuario['salt'] = '';
                    $valuesUsuario['rol'] = Application_Model_Rol::SUSCRIPTOR;
                    $valuesUsuario['activo'] = 1;
                    $valuesUsuario['ultimo_login'] = $date;
                    $valuesUsuario['fecha_registro'] = $date;
                    $valuesUsuario['pswd'] = App_Auth_Adapter_ClubDbTable::generatePassword(
                        $valuesUsuario['pswd']
                    );
                    unset($valuesUsuario['pswd2']);
                    $lastId = $this->_usuario->insert($valuesUsuario);

                    $slug = $this->_crearSlug($valuesSuscriptor, $lastId);

                    $valorTipoDoc = explode('#', $valuesSuscriptor['tipo_documento']);
                    $valuesSuscriptor['tipo_documento'] = $valorTipoDoc[0];
                    $valuesSuscriptor['usuario_id'] = $lastId;
                    $valuesSuscriptor['fecha_nacimiento'] = date(
                        'Y-m-d', 
                        strtotime(str_replace('/', '-', $valuesSuscriptor['fecha_nacimiento']))
                    );
                    $valuesSuscriptor['sexo'] = $valuesSuscriptor['sexoMF'];
                    $valuesSuscriptor['fecha_actualizacion'] = $date;
                    $valuesSuscriptor['slug'] = $slug;
                    $valuesSuscriptor['fecha_registro'] = $date;
                    $valuesSuscriptor['enviar_alertas_email'] = $valuesCategorias['enviar_alertas_email'];
                    unset($valuesSuscriptor['sexoMF']);
                    unset($valuesSuscriptor['distrito_entrega']);
                    unset($valuesSuscriptor['tipo_documento_suscriptor']);
                    unset($valuesSuscriptor['documento_suscriptor']);
                    if (!is_null($invitacion)) {
                        $ms = "Su invitación fue completada correctamente";
                        $maximoInvitados = 
                            empty($this->config->beneficiarios->maximoInvitados)? 
                            3:$this->config->beneficiarios->maximoInvitados;
                        $invitado = Application_Model_Invitacion::isValidToken($invitacion);
                        if (Application_Model_Suscriptor::getNumeroInvitadoSuscriptorPadre($invitado['suscriptor_id'])
                            < $maximoInvitados) {
                            $valuesSuscriptor['suscriptor_padre_id'] = $invitado['suscriptor_id'];
                            $valuesSuscriptor['es_invitado'] = 1;
                            $valuesSuscriptor['activo'] = 1;
                            $valuesSuscriptor['fecha_invitacion'] = $date;
                            Application_Model_Invitacion::deleteInvitacion($invitacion);
                            $storage = Zend_Auth::getInstance()->getStorage()->read();
                            $storage['suscriptor']['suscriptor_padre_id']
                                = $invitado['suscriptor_id'];
                            $storage['suscriptor']['es_invitado'] = 1;
                            $storage['suscriptor']['activo'] = 1;
                            Zend_Auth::getInstance()->getStorage()->write($storage);
                        } else {
                            $ms = "Su invitación no fue completada por 
                                superarse el máximo de invitados por suscriptor";
                        }
                    }
                    $lastIdSuscriptor = $this->_suscriptor->insert($valuesSuscriptor);
                    $where = $this->getAdapter()->quoteInto('id = ?', $lastIdSuscriptor);
                    $hash["hash"] = $this->_helper->suscriptor->generaHashSuscriptor($lastIdSuscriptor);
                    $lastIdSuscriptor = $this->_suscriptor->update($hash, $where);
                    
                    
                    
                    for ($i = 1; $i < 4; $i++) {
                        if ($valuesCategorias['categoria_' . $i] > 0) {
                            $_alerta = new Application_Model_Alerta();
                            $alerta = array(
                                'categoria_id' => $valuesCategorias['categoria_' . $i],
                                'suscriptor_id' => $lastIdSuscriptor,
                                'fecha_afiliacion' => $date
                            );
                            $_alerta->insert($alerta);
                        }
                    }
                    $db->commit();
                    $this->getMessenger()->success('Te has registrado correctamente. '.$ms);

                    if ($valuesSuscriptor['sexo'] == 'M') {
                        $subjectMessage = 'Bienvenido';
                    } else {
                        $subjectMessage = 'Bienvenida';
                    }
                    $this->_helper->mail->nuevoUsuario(
                        array(
                            'to' => $valuesUsuario['email'],
                            'user' => $valuesUsuario['email'],
                            'pswd' => $pswd,
                            'fr' => $date,
                            'slug' => $slug,
                            'nombre' => ucwords($valuesSuscriptor['nombres']),
                            'subjectMessage' => $subjectMessage
                        )
                    );
                    if ($lastIdSuscriptor != null || $id != null) {
                        Application_Model_Usuario::auth($valuesUsuario['email'], $pswd, $valuesUsuario['rol']);
                        if (($this->auth['suscriptor']['es_suscriptor']
                            == 1 || $this->auth['suscriptor']['es_invitado'] == 1)
                            && ($this->auth['suscriptor']['activo'] == 1)) {
                            $this->_redirect($this->_url);
                        } else {
                            $this->_redirect($this->_noSuscriptor);
                        }
                    }
                } catch (Exception $exc) {
                    $db->rollBack();
//                    $this->getMessenger()->error($exc->getMessage());
                    $this->getMessenger()->error('Hubo un error en el registro de usuario. '.$exc->getMessage());
                }
                //FIN NUEVO USUARIO
            } else {
                $this->getMessenger()->error('Hubo un error en el registro de usuario.');
//                $this->_redirect('/');
            }
        }
//        $this->view->posted = $this->_request->isPost();
        $this->view->formUsuario = $formUsuario;
        $this->view->formSuscriptor = $formSuscriptor;
        $this->view->formCategorias = $formCategorias;
    }

    public function validarEmailAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $email = $this->_getParam('email');
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $authData = Zend_Auth::getInstance()->getStorage()->read();
            $id = $authData['usuario']->id;
        } else {
            $id = false;
        }

        $_usuario = new Application_Model_Usuario();
        $isValid = Application_Model_Usuario::validarUsuario($email, 'suscriptor');
        $data = array('status' => $isValid);
        $this->_response->appendBody(Zend_Json::encode($data));
    }

    public function validarCodigoSuscriptorAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $codigo = $this->_getParam('codigo_suscriptor');
        $_usuario = new Application_Model_Usuario();
        $registrado = $_usuario->validacionCodigoSuscriptor($codigo, null, $this->auth["suscriptor"]["id"]);

//        $_actSuscriptor = new Application_Model_ActSuscriptor();
        $suscrito = Application_Model_ActSuscriptor::validacionSuscriptorByCodigo($codigo);

        $response = 'no_existe';
        if (!$registrado) {
            $response = 'registrado';
        } else {
            if ($suscrito) {
                $response = 'suscrito';
            }
        }

        $data = array('status' => $response);
        $this->_response->appendBody(Zend_Json::encode($data));
    }

    public function validarDniAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $tdoc = $this->_getParam('tdoc');
        $ndoc = $this->_getParam('ndoc');
        $_usuario = new Application_Model_Usuario();
        $isValid = $_usuario->validacionNDocSuscriptor($tdoc, $ndoc, $this->auth["suscriptor"]["id"]);
        $suscrito = Application_Model_ActSuscriptor::validacionSuscriptorByDocumento($tdoc, $ndoc);
        $data = array('status' => '');

        if ($isValid && !$suscrito) {
            $data['status'] = 'valido';
        } else {
            if ($suscrito) {
                $data['status'] = 'suscrito';
            } elseif (!$isValid) {
                $data['status'] = 'registrado';
            }
        }
        
//        $data = array('status' => $isValid);
        $this->_response->appendBody(Zend_Json::encode($data));
    }
    
    public function validarDocumentoAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $tdoc = $this->_getParam('tdoc');
        $ndoc = $this->_getParam('ndoc');

        $suscriptor = Application_Model_Suscriptor::getSuscriptorByDocumento($tdoc, $ndoc);
        $data['status'] = 'valido';
        if (!empty($suscriptor)) {
            if ($suscriptor['origen'] == 'web') {
                $data['status'] = 'registrado';
            } elseif ($suscriptor['es_invitado'] == 0 && $suscriptor['origen'] == 'cron') {
                $data['status'] = 'suscrito';
            } elseif ($suscriptor['es_invitado'] == 1 && $suscriptor['origen'] == 'cron') {
                $data['status'] = 'invitado';
                $data['suscriptor'] = $suscriptor;
            } elseif ($suscriptor['es_suscriptor'] == 0 
                && $suscriptor['es_invitado'] == 0 
                && $suscriptor['origen'] == 'cron') {
                $data['status'] = 'inactivo';
            }
        }
        $this->_response->appendBody(Zend_Json::encode($data));
    }

    private function _crearSlug($values, $lastId)
    {
        $slugFilter = new App_Filter_Slug(
            array(
                'field' => 'slug',
                'model' => $this->_suscriptor
            )
        );

        $slug = $slugFilter->filter(
            $values['nombres'] . ' ' .
            $values['apellido_paterno'] . ' ' . $values['apellido_materno'] . ' ' .
            substr(md5($lastId), 0, 8)
        );
        return $slug;
    }
 
    public function activarInvitacionAction()
    {
        $token = $this->getRequest()->getParam('token');
        $this->view->token = $token;
        $invitacion = Application_Model_Invitacion::isValidToken($token);
        $maximoInvitados =
            empty($this->config->beneficiarios->maximoInvitados) ?
                3 : $this->config->beneficiarios->maximoInvitados;

        if ($invitacion) {
            if ($this->getRequest()->isPost()) {
                $invitado = $this->_suscriptor->getSuscriptorPerfil($this->auth['suscriptor']['id']);
                if (!$this->isAuth) {
                    $this->_redirect('activar-invitacion/' . $token . '#loginModalW');
                }
                if ($invitado['es_invitado'] == 0 && $invitado['suscriptor_padre_id'] == '') {
                    if (!($invitado['es_suscriptor'] == 1 && $invitado['activo'] == 1)) {
                        $date = date('Y-m-d H:i:s');
                        if (Application_Model_Suscriptor::getNumeroInvitadoSuscriptorPadre($invitacion['suscriptor_id'])
                            < $maximoInvitados) {
                            try {
                                $data['suscriptor_padre_id'] = $invitacion['suscriptor_id'];
                                $data['fecha_invitacion'] = $date;
                                $data['es_invitado'] = 1;
                                $data['activo'] = 1;
                                $db = $this->getAdapter();
                                $db->beginTransaction();
                                $where = $this->_suscriptor->getAdapter()->quoteInto('id = ?', $invitado['id']);
                                $db->update('suscriptor', $data, $where);
                                Application_Model_Invitacion::deleteInvitacion($token);
                                $db->commit();
                                $storage = Zend_Auth::getInstance()->getStorage()->read();
                                $storage['suscriptor']['suscriptor_padre_id'] = $invitacion['suscriptor_id'];
                                $storage['suscriptor']['es_invitado'] = 1;
                                $storage['suscriptor']['activo'] = 1;
                                Zend_Auth::getInstance()->getStorage()->write($storage);
                                $this->getMessenger()->success('Invitacion registrada con exito');
                                $this->_redirect('/');
                            } catch (Exception $e) {
                                $db->rollBack();
                                echo $e->getMessage();
                                $this->_redirect('/');
                            }
                        } else {
                            $this->getMessenger()
                                ->error('El suscriptor que te invitó, ya superó el limite de beneficiarios.');
                            $this->_redirect('/');
                        }
                    } else {
                        $this->getMessenger()->error('Ya eres suscriptor, no puedes acceder a una invitación.');
                        $this->_redirect('/');
                    }
                } else {
                    $this->getMessenger()->error('Ya eres invitado de otro suscriptor. La operacion no se completó');
                    $this->_redirect('/');
                }
            }
        } else {
            $this->getMessenger()->error('La invitación ya no es válida.');
            $this->_redirect('/');
        }
    }
    
    public function registrarInvitadoAction()
    {
        $token = $this->getRequest()->getParam('token', 0);
        $invitacion = Application_Model_Invitacion::isValidToken($token);
        if ($invitacion === false) {
            $this->getMessenger()->error('La invitación ya expiró o fue registrada');
            $this->_redirect('/');
        }

        $usuario = $this->_usuario->getIdByEmailRol($invitacion['email'], Application_Form_Login::ROL_SUSCRIPTOR);
        if ($invitacion['suscriptor_id'] == $this->auth['suscriptor']['id']) {
            $this->getMessenger()->error('No puedes invitarte a ti mismo.');
            $this->_redirect('/');
        } else {
            if ($usuario) {
                if (!$this->isAuth)
                    $this->_redirect('activar-invitacion/' . $token . '#loginModalW');
                else
                    $this->_redirect('activar-invitacion/' . $token);
            } else {
                $this->getMessenger()->error('Debes estar registrado para aceptar la invitacion');
                $this->_redirect('registro?inv=' . $token);
            }
        }
    }

    public function verificarSuscriptorAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $tipo = $this->_getParam('tipo_documento');
        $numero = $this->_getParam('documento');
        $distrito = $this->_getParam('distrito');

        $suscriptor = Application_Model_Suscriptor::getSuscriptorByDocumento($tipo, $numero);

        if (empty($suscriptor)) {
            $response = array(
                'status' => false, 
                'message' => 'Tipo y/o número de documento no válidos'
            );
        } elseif ($suscriptor['origen'] == 'web') {
            $response = array('status' => false, 'message' => 'Suscriptor ya registrado');
        } else {
            $distrito = Application_Model_SuscriptorDireccion
                ::getSuscriptorDistrito($suscriptor['codigo_suscriptor'], $distrito);
            if (empty($distrito)) {
                $response = array('status' => false, 'message' => 'Distrito de entrega no válido');
            } else {
                $formSuscriptor = new Application_Form_Suscriptor(null);
                foreach (array_keys(Application_Form_Suscriptor::$valorDocumento) as $valor) {
                    $valor = explode('#', $valor);
                    if (strtoupper($suscriptor['tipo_documento']) == $valor[0]) {
                        $suscriptor['tipo_documento']
                            = strtoupper($suscriptor['tipo_documento']) . '#' . $valor[1];
                    }
                }
                $response = array(
                    'status' => true, 'message' => 'Datos válidos', 'suscriptor' => $suscriptor
                );
            }
        }
        $this->_response->appendBody(Zend_Json::encode($response));
    }
}