<?php

class Gestor_EstablecimientosController extends App_Controller_Action_Gestor
{

    public function init()
    {
        /* Initialize action controller here */
        parent::init();
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_redirect('/gestor');
        }
        Zend_Layout::getMvcInstance()->active
            = App_Controller_Action_Gestor::MENU_NAME_ESTABLECIMIENTOS;
        $p = new Plugin_CsrfProtect();
        $this->view->csrf = $p->session->key;
    }

    public function indexAction()
    {
        $this->_forward('listar-establecimientos');
    }

    public function listarEstablecimientosAction()
    {
        $this->view->headScript()->appendFile(
            $this->mediaUrl . '/js/gestor/modal.js'
        );
        
        $this->view->headScript()->appendFile(
            $this->mediaUrl . '/js/gestor/filtro.establecimientos.js'
        );
        
        $this->view->headScript()->appendFile(
            $this->mediaUrl . '/js/gestor/filtro.establecimientos.usuario.js'
        );
        
        $this->view->headScript()->appendFile(
            $this->config->app->mediaUrl . '/js/gestor/establecimiento.js'
        );
        
        $this->view->headScript()->appendFile(
            $this->mediaUrl . '/js/gestor/telefonos_establecimiento.js'
        );
        
        $formFiltroEstablecimiento = new Application_Form_FiltroEstablecimientos();
        $this->view->formFiltroEstablecimiento = $formFiltroEstablecimiento;
        $this->view->establecimientos = Application_Model_Establecimiento::
            getEstablecimientos();
    }

    public function buscarEstablecimientosAction()
    {
        $this->_helper->layout->disableLayout();
        $params = $this->_getAllParams();
        $this->view->col = $this->_getParam('col', '');
        $this->view->ord = $this->_getParam('ord', 'DESC');
        $page = $this->_getParam('page', 1);
        $criteria = array();
        if ($params['nombre'] <> '')
            $criteria['nombre'] = $params['nombre'];
        if ($params['estado'] <> '')
            $criteria['estado'] = $params['estado'];
        $establecimientos = new Application_Model_Establecimiento();
        $establecimientos = $establecimientos->getEstablecimientosPaginator($criteria);
        $establecimientos->setCurrentPageNumber($page);
        $this->view->establecimientos = $establecimientos;
        
        $nroPorPage = $establecimientos->getItemCountPerPage();
        $nroPage = $establecimientos->getCurrentPageNumber();
        $nroReg = $establecimientos->getCurrentItemCount();
        
        $this->view->mostrando = "Mostrando ".
            ' '.(($nroPage-1)*$nroPorPage + 1).
            ' - '.((($nroPage-1)*$nroPorPage) + $nroReg).
            ' de '.$establecimientos->getTotalItemCount();
        $this->view->nroregistros = "Registros listados : ".$nroReg;
        
        $this->view->establecimientos = $establecimientos;
        
    }

    public function nuevoEstablecimientoAction()
    {
        $this->view->headScript()->appendFile(
            $this->mediaUrl . '/js/gestor/establecimiento.logo.uploader.js'
        );
        
        $this->view->headScript()->appendFile(
            $this->mediaUrl . '/js/gestor/establecimientos.nuevo.js'
        );
        $id = null;
        $idUs = null;
        $formNEstablecimiento = new Application_Form_Establecimiento($idUs);
        $formNEstablecimiento->validadorRuc($id);
        
        if ($this->_request->isPost()) {
            $allParams = $this->_getAllParams();
            $formValid = $formNEstablecimiento->isValid($allParams);
            if ($formValid) {
                try {
                    $db = $this->getAdapter();
                    $db->beginTransaction();
                    
                    $utilfile = $this->_helper->getHelper('UtilFiles');
                    $nuevoNombre = 
                        $utilfile->_renameFile(
                            $formNEstablecimiento, 'path_imagen', 'establecimiento'
                        );
                    $valuesEstablecimiento = $formNEstablecimiento->getValues();
                    $valuesEstablecimiento['path_imagen'] = $nuevoNombre;
                    $date = date('Y-m-d H:i:s');
                    $valuesEstablecimiento['tipo_establecimiento_id'] =  
                        $valuesEstablecimiento['tipo_establecimiento'];
                    $valuesEstablecimiento['actualizado_por'] = $this->auth['usuario']->id;
                    $valuesEstablecimiento['creado_por'] = $this->auth['usuario']->id;
                    $valuesEstablecimiento['fecha_registro'] = $date;
                    $valuesEstablecimiento['fecha_actualizacion'] = $date;
                    unset($valuesEstablecimiento['tipo_establecimiento']);
                    unset($valuesEstablecimiento['idEst']);
                    
                    $modelEstablecimiento = new Application_Model_Establecimiento();
                    $val = $modelEstablecimiento->insert($valuesEstablecimiento);
                    if ($val) {
                        $this->getMessenger()->success('Establecimiento registrado con exito.');
                    }
                    $db->commit();
                    $this->_redirect('/gestor/establecimientos/listar-establecimientos');
                    
                } catch (Exception $e) {
                    $db->rollBack();
                    $this->getMessenger()->error('Error al guardar el establecimiento.');
                    //echo $e->getMessage();
                }
            } else {
                $this->getMessenger()->error('Error al guardar el establecimiento, verifique los datos.');
                //Zend_Debug::dump($formNEstablecimiento->getErrors());
            }
        }
        
        $this->view->formEstablecimiento = $formNEstablecimiento;
        
    }
    
    public function editarEstablecimientoAction()
    {
        $this->_helper->viewRenderer('nuevo-establecimiento');
        
        $this->view->headScript()->appendFile(
            $this->mediaUrl . '/js/gestor/establecimiento.logo.uploader.js'
        );
        
        $this->view->headScript()->appendFile(
            $this->mediaUrl . '/js/gestor/establecimientos.nuevo.js'
        );
        
        $this->view->idEst =$idEst = $this->_getParam('idEst');
        
        $idUs = null;
        $formNEstablecimiento = new Application_Form_Establecimiento($idEst);
        $formNEstablecimiento->validadorRuc($idEst);
        
        $modelEstablecimiento = new Application_Model_Establecimiento();
        $arrayEstablecimiento = $modelEstablecimiento->getEstablecimiento($idEst);
        $arrayEstablecimiento['tipo_establecimiento'] = 
            $arrayEstablecimiento['tipo_establecimiento_id'];
        $this->view->imgPhoto = $imagen = $arrayEstablecimiento['path_imagen'];
        unset($arrayEstablecimiento['tipo_establecimiento_id']);
        
        $formNEstablecimiento->setDefaults($arrayEstablecimiento);
        
        if ($this->_request->isPost()) {
            $formNEstablecimiento->getElement('path_imagen')->removeValidator('Count');
            $allParams = $this->_getAllParams();
            $formValid = $formNEstablecimiento->isValid($allParams);
            if ($formValid) {
                try {
                    $db = $this->getAdapter();
                    $db->beginTransaction();
                    
                    $utilfile = $this->_helper->getHelper('UtilFiles');
                    $nuevoNombre = 
                        $utilfile->_renameFile(
                            $formNEstablecimiento, 'path_imagen', 'establecimiento'
                        );
                    $valuesEstablecimiento = $formNEstablecimiento->getValues();
                    $date = date('Y-m-d H:i:s');
                    if ($nuevoNombre != '') {
                        @unlink($this->_config->paths->elementsEstablecimientoRoot.$imagen);
                        $valuesEstablecimiento['path_imagen'] = $nuevoNombre;
                    } else {
                        $valuesEstablecimiento['path_imagen'] = $imagen;
                    }
                    $valuesEstablecimiento['tipo_establecimiento_id'] = 
                        $valuesEstablecimiento['tipo_establecimiento'];
                    $valuesEstablecimiento['actualizado_por'] = $this->auth['usuario']->id;
                    $valuesEstablecimiento['fecha_actualizacion'] = $date;
                    unset($valuesEstablecimiento['tipo_establecimiento']);
                    $where = $modelEstablecimiento->getAdapter()->quoteInto('id = ?', $idEst);
                    
                    $val = $modelEstablecimiento->update($valuesEstablecimiento, $where);
                    if ($val) {
                        $this->getMessenger()->success('Establecimiento actualizado con exito.');
                    }
                    $db->commit();
                    $this->_redirect('/gestor/establecimientos/listar-establecimientos');
                    
                } catch (Exception $e) {
                    $db->rollBack();
                    $this->getMessenger()->error('Error al actualizar.');
                    echo $e->getMessage();
                }
            }
        }
        
        $this->view->formEstablecimiento = $formNEstablecimiento;
    }
    
    public function eliminarEstablecimientoAction()
    {
        $data = 0 ;
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $allparams = $this->_getAllParams();
        $idEst = $allparams['idEst'];
        
        if ($this->_request->isPost()) {
            if ($idEst) {
                try {
                    $db = $this->getAdapter();
                    $db->beginTransaction();
                    
                    $modelEstablecimiento = new Application_Model_Establecimiento();
                    
                    $arrayEstablecimiento = $modelEstablecimiento->getEstablecimiento($idEst);
                    @unlink(
                        $this->_config->paths->elementsEstablecimientoRoot.
                        $arrayEstablecimiento['path_imagen']
                    );
                    
                    $where = $modelEstablecimiento->getAdapter()->quoteInto('id = ?', $idEst);
                    $val = $modelEstablecimiento->delete($where);
                    if ($val == 1) {
                        $data = 1;
                    } else {
                        $data = -1;
                    }
                    $db->commit();
                } catch (Exception $e) {
                    $db->rollBack();
                    $data = -1;
                    echo $e->getMessage();
                }
            } else {
                $data = -1;
            }
        }
        $this->_response->appendBody(Zend_Json::encode($data));
    }
    
    public function listarUsuariosEstablecimientosAction()
    {
        $this->_helper->layout->disableLayout();
        $params = $this->_getAllParams();
        $this->view->idEst = $idEstablecimiento = $params['id'];
        $modelEstablecimiento = new Application_Model_Establecimiento();
        $arrayEstablecimiento = $modelEstablecimiento->getEstablecimiento($idEstablecimiento);
        $this->view->nombre = $arrayEstablecimiento['nombre'];
        
        $formFiltroEstablecimiento = new Application_Form_FiltroEstablecimientos();
        $this->view->formFiltroUsuario = $formFiltroEstablecimiento;
           
    }
    
    public function cambioClaveAction()
    {
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
                
                $genPassword =$this->_helper->getHelper('GenPassword');
                $pswd = $genPassword->_genPassword();
                
                $modelUsuario = new Application_Model_Usuario();
                $valor = $modelUsuario->update(
                    array('pswd'=>App_Auth_Adapter_ClubDbTable::generatePassword($pswd)),
                    $modelUsuario->getAdapter()->quoteInto('id = ?', $idUsu)
                );
                
                if ($valor) {
                    $this->_helper->mail->nuevaClaveUsuario(
                        array(
                            'to' => $arrayAdmin['email'],
                            'subject' => $arrayAdmin['email'],
                            'nombre' => ucwords($arrayAdmin['nombres']),
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
    
    public function editarUsuarioAction()
    {
        $data = 0;
        $this->_helper->layout->disableLayout();
        $params = $this->_getAllParams();
        $this->view->idUsu = $idUsu = $params['idUsu'];
        
        $modelBeneUsuario = new Application_Model_Administrador();
        $arrayBeneUsuario =$modelBeneUsuario->getAdministradorxId($idUsu);
        
        $formBeneUsuario = new Application_Form_BeneficioUsuario($idUsu);
        $formBeneUsuario->validadorEmail($idUsu, Application_Form_Login::ROL_ESTABLECIMIENTO);
        $formBeneUsuario->setDefaults($arrayBeneUsuario);
        
        if ( $this->_request->isPost() ) {
            $formValid = $formBeneUsuario->isValid($params);
            if ($formValid) {
                try {
                    $db = $this->getAdapter();
                    $db->beginTransaction();
                    $valuesBeneUsuario = $formBeneUsuario->getValues();
                    //Actualiza Administrador
                    $where = $modelBeneUsuario->getAdapter()
                        ->quoteInto('id = ?', $arrayBeneUsuario['id_admin']);
                        
                    $modelBeneUsuario->update(
                        array(
                        'nombres' => $valuesBeneUsuario['nombres'],
                        'apellido_paterno' => $valuesBeneUsuario['apellido_paterno'],
                        'apellido_materno' => $valuesBeneUsuario['apellido_materno']
                        ), $where
                    );

                    //Actualiza Usuario
                    $modelUsuario = new Application_Model_Usuario();
                    $where = $modelUsuario->getAdapter()
                        ->quoteInto('id = ?', $idUsu);
                        
                    $modelUsuario->update(
                        array(
                            'email'=>$valuesBeneUsuario['email'],
                            'activo'=>$valuesBeneUsuario['activo'],
                            'fecha_actualizacion'=> date('Y-m-d H:i:s')
                        ), 
                        $where
                    );
                    $data = 1;
                    $db->commit();
                
                } catch (Zend_Db_Exception $e) {
                     $db->rollBack();
                     $data = -1;
                     echo $e->getMessage();
                } catch (Zend_Exception $e) {
                    $data = -1;
                    echo $e->getMessage();
                }
            } else {
                if ($data == 0) {
                    $data = -1;
                }
            }
        }
         
        $this->view->data = $data;
        $this->view->formBeneUsuario = $formBeneUsuario;
        
    }

    public function buscarUsuariosAction()
    {
        $this->_helper->layout->disableLayout();
        $params = $this->_getAllParams();
        $criteria = array();
        $this->view->col = $criteria['col'] = $this->_getParam('col', '');
        $this->view->ord = $criteria['ord'] = $this->_getParam('ord', 'DESC');
        $this->view->pag = $page = $this->_getParam('page', 1);
        
        $criteria['idEst'] = $params['idEst'];
        if ($params['nombre'] <> '')
            $criteria['nombre'] = $params['nombre'];
        if ($params['estado'] <> '')
            $criteria['estado'] = $params['estado'];
            
        $usuariosEstablecimiento = new Application_Model_Establecimiento();
        $usuariosEstablecimiento = $usuariosEstablecimiento->getUsuariosPaginator($criteria);
        $usuariosEstablecimiento->setCurrentPageNumber($page);
        
        $nroPorPage = $usuariosEstablecimiento->getItemCountPerPage();
        $nroPage = $usuariosEstablecimiento->getCurrentPageNumber();
        $nroReg = $usuariosEstablecimiento->getCurrentItemCount();
        
        $this->view->mostrando = "Mostrando ".
            ' '.(($nroPage-1)*$nroPorPage + 1).
            ' - '.((($nroPage-1)*$nroPorPage) + $nroReg).
            ' de '.$usuariosEstablecimiento->getTotalItemCount();
        $this->view->nroregistros = "Registros listados : ".$nroReg;
        
        $this->view->establecimientos = $usuariosEstablecimiento;
    }
    
    public function cargafotoAction()
    {
        $img = 'path_imagen';
        $config = Zend_Registry::get('config');
        $r = $this->getRequest();
        if ($r->isPost()) {
            $session = $this->getSession();
            if ($session->__isset("tmp_img")) {
                @unlink($session->__get("tmp_img"));
            }
            $tamanomax = $r->__get("filesize");
            $tamano = $_FILES[$img]['size'];
            if ($tamano <= $tamanomax) {
                $utilfile = $this->_helper->getHelper('UtilFiles');
                $archivo = $_FILES[$img]['name'];
                $tipo = $utilfile->_devuelveExtension($archivo);
                $nombrearchivo = "elements/establecimientos/temp/temp_" . time() . "." . $tipo;
                $session->__set("tmp_img", $nombrearchivo);
                move_uploaded_file($_FILES[$img]['tmp_name'], $nombrearchivo);
                $imgx = new ZendImage();
                $imgx->loadImage(APPLICATION_PATH . "/../public/" . $nombrearchivo);
                echo "success|<img height='".
                    $config->logoestablecimiento->tamano->establecimiento->h."' 
                    width='".$config->logoestablecimiento->tamano->establecimiento->w."' 
                    style='height:".$config->logoestablecimiento->tamano->establecimiento->h."px;
                        width:".$config->logoestablecimiento->tamano->establecimiento->w."px'
                    src='" . SITE_URL . '/' . $nombrearchivo . "' />";
            } else {
                echo "error|Tamaño de archivo sobrepasa el limite Permitido";
            }
        } else {
            echo "error|ERROR";
        }
        die();
    }
    
    public function validarRucAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $nruc = $this->_getParam('ndoc');
        $idEmp = $this->_getParam('idEmp');

        $moduloEstablecimiento = new Application_Model_Establecimiento();
        $isValid ='';
        if ($idEmp!= null) { 
            $isValid = $moduloEstablecimiento->validacionNRuc($nruc, null, $idEmp);
        } else {
            $isValid = $moduloEstablecimiento->validacionNRuc($nruc, null, false);
        }
        
        $data = array(
            'status' => $isValid
        );
        $this->_response->appendBody(Zend_Json::encode($data));
    }
    
    public function validarEmailAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $valUsuario = false;
        
        $allparams = $this->_getAllParams();
        $email = $allparams['value'];
        $idUsu = $allparams['idUsu'];
        
        if ($this->_request->isPost()) {
            if ($idUsu) {
                $modelUsuario = new Application_Model_Usuario();
                $valUsuario = $modelUsuario->validarUsuario(
                    $email,
                    Application_Form_Login::ROL_ESTABLECIMIENTO,
                    $idUsu
                );
            }
        }
        $this->_response->appendBody(Zend_Json::encode($valUsuario));
    }
    
    public function addPhonesEstablecimientoAction()
    {
        $this->_helper->layout->disableLayout();
        //var_dump($this->_getAllParams()); exit;
        $idest = $this->getRequest()->getPost('idEst', '');
        $datEst = Application_Model_Establecimiento::getEstablecimiento($idest);
        $this->view->name = $datEst['nombre'];
        $this->view->codigo = $idest;
        
        $objPhones = new Application_Model_NumeroTelefonicoEstablecimiento();
        $objPhones->setEstablecimiento_id($idest);
        $datPhones = $objPhones->getPhoneNumbersxEstablecimiento();
        $nrofields = count($datPhones);
        
        $frmPhones = new Application_Form_Phones();
        $frmPhones->addElementsPhones($datPhones);
        $frmPhones->addElementPhoneNumber($nrofields+1);
        $frmPhones->addElementIdPhone($nrofields+1);
        $frmPhones->addElementDDLOperador($nrofields+1);
        $frmPhones->addElementCheckboxState($nrofields+1);
        
        $this->view->formPhone = $frmPhones;
        $this->view->nroreg = $nrofields;
    }
    
    public function updatePhonesEstablecimientoAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        
        if ($this->_request->isPost()) {
            //var_dump($this->_getAllParams()); exit;
            $idEsta = $this->getRequest()->getPost('idEstablecimiento', '');
            $db = $this->getAdapter();
            if(!empty($idEsta)):
                try {
                $db->beginTransaction();
                $objPhones = new Application_Model_NumeroTelefonicoEstablecimiento();
                $post = $this->_getAllParams();
                $data = array('establecimiento_id'=>$idEsta);
                $idsDelete = ''; $nroRegPhone = 0;
                foreach ($post as $ind=>$value):
                    $extrae = substr($ind, 0, -1);
                    $nro = substr($ind, -1);
                    if ($extrae=='txtNumberPhone') {
                        //var_dump($ind,$nro); exit;
                        $data['numero_telefonico'] = $post['txtNumberPhone'.$nro];
                        $data['operador'] = $post['operador'.$nro];
                        $data['activo'] = !empty($post['chkActivo'.$nro])?1:0;
                        $data['actualizado_por'] = $this->auth['usuario']->id;
                        $data['fecha_actualizacion'] = date('Y-m-d h:i:s');
                        if (!empty($post['idphone'.$nro])) {
                            //$data['id'] = $post['idphone'.$nro];
                            $objPhones->update($data, "id = '".$post['idphone'.$nro]."'");
                            $idsDelete .= $post['idphone'.$nro].",";
                            $nroRegPhone++;
                        } else {
                            $data['fecha_creacion'] = date('Y-m-d h:i:s');
                            $data['creado_por'] = $this->auth['usuario']->id;
                            //var_dump($data);
                            $idNumber = $objPhones->insert($data);
                            $idsDelete .= $idNumber.",";
                            $nroRegPhone++;
                        }
                    }
                endforeach;
                //var_dump($idsDelete); exit;
                if (!empty($idsDelete)) {
                    $idsDelete = substr($idsDelete, 0, -1);
                    $objPhones->delete(
                        'id not in('.$idsDelete.')'." and establecimiento_id='".$idEsta."'"
                    );
                }
                if ($nroRegPhone<=0) { 
                    $objPhones->delete("establecimiento_id='".$idEsta."'"); 
                }
                //exit;
                $db->commit();
                echo 'Datos Grabados exitosamente';
                } catch (Exception $e) {
                    $db->rollBack();
                    echo $e->getMessage();
                }
            endif;
        }
    }
    
    public function validUniquePhoneNumberAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        //var_dump($this->_getAllParams()); exit;
        $valphone = $this->getRequest()->getPost('phone', '');
        $idphone = $this->getRequest()->getPost('phoneid', '');
        
        $valphones = $this->getRequest()->getPost('phones', '');
        $idphones = $this->getRequest()->getPost('phoneids', '');

        $p = new Plugin_CsrfProtect();
        $p->_initializeTokens();
        $message = array('flag'=>true,'nrophone'=>0, 'csrf' => $p->session->key);
        if (!empty($valphone)) {
            $objTel = new Application_Model_NumeroTelefonicoEstablecimiento();

            if (empty($idphone)) {
                $datTel = $objTel->getRowByColumnTable('numero_telefonico', $valphone);
                if (!empty($datTel)) {
                    $message['flag'] = false;
                }
            } else {
                $datTel = $objTel->getRowByColumnTable('id', $idphone);
                if ($datTel['numero_telefonico']!=$valphone) {
                    $datTel = $objTel->getRowByColumnTable('numero_telefonico', $valphone);
                    if (!empty($datTel)) {
                        $message['flag'] = false;
                    }
                }
            }
        } elseif (!empty($valphones)) {
            $item = explode(',', substr($valphones, 0, -1));
            $iditem = explode(',', substr($idphones, 0, -1));
            $multiflag = true;
            //var_dump($item, $iditem); exit;
            foreach ($item as $i=>$telefono) {
                $objTel = new Application_Model_NumeroTelefonicoEstablecimiento();

                if (empty($iditem[$i])) {
                    $datTel = $objTel->getRowByColumnTable('numero_telefonico', $telefono);
                    if (!empty($datTel)) {
                        $multiflag = $multiflag && false;
                    }
                } else {
                    $datTel = $objTel->getRowByColumnTable('id', $iditem[$i]);
                    if ($datTel['numero_telefonico']!=$telefono) {
                        $datTel = $objTel->getRowByColumnTable('numero_telefonico', $telefono);
                        if (!empty($datTel)) {
                            $multiflag = $multiflag && false;
                        }
                    }
                }
                $message['nrophone'] ++;
            }
            $message['flag'] = $multiflag;
        }
        $this->_response->appendBody(Zend_Json::encode($message));
    }
}
