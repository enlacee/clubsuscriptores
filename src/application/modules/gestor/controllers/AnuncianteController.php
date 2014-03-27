<?php

class Gestor_AnuncianteController extends App_Controller_Action_Gestor
{

    public function init()
    {
        /* Initialize action controller here */
        parent::init();
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_redirect('/gestor');
        }
        Zend_Layout::getMvcInstance()->active
            = App_Controller_Action_Gestor::MENU_NAME_ANUNCIANTE;
        $p = new Plugin_CsrfProtect();
        $this->view->csrf = $p->session->key;
    }

    public function indexAction()
    {
        $this->_forward('listar-anunciante');
    }

    public function listarAnuncianteAction()
    {
//        $this->view->headScript()->appendFile(
//            $this->mediaUrl . '/js/gestor/modal.js'
//        );
//        
        $this->view->headScript()->appendFile(
            $this->mediaUrl . '/js/gestor/filtro.anunciante.js'
        );
        
//        $this->view->headScript()->appendFile(
//            $this->mediaUrl . '/js/gestor/filtro.establecimientos.usuario.js'
//        );
//        
//        $this->view->headScript()->appendFile(
//            $this->config->app->mediaUrl . '/js/gestor/establecimiento.js'
//        );
//        
//        $this->view->headScript()->appendFile(
//            $this->mediaUrl . '/js/gestor/telefonos_establecimiento.js'
//        );
        
        $formFiltroAnunciante = new Application_Form_FiltroAnunciante();
        $this->view->formFiltroAnunciante = $formFiltroAnunciante;
//        $this->view->establecimientos = Application_Model_Anunciante::
//            getAnunciantes();
    }

    public function buscarAnuncianteAction()
    {
        $this->_helper->layout->disableLayout();
//        $params = $this->_getAllParams();
        $params['nombre'] = $this->_getParam('nombre', '');
        $params['estado'] = $this->_getParam('estado', '');
        
        $this->view->col = $this->_getParam('col', '');
        $this->view->ord = $this->_getParam('ord', 'DESC');
        $page = $this->_getParam('page', 1);
        $criteria = array();
        if ($params['nombre'] <> '')
            $criteria['nombre'] = $params['nombre'];
        if ($params['estado'] <> '')
            $criteria['estado'] = $params['estado'];
        $anunciante = new Application_Model_Anunciante();
        $anunciante = $anunciante->getAnunciantePaginator($criteria);
        $anunciante->setCurrentPageNumber($page);
        $this->view->anunciante = $anunciante;
        
        $nroPorPage = $anunciante->getItemCountPerPage();
        $nroPage = $anunciante->getCurrentPageNumber();
        $nroReg = $anunciante->getCurrentItemCount();
        
        $this->view->mostrando = "Mostrando ".
            ' '.(($nroPage-1)*$nroPorPage + 1).
            ' - '.((($nroPage-1)*$nroPorPage) + $nroReg).
            ' de '.$anunciante->getTotalItemCount();
        $this->view->nroregistros = "Registros listados : ".$nroReg;
        
        $this->view->anunciante = $anunciante;
        
    }

    public function nuevoAnuncianteAction()
    {        
        $this->view->headScript()->appendFile(
            $this->mediaUrl . '/js/gestor/anunciante.nuevo.js'
        );
        $id = null;
        $formAnunciante = new Application_Form_Anunciante();
        $formAnunciante->validadorRuc($id);
        
        if ($this->_request->isPost()) {
            $allParams = $this->_getAllParams();
            $formValid = $formAnunciante->isValid($allParams);
            if (!$formValid) {
                //var_dump($formBeneficio->getElements());
                foreach ($formAnunciante->getElements() as $key => $e){
                    //$e   = new Zend_Form_Element();
                    var_dump($key);
                    var_dump($e->getErrors());
                }
                exit;
            }
            if ($formValid) {
                try {
                    $db = $this->getAdapter();
                    $db->beginTransaction();
                    
                    $valuesAnunciante = $formAnunciante->getValues();
                    $date = date('Y-m-d H:i:s');
                    $valuesAnunciante['actualizado_por'] = $this->auth['usuario']->id;
                    $valuesAnunciante['creado_por'] = $this->auth['usuario']->id;
                    $valuesAnunciante['fecha_registro'] = $date;
                    $valuesAnunciante['fecha_actualizacion'] = $date;
                    unset($valuesAnunciante['idEst']);
                    
                    $modelAnunciante = new Application_Model_Anunciante();
                    $val = $modelAnunciante->insert($valuesAnunciante);
                    if ($val) {
                        $this->getMessenger()->success('Anunciante registrado con exito.');
                    }
                    $db->commit();
                    $this->_redirect('/gestor/anunciante/listar-anunciante');
                    
                } catch (Exception $e) {
                    $db->rollBack();
                    $this->getMessenger()->error('Error al guardar el Anunciante.');
                    echo $e->getMessage();
                }
            } else {
                $this->getMessenger()->error('Error al guardar el Anunciante, verifique los datos.');
                //Zend_Debug::dump($formNAnunciante->getErrors());
            }
        }
        
        $this->view->formAnunciante = $formAnunciante;
        
    }
    
    public function editarAnuncianteAction()
    {
        $this->_helper->viewRenderer('nuevo-anunciante');
        
//        $this->view->headScript()->appendFile(
//            $this->mediaUrl . '/js/gestor/establecimiento.logo.uploader.js'
//        );
        
        $this->view->headScript()->appendFile(
            $this->mediaUrl . '/js/gestor/anunciante.nuevo.js'
        );
        
        $this->view->idEst =$idEst = $this->_getParam('idEst');
        
        $formNAnunciante = new Application_Form_Anunciante($idEst);
        $formNAnunciante->validadorRuc($idEst);
        
        $modelAnunciante = new Application_Model_Anunciante();
        $arrayAnunciante = $modelAnunciante->getAnunciante($idEst);
                
        $formNAnunciante->setDefaults($arrayAnunciante);
        
        if ($this->_request->isPost()) {
            $allParams = $this->_getAllParams();
            $formValid = $formNAnunciante->isValid($allParams);
            if ($formValid) {
                try {
                    $db = $this->getAdapter();
                    $db->beginTransaction();
                    
                    $valuesAnunciante = $formNAnunciante->getValues();
                    $date = date('Y-m-d H:i:s');
                    
                    $valuesAnunciante['actualizado_por'] = $this->auth['usuario']->id;
                    $valuesAnunciante['fecha_actualizacion'] = $date;
                    
                    $where = $modelAnunciante->getAdapter()->quoteInto('id = ?', $idEst);
                    
                    $val = $modelAnunciante->update($valuesAnunciante, $where);
                    if ($val) {
                        $this->getMessenger()->success('Anunciante actualizado con exito.');
                    }
                    $db->commit();
                    $this->_redirect('/gestor/anunciante/listar-anunciante');
                    
                } catch (Exception $e) {
                    $db->rollBack();
                    $this->getMessenger()->error('Error al actualizar.');
                    echo $e->getMessage();
                }
            }
        }
        
        $this->view->formAnunciante = $formNAnunciante;
    }
    
    public function eliminarAnuncianteAction()
    {
        $this->_helper->layout->disableLayout();
        $modelAnunciante = new Application_Model_Anunciante();
        
        if ($this->getRequest()->isPost()) {
            $this->_helper->viewRenderer->setNoRender();
            
            $messages = array();
            $idAnun = $this->getRequest()->getPost('id', '');
            try {
                $db = $this->getAdapter();
                $db->beginTransaction();
                
                $data['elog'] = '1';
                $data['fecha_de_baja'] = date('Y-m-d h:i:s');
                $data['de_baja_por'] = $this->auth['usuario']->id;
                $modelAnunciante->update($data, "id = '".$idAnun."'");
                $messages['success'] = true;
                $messages['mensaje'] = 'Se ejecuto correctamente';
                
                //Refrescamos la cache de los Destacados en pagina principal
                Application_Model_Beneficio::refreshCache();
                $db->commit();
            } catch (Exception $e) {
                //echo $e->getMessage();
                $db->rollBack();
                $messages['success'] = false;
                $messages['mensaje'] = 'Error';
            }
            $this->_response->appendBody(Zend_Json::encode($messages));
        } else {
            $id = $this->_getParam('id', null);
            $msj = '';
            $anunciante=  $modelAnunciante->getStatusAnuncianteById($id);
            
            $nroasociados=0;$elimina=1;$msjEliminado=" ¿Estás seguro de eliminarlo?";
            if (!empty($anunciante['nroasociados'])) {
                $nroasociados=$anunciante['nroasociados'];
                $elimina=0;
                $msjEliminado=" Tiene que desasociar de los beneficios antes de eliminar.";
            }
            
            $msj.= 'El Anunciante <i><u>'.trim($anunciante['razon_social']).'</u></i> esta '.
                ($anunciante['activo']?'activo':'inactivo').
                ', tiene '.(!empty($anunciante['nroasociados'])?$anunciante['nroasociados']:'0').
                ' Beneficio(s) asociado(s).';
            
            $msj.= $msjEliminado;
            $this->view->puedeEliminar = $elimina;
            $this->view->id = $id;
            $this->view->msj = $msj;
        }
    }
    

    public function validarRucAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $nruc = $this->_getParam('ndoc');
        $idEmp = $this->_getParam('idEmp');

//        $moduloAnunciante = new Application_Model_Anunciante();
        $isValid ='';
        if ($idEmp!= null) { 
            $isValid = Application_Model_Anunciante::validacionRuc($nruc,null, $idEmp);
        } else {
            $isValid = Application_Model_Anunciante::validacionRuc($nruc,null, false);
        }
        
        $data = array(
            'status' => $isValid
        );
        $this->_response->appendBody(Zend_Json::encode($data));
    }

}
