<?php

class Admin_UsuariosController extends App_Controller_Action_Admin
{
    /*private $_url = array(
        'gestor' => '/gestor',
        'establecimiento' => '/establecimiento',
        'admin' => '/admin'
    );*/
    private $_url = array(
        '1' => '/gestor',
        '2' => '/establecimiento',
        '3' => '/admin'
    );
    private $_perfil = array(
        'gestor' => 'Gestor del Portal',
        'establecimiento' => 'Administrador de Establecimiento',
        'admin' => 'Administrador del Portal'
    );

    public function init()
    {
        parent::init();
        $this->_usuario = new Application_Model_Usuario();
    }

    public function indexAction()
    {
        $this->view->headScript()->appendFile(
            $this->config->app->mediaUrl . '/js/admin/usuarios.js'
        );
        $this->view->headLink()->appendStylesheet(
            $this->mediaUrl . '/js/datepicker/themes/ui-lightness/ui.all.css', 'all'
        );
        $this->view->headScript()->appendFile(
            $this->mediaUrl . '/js/datepicker/ui/ui.core.js'
        );
        $this->view->headScript()->appendFile(
            $this->mediaUrl . '/js/datepicker/ui/ui.datepicker.js'
        );
        $this->view->headScript()->appendFile(
            $this->mediaUrl . '/js/datepicker/ui/i18n/ui.datepicker-es.js'
        );
        
        Zend_Layout::getMvcInstance()->active = App_Controller_Action_Admin::MENU_NAME_USUARIOS;

        $objUsuario = new Application_Model_Usuario();

        $page = $this->_getParam('page', 1);
        $idEstablecimiento = $this->_getParam('establecimiento');
        $estado = $this->_getParam('estado', '');
        $tipoFiltro = $tipo = $this->_getParam('tipo',1);
        $subTipo = $this->_getParam('subTipo');
        $ord = $this->_getParam('ord');
        $col = $this->_getParam('col');
        $query = $this->_getParam('query');
        if ($tipo=="Todos") { $tipoFiltro = ""; }
        elseif ($tipo == 2) { 
            if (!empty($subTipo)) { $tipoFiltro = $subTipo; }
            else { $tipoFiltro = '4,5,7'; }
        } else {
            $subTipo="";
        }
        $paginator = $objUsuario->paginarListaUsuarios(
            $query, $idEstablecimiento, $estado, $tipoFiltro, $ord, $col
        );

//        $this->view->MostrandoN = $paginator->getItemCount($paginator->getItemsByPage($page));
//        $this->view->MostrandoDe = $paginator->getTotalItemCount();
//        $this->view->totalitems = $paginator->getItemCount($paginator->getItemsByPage($page));
        $paginator->setCurrentPageNumber($page);

        $this->view->listaUsuarios = $paginator;
        $this->view->ord = $ord;
        $this->view->col = $col;

        $this->view->estado = $estado;
        $this->view->tipo = $tipo;
        $this->view->subTipo = $subTipo;
        $this->view->idEstablecimiento = $idEstablecimiento;
        $this->view->query = $query;


        $filtros = new Application_Form_FiltroUsuarios();
        $filtros->setIdEstablecimiento($idEstablecimiento);
        $filtros->setPerfil($tipo);
        $filtros->setSubPerfil($subTipo);
        $filtros->setEstado($estado);
        $filtros->setPerfilSuscriptor();
        $this->view->filtros = $filtros;
        
        $nroPorPage = $paginator->getItemCountPerPage();
        $nroPage = $paginator->getCurrentPageNumber();
        $nroReg = $paginator->getCurrentItemCount();
        
        $this->view->mostrando = "Mostrando ".
            ' '.(($nroPage-1)*$nroPorPage + 1).
            ' - '.((($nroPage-1)*$nroPorPage) + $nroReg).
            ' de '.$paginator->getTotalItemCount();
        $this->view->beneficios = $paginator;
        $this->view->nroregistros = "Registros listados : ".$nroReg;
    }

    public function obtenerInfoUsuarioAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $id = $this->_getParam('id', null);
        $response = array('status' => false, 'perfil' => array());
        $response = array();
        if (!is_null($id)) {
            $perfil = Application_Model_Usuario::getPerfilUsuario($id);
            $response['status'] = true;
            $response['perfil'] = $perfil;
        } else {
            $response['status'] = false;
        }
        $this->_response->appendBody(Zend_Json::encode($response));
    }

    public function editarUsuarioAction()
    {
        //--> se esta moficando para que tbn se lea los suscriptores.
        $this->_helper->layout->disableLayout();
        $data = 0;
        $id = $this->_getParam('id', null);
        $tip = $this->_getParam('tip', "S");
        $formUsuario = new Application_Form_Administrador($id);
        $formUsuario->validadorNumDoc($id);
        if ($this->_request->isPost()) {
            $perfil = Application_Model_Usuario::getPerfilUsuario($id);

            $params = $this->_getAllParams();
            if ($formUsuario->isValid($params)) {
                //validarUsuario($params['email'], $params['rol'], $id) //change
                if ($tip=="S") {$params['rol']='null';}
                //elseif ($tip=="2") {$params['rol']=$params['subRol'];}
                                
                if (Application_Model_Usuario::validUser(
                    $params['email'], $params['rol'], $id
                )) {
                    
                    if ($tip=="S") {
                        $valuesUsuario = $formUsuario->getValues();                        
                        
                        $db2 = Zend_Registry::get('db2');
                        
                        $db = $this->getAdapter();
                        $db->beginTransaction();
                        try {

                            $date = date('Y-m-d H:i:s');
                            
                            $objUsuario = new Application_Model_Usuario();
                            $where = $db->quoteInto('id = ?', $id);

                            $objUsuario->update(
                                array(
                                'email' => $valuesUsuario['email']                                
                                ), $where
                            );
                            
                            $db->commit();
                            $this->getMessenger()->success('El usuario se actualizó correctamente');
//                            $this->_redirect('admin/usuarios');
                        } catch (Exception $e) {
                            $db->rollBack();
                            echo $e->getMessage();
                            $this->getMessenger()->error(
                                'Hubo un error en la actualización del usuario, inténtelo mas tarde.'
                            );
                        }
                        $codSuscriptor = Application_Model_Suscriptor::getSuscriptorById(array("usuario_id"=>$id));
                        if(!empty($codSuscriptor['codigo_suscriptor'])){
                            $db2->query("update t_actsuscriptor set 
                                des_email='".$valuesUsuario['email']."',
                                des_email_act='".$valuesUsuario['email']."',
                                est_carga='N',
                                fch_actualizacion_dt=getdate()
                                where cod_entesuscriptor = '".$codSuscriptor['codigo_suscriptor']."'");
                        } elseif ($codSuscriptor['es_suscriptor']==0 && $codSuscriptor['es_invitado']==1) {
                            $db2->query("update t_actsuscriptor set 
                                des_email='".$valuesUsuario['email']."',
                                des_email_act='".$valuesUsuario['email']."',
                                est_carga='N',
                                fch_actualizacion_dt=getdate()
                                where cod_tipdocid = '".$codSuscriptor['tipo_documento']."' 
                                    and des_numdocid = '".$codSuscriptor['numero_documento']."'");
                        }
                        
                    } else {
                        $valuesUsuario = $formUsuario->getValues();
                        $tpdoc = explode('#', $valuesUsuario['tipo_documento']);
                        $valuesUsuario['tipo_documento'] = strtoupper($tpdoc[0]);
                        $establecimientoId = null;
                        if ($valuesUsuario['rol'] == 'establecimiento')
                            $establecimientoId = $valuesUsuario['establecimiento'];

                        $objPerf = new Application_Model_Perfil();
                        $objPerf->setId($params['rol']);
                        $datPerf = $objPerf->getPerfilById();

                        $establecimientoId = (
                            //$valuesUsuario['rol'] == 'establecimiento' ? //change
                            $datPerf['modulo_id'] == '2' ?
                            $valuesUsuario['establecimiento'] : null
                        );
                        try {
                            $db = $this->getAdapter();
                            $db->beginTransaction();

                            $date = date('Y-m-d H:i:s');
                            $valuesAdmin['nombres'] = $valuesUsuario['nombres'];
    //                        $valuesAdmin['apellidos'] 
    //                            = $valuesUsuario['apellido_paterno'] . ' ' . $valuesUsuario['apellido_materno'];
                            $valuesAdmin['apellido_paterno'] = $valuesUsuario['apellido_paterno'];
                            $valuesAdmin['apellido_materno'] = $valuesUsuario['apellido_materno'];
                            $valuesAdmin['apellidos'] = $valuesUsuario['apellido_paterno'].' '.
                                    $valuesUsuario['apellido_materno'];
                            $valuesAdmin['sexo'] = $valuesUsuario['sexo'];
                            $valuesAdmin['fecha_nacimiento'] = date_format(
                                DateTime::createFromFormat(
                                    'd/m/Y', $valuesUsuario['fecha_nacimiento']
                                ), 'Y-m-d'
                            );
                            $valuesAdmin['tipo_documento'] = $valuesUsuario['tipo_documento'];
                            $valuesAdmin['numero_documento'] = $valuesUsuario['numero_documento'];
                            unset($valuesUsuario['nombres']);
                            unset($valuesUsuario['apellido_paterno']);
                            unset($valuesUsuario['apellido_materno']);
                            unset($valuesUsuario['sexo']);
                            unset($valuesUsuario['fecha_nacimiento']);
                            unset($valuesUsuario['tipo_documento']);
                            unset($valuesUsuario['numero_documento']);
                            unset($valuesUsuario['establecimiento']);
                            $valuesUsuario['fecha_actualizacion'] = $date;
                            
                            if ($valuesUsuario['rol']=="2") {$valuesUsuario['rol']=$valuesUsuario['subRol'];}
                            $valuesUsuario['perfil_id'] = $valuesUsuario['rol'];

                            $objMod = new Application_Model_Modulo();
                            $valuesUsuario['rol'] = $objMod->getNombreModulo($datPerf['modulo_id']);
                            
                            $objUsuario = new Application_Model_Usuario();
                            $where = $db->quoteInto('id = ?', $id);

                            $objUsuario->update(
                                array(
                                'email' => $valuesUsuario['email'],
                                //'rol' => $valuesUsuario['rol'], //change
                                'perfil_id' => $valuesUsuario['perfil_id'],
                                'rol' => $valuesUsuario['rol'],
                                'activo' => $valuesUsuario['activo']
                                ), $where
                            );
                            $objAdministrador = new Application_Model_Administrador();
                            $where = $db->quoteInto('usuario_id = ?', $id);
                            $objAdministrador->update($valuesAdmin, $where);
                            if (
                                !is_null($establecimientoId)
                                //&& $valuesUsuario['rol'] == 'establecimiento' //change
                                && $datPerf['modulo_id'] == '2'
                            ) {
                                $objEsta = new Application_Model_EstablecimientoUsuario();
                                $valuesEstablecimiento['usuario_id'] = $id;
                                $valuesEstablecimiento['establecimiento_id'] = $establecimientoId;

                                if (empty($perfil['eu_id'])) { 
                                    //is_null($perfil['eu_id']) && $perfil['eu_id'] <> ''
                                    $lastEUId =
                                        $db->insert('establecimiento_usuario', $valuesEstablecimiento);
                                } else {
                                    $where = $db->quoteInto('id = ?', $perfil['eu_id']);
                                    $objEsta->update(
                                        $valuesEstablecimiento, $where
                                    );
                                }
                                /*var_dump($perfil,$valuesEstablecimiento); 
                                exit;*/
                            } else {
                                $where = $db->quoteInto('usuario_id = ?', $id);
                                $objEsta = new Application_Model_EstablecimientoUsuario();
                                $objEsta->delete($where);
                            }
                            $db->commit();
                            $this->getMessenger()->success('El usuario se actualizó correctamente');
                            $this->_redirect('admin/usuarios');
                        } catch (Exception $e) {
                            $db->rollBack();
                            echo $e->getMessage();
                            $this->getMessenger()->error(
                                'Hubo un error en la actualización del usuario, inténtelo mas tarde'
                            );
                        }
                    }
                } else {
                    $this->getMessenger()->error(
                        'El correo electrónico ya está registrado con el Perfil seleccionado'
                    );
                }
                $this->_redirect('admin/usuarios');
            } else {
                $this->getMessenger()->error(
                    'Los datos ingresados no son correctos, revise la información ingresada.'
                );
                $this->_redirect('admin/usuarios');
            }
        } else {
            if (!is_null($id)) {
                if ($tip=="S") {
                    $perfil = Application_Model_Suscriptor::getSuscriptorById(array("usuario_id"=>$id));
                    $perfil["rol"]="S";
                    $formUsuario->setRolSuscriptor();                    
                } else {
                    $perfil = Application_Model_Usuario::getPerfilUsuario($id);
                    if ($perfil['nivel']==2) {
                        $perfil["rol"]=$perfil["padre_perfil_id"];
                        $perfil["subRol"]=$perfil["perfil_id"];
                    }
                }
                if ($perfil['fecha_nacimiento'] != '' && isset($perfil['fecha_nacimiento'])) {
                    $perfil['fecha_nacimiento'] =
                        date('d/m/Y', strtotime($perfil['fecha_nacimiento']));
                }
                foreach (array_keys(Application_Form_Administrador::$valorDocumento) as $valor) {
                    $valor = explode('#', $valor);
                    if (strtoupper($perfil['tipo_documento']) == $valor[0]) {
                        $perfil['tipo_documento']
                            = strtoupper($perfil['tipo_documento']) . '#' . $valor[1];
                    }
                }
                //$perfil['rol'] = 
                $formUsuario->setDefaults($perfil);
            }
        }
        $this->view->id = $id;
        $this->view->tip = $tip;
        $this->view->formAdministrador = $formUsuario;
        $this->view->data = $data;
    }

    public function nuevoUsuarioAction()
    {
        $this->_helper->layout->disableLayout();
//        $this->_helper->viewRenderer->setNoRender();
        $data = 0;
        $formAdmin = new Application_Form_Administrador(null);
        $formAdmin->validadorNumDoc(null);

        if ($this->_request->isPost()) {
            $params = $this->_getAllParams();
            if ($formAdmin->isValid($params)) {
                $pswd = uniqid();
                $newPswd = App_Auth_Adapter_ClubDbTable::generatePassword($pswd);
                $valuesUsuario = $formAdmin->getValues();
                $tpdoc = explode('#', $valuesUsuario['tipo_documento']);
                $valuesUsuario['tipo_documento'] = strtoupper($tpdoc[0]);
                
                $objPerf = new Application_Model_Perfil();
                $objPerf->setId($valuesUsuario['rol']);
                $datPerf = $objPerf->getPerfilById();
                $establecimientoId = (
                    //$valuesUsuario['rol'] == 'establecimiento' ? //change
                    $datPerf['modulo_id'] == '2' ?
                    $valuesUsuario['establecimiento'] : null
                );
                
                $rol_id=($valuesUsuario['rol']==2)?$valuesUsuario['subRol']:$valuesUsuario['rol'];
                //if (Application_Model_Usuario::validarUsuario( //change
                if (Application_Model_Usuario::validUser(
                    $valuesUsuario['email'], $rol_id
                )) {
                    try {
                        $db = $this->getAdapter();
                        $db->beginTransaction();
                        
                        $date = date('Y-m-d H:i:s');
                        $valuesAdmin['nombres'] = $valuesUsuario['nombres'];
//                        $valuesAdmin['apellidos'] 
//                            = $valuesUsuario['apellido_paterno'] . ' ' . $valuesUsuario['apellido_materno'];
                        $valuesAdmin['apellido_paterno'] = $valuesUsuario['apellido_paterno'];
                        $valuesAdmin['apellido_materno'] = $valuesUsuario['apellido_materno'];
                        $valuesAdmin['apellidos'] = $valuesUsuario['apellido_paterno'].' '.
                                $valuesUsuario['apellido_materno'];                        
                        $valuesAdmin['sexo'] = $valuesUsuario['sexo'];
                        $valuesAdmin['fecha_nacimiento'] = date_format(
                            DateTime::createFromFormat(
                                'd/m/Y', $valuesUsuario['fecha_nacimiento']
                            ), 'Y-m-d'
                        );
                        $valuesAdmin['tipo_documento'] = $valuesUsuario['tipo_documento'];
                        $valuesAdmin['numero_documento'] = $valuesUsuario['numero_documento'];
                        unset($valuesUsuario['nombres']);
                        unset($valuesUsuario['apellido_paterno']);
                        unset($valuesUsuario['apellido_materno']);
                        unset($valuesUsuario['sexo']);
                        unset($valuesUsuario['fecha_nacimiento']);
                        unset($valuesUsuario['tipo_documento']);
                        unset($valuesUsuario['numero_documento']);
                        unset($valuesUsuario['establecimiento']);
                        $valuesUsuario['pswd'] = $newPswd;
                        $valuesUsuario['fecha_registro'] = $date;
                        $valuesUsuario['fecha_actualizacion'] = $date;
                        
                        if ($valuesUsuario['rol']==2) {
                            if (!empty($valuesUsuario['subRol'])) $valuesUsuario['perfil_id']=$valuesUsuario['subRol'];
                            else throw new Exception("¡Seleccione un sub rol!");
                        } else {
                            $valuesUsuario['perfil_id'] = $valuesUsuario['rol'];
                        }
                        unset($valuesUsuario['subRol']);
                        unset($valuesUsuario['rol']);
                        $objMod = new Application_Model_Modulo();
                        $valuesUsuario['rol'] = $objMod->getNombreModulo($datPerf['modulo_id']);
                        
                        $lastUId = $this->_usuario->insert($valuesUsuario);
                        $valuesAdmin['usuario_id'] = $lastUId;
                        $lastAId = $db->insert('administrador', $valuesAdmin);

                        if (!is_null($establecimientoId)
                            //&& $valuesUsuario['rol'] == 'establecimiento' //change
                            && $datPerf['modulo_id'] == '2' && $valuesUsuario['perfil_id'] != '7'
                        ) {
                            $valuesEstablecimiento['usuario_id'] = $lastUId;
                            $valuesEstablecimiento['establecimiento_id'] = $establecimientoId;
                            $lastEUId =
                                $db->insert('establecimiento_usuario', $valuesEstablecimiento);
                            Application_Model_Establecimiento
                            ::updateNumeroUsuarioEstablecimiento($establecimientoId);
                        }

                        $this->_helper->mail->nuevoAdm(
                            array(
                                'to' => $valuesUsuario['email'],
                                'user' => $valuesAdmin['nombres'],
                                'pswd' => $pswd,
                                'url' => $this->config->app->siteUrl .
                                //$this->_url[$valuesUsuario['rol']], //change
                                $this->_url[$datPerf['modulo_id']],
                                //'perfil' => $this->_perfil[$valuesUsuario['rol']]
                                'perfil' => $datPerf['nombre']
                            )
                        );
                        $this->getMessenger()->success('El usuario se registro correctamente');
                        $db->commit();
                        $this->_redirect('admin/usuarios');
                    } catch (Exception $e) {
                        $db->rollBack();
                        echo $e->getMessage();
                        $this->getMessenger()->error(
                            'Hubo un error en el registro del usuario, inténtelo mas tarde'
                        );
                    }
                } else {
                    $this->getMessenger()->error(
                        'El correo electrónico ya está registrado con el Perfil seleccionado'
                    );
                }
            } else {
                $this->getMessenger()->error(
                    'Hubo un error en el ingreso del usuario, revise los datos.'
                );
            }
            $this->_redirect('admin/usuarios');
        }
        $this->view->formAdministrador = $formAdmin;
        $this->view->data = $data;
    }

    public function cambioClaveAction()
    {
        $p = new Plugin_CsrfProtect();
        $this->view->csrf = $p->session->key;
        
        $this->_helper->layout->disableLayout();

        $idUsu = $this->_getParam('idUsu');
        $data = 0;
        $modelAdmin = new Application_Model_Administrador();
        $arrayAdmin = $modelAdmin->getAdministradorxId($idUsu);

        $this->view->nombres = $arrayAdmin['nombres'];
        $this->view->apellidos = $arrayAdmin['apellidos'];
        $this->view->email = $arrayAdmin['email'];
        $this->view->idUsu = $idUsu;
        if ($this->_request->isPost()) {
            try {
                $db = $this->getAdapter();
                $db->beginTransaction();

                $genPassword = $this->_helper->getHelper('GenPassword');
                $pswd = $genPassword->_genPassword();

                $modelUsuario = new Application_Model_Usuario();
                $valor = $modelUsuario->update(
                    array( 'pswd' => App_Auth_Adapter_ClubDbTable::generatePassword($pswd)), 
                    $modelUsuario->getAdapter()->quoteInto('id = ?', $idUsu)
                );

                if ($valor) {
                    $this->_helper->mail->nuevaClaveAdministrador(
                        array(
                            'to' => $arrayAdmin['email'],
                            'subject' => $arrayAdmin['email'],
                            'nombre' => ucwords($arrayAdmin['nombres']),
                            'url' => $this->config->app->siteUrl . $this->_url[$arrayAdmin['rol']],
                            'pswd' => $pswd,
                        )
                    );
                    $data = 1;
                }

                $db->commit();
            } catch (Zend_Db_Exception $e) {
                $db->rollBack();
                $data = -1;
                echo $e->getMessage();
            } catch (Zend_Exception $e) {
                $data = -1;
                echo $e->getMessage();
            }
        }
        $this->view->data = $data;
    }
    
    public function getModuloAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        //var_dump($this->_getAllParams()); exit;
        $response = array('modulo_id' => '');
        $perfilId = $this->getRequest()->getParam('id', '');
        $objPerf = new Application_Model_Perfil();
        $objPerf->setId($perfilId);
        $source = $objPerf->getPerfilById();
        $response = !empty($source) ? $source['modulo_id'] : $response;
        $this->_response->appendBody(Zend_Json::encode($response));
    }
    
    public function reasignaClaveAction()
    {
        $this->_helper->layout->disableLayout();
        //$this->_helper->viewRenderer->setNoRender();
        //var_dump($this->_getAllParams()); //exit;
        
        $formCambioClave = new Application_Form_CambioClave();
        $formCambioClave->addOldPswd();
        $formCambioClave->setAction('/admin/usuarios/reasigna-clave');
        
        $formCambioClave->addComboRolUser();
        $formCambioClave->addComboSubRolUser();        
        $formCambioClave->addEmailUser();
        $envio = array();
        if ($this->_request->isPost()) {
            $this->_helper->viewRenderer->setNoRender();
            
            $allParams = $this->_getAllParams();
            $validClave = $formCambioClave->isValid($allParams);
            //var_dump($validClave, $allParams); exit;
            if ($validClave) {
                $valuesClave = $formCambioClave->getValues();
                try {
                    $db = $this->getAdapter();
                    $db->beginTransaction();
                    
                    $email = $valuesClave['txtemail'];
                    $idperfil = $valuesClave['cborol'];
                    $idsubperfil = $valuesClave['cbosubrol'];
                    if ($valuesClave['cborol']==2) {
                        if (empty($idsubperfil)) $idperfil='';
                        else $idperfil=$idsubperfil;
                    }
                    //Antes de actualizar verificamos si los datos son existentes
                    $objUsuario = new Application_Model_Usuario();
                    $validUser = $objUsuario->validExistsUser($email, $idperfil);
                    if (!empty($validUser)) {
                        $idUsuario = $validUser['id'];
                        
                        $emailuser = $this->auth['usuario']->email;
                        $validLogin = Application_Model_Usuario::
                            validacionPswd($valuesClave['oldpswd'], '', $emailuser);
                        if ($validLogin) {
                            //Captura de los datos de usuario
                            $valuesClave['pswd'] =
                                App_Auth_Adapter_ClubDbTable::generatePassword($valuesClave['pswd']);
                            unset($valuesClave['pswd2']);
                            unset($valuesClave['oldpswd']);
                            unset($valuesClave['cborol']);
                            unset($valuesClave['cbosubrol']);
                            unset($valuesClave['txtemail']);
                            
                            $where = $this->_usuario->getAdapter()
                                ->quoteInto('id = ?', $idUsuario);
                            $this->_usuario->update($valuesClave, $where);
                            $db->commit();

                            //$this->getMessenger()->success('Se cambio la clave con éxito!');
                            $envio['message'] = 'Se cambio la clave con éxito!';
                            $envio['state'] = 'success';
                        } else {
                            $envio['message'] = 'Contraseña Incorrecta!';
                            $envio['state'] = 'error';
                        }
                    } else {
                        $envio['message'] = 'Usuario no Existe!';
                        $envio['state'] = 'error';
                    }
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
                $envio['message'] = 'Hubo un error al intentar actualizar los datos,'
                    .' verificar si la información es correcta';
                $envio['state'] = 'error';
            }
            $this->_response->appendBody(Zend_Json::encode($envio));
        }
        $this->view->formCambioClave = $formCambioClave;
    }
}

