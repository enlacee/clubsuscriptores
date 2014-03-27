<?php

class Gestor_EncuestasController extends App_Controller_Action_Gestor
{
    protected $_encuesta;
    
    public function init()
    {
        parent::init();
        /* Initialize action controller here */
        $this->_encuesta = new Application_Model_Encuesta();
        $p = new Plugin_CsrfProtect();
        $this->view->csrf = $p->session->key;
    }

    public function indexAction()
    {
        Zend_Layout::getMvcInstance()->active
            = App_Controller_Action_Gestor::MENU_NAME_ENCUESTAS;
        
        $this->view->headScript()->appendFile(
            $this->mediaUrl . '/js/gestor/encuesta.js'
        );
        
        $this->view->headTitle('Encuestas');
        
        $objFiltroB = new Application_Form_FiltroBeneficios();
        $this->view->form = $objFiltroB;
        
        $this->encuestasPaginator();
    }
    
    protected function encuestasPaginator($nropage = 1)
    {
        $queryEncuesta = $this->_encuesta->getEncuestas();
        
        $page = $nropage;
        $nropagesEncuesta = $this->getConfig()->gestor->encuestas->nropaginas;

        $zp = Zend_Paginator::factory($queryEncuesta);
        $paginator = $zp->setItemCountPerPage($nropagesEncuesta);
        //$paginator = $zp;
        $this->view->nrofields = $paginator->getAdapter()->count();
        
        $this->view->totalitems = $paginator->getItemCount($paginator->getItemsByPage($page));
        $paginator->setCurrentPageNumber($page);
        $this->view->encuestas = $paginator;
        
        $nroPorPage = $paginator->getItemCountPerPage();
        $nroPage = $paginator->getCurrentPageNumber();
        $nroReg = $paginator->getCurrentItemCount();
        
        $this->view->mostrando = "Mostrando ".
            ' '.(($nroPage-1)*$nroPorPage + 1).
            ' - '.((($nroPage-1)*$nroPorPage) + $nroReg).
            ' de '.$paginator->getTotalItemCount();
        $this->view->nroregistros = "Registros listados : ".$nroReg;
    }
    
    public function listaEncuestasAction()
    {
        $this->_helper->layout->disableLayout();
        $page = $this->_getParam('page', 1);
        $this->_encuesta->setNombre($this->_getParam('descripcion', ''));
        $this->_encuesta->setActivo($this->_getParam('vigencia', ''));
        $this->encuestasPaginator($page);
    }
    
    public function verEncuestaAction()
    {
        $this->_helper->layout->disableLayout();
        //$this->_helper->viewRenderer->setNoRender();
        //var_dump($this->_getAllParams()); //exit;
        
        $id = $this->getRequest()->getPost('id', '');
        $objencuesta = new Application_Model_Encuesta();
        $objencuesta->setId($id);
        $this->view->encuesta = $objencuesta->getEncuesta();

        $objrespuesta = new Application_Model_OpcionRespuesta();
        $objrespuesta->setEncuesta_id($id);
        $this->view->resultado_encuesta = $objrespuesta->getResultadoEncuesta();
        $this->view->total_votos = $objrespuesta->getTotalVotosEncuesta();
        
        $objOpEnc = new Application_Model_OpcionEncuesta();
        $objOpEnc->setEncuesta_id($id);
        $masElegidos = $objOpEnc->getOpcionesMasElegidas();
        $cadElegidos = 
            (count($masElegidos)>1?'Las opciones más elegidas son ':'La opción más elegida es ');
        foreach($masElegidos as $opcion):
            $cadElegidos.= '"'.$opcion['opcion'].'",';
        endforeach;
        $this->view->cadElegidos = (!empty($masElegidos)?substr($cadElegidos, 0, -1).'.':'');
    }
    
    public function operaEncuestaAction()
    {
        $this->_helper->layout->disableLayout();
        $publica = $this->_getParam('publica', '');
        $nrooptions = $this->getConfig()->gestor->encuestas->nrooptions;
        $this->view->nrooptions = $nrooptions;
        
        $formEncuesta = new Application_Form_Encuesta();
        //var_dump($this->_getAllParams()); exit;
        if ($this->_request->isPost()) {
            $formEncuesta->setNr_opciones($nrooptions);
            $formEncuesta->addElementsOpcion();
        
            $values = $this->getRequest()->getPost();
            
            $id = $this->getRequest()->getPost('id', '');
            $opciones = array(); $nroempty = 0; $msjOps = true;
            /*foreach ($values['opcion'] as $ind => $opcion):
                $opciones[$ind]['id'] = $values['idop'][$ind];
                $opciones[$ind]['encuesta_id'] = $id;
                $opciones[$ind]['opcion'] = $opcion;
                if (empty($opcion)) $nroempty++;
            endforeach;*/
            for ($i=0;$i<$nrooptions;$i++):
                $opciones[$i]['id'] = !empty($values['idop'.($i+1)])?$values['idop'.($i+1)]:'';
                $opciones[$i]['encuesta_id'] = $id;
                if (empty($values['opcion'.($i+1)])){ $nroempty++;}
                else { $opciones[$i]['opcion'] = $values['opcion'.($i+1)]; }
            endfor;
            if ($nroempty>=($nrooptions-1)) $msjOps = false;
            $this->view->opciones = $opciones;
            $this->view->msjOps = !$msjOps;
            $this->view->cadMsjOps = 'Ingrese como minimo 2 opciones';

            $this->view->codigo = $id;
            $this->view->edita = false;
            if ($values['accion']=='E') {
                $this->view->edita = true;
            } else {
                $formEncuesta->addElementNombre();
                $formEncuesta->addElementPublicar();
            }
            if(!empty($values['txtnombre'])) 
                $this->view->encuesta = array('nombre'=>$values['txtnombre']);
            
            $formEncuesta->setDefaults($values);
            if ($formEncuesta->isValid($values) && $msjOps) {
                $this->view->state = '1';
                $this->view->msj = '1';
                if (empty($publica)) {
                    $this->grabarEncuesta($this->_getAllParams());
                    $this->view->msj = '0';
                    //exit;
                } else {
                    $p = new Plugin_CsrfProtect();
                    $p->_initializeTokens();
                    $this->view->csrf = $p->session->key;
                }
            } else {
                $this->view->state = '0';
                $p = new Plugin_CsrfProtect();
                $p->_initializeTokens();
                $this->view->csrf = $p->session->key;
//                $formEncuesta->getElement('csrf')->setValue($p->session->key);
                //var_dump($this->_getAllParams());
            }
        } else {
            $id = $this->_getParam('id', '');
            $objOpEnc = new Application_Model_OpcionEncuesta();
            $objOpEnc->setEncuesta_id($id);
            $this->view->opciones = $dataOpEnc = $objOpEnc->getOpciones();
            //var_dump($objOpEnc->getOpciones()); exit;
            $this->view->codigo = $id;
            $this->view->edita = false;
            $this->view->onlyRead = '';
        
            $objencuesta = new Application_Model_Encuesta();
            $objencuesta->setId($id);
            $ds = $objencuesta->getEncuesta();
            //var_dump($ds); exit;
            $this->view->encuesta = $ds;
        
            $formEncuesta->setPregunta($ds['pregunta']);
            if (!empty($ds['nombre'])) {
                $countOpEnc=count($dataOpEnc);
                $this->view->nrooptions = $countOpEnc;
                $formEncuesta->setNr_opciones($countOpEnc);
                $formEncuesta->addElementsOpcion();
                
                $this->view->edita = true;
                if (!empty($ds['activo'])) {
                    $this->view->onlyRead = 'readonly';
                    $this->view->messageNoEdit = "Encuesta vigente, no se puede editar.";
                    $formEncuesta->setReadOnly();
                } elseif (!empty($ds['numero_respuestas'])) {
                    $this->view->onlyRead = 'readonly';
                    $this->view->messageNoEdit = "Encuesta tiene votos,<br/> no se puede editar.";
                    $formEncuesta->setReadOnly();
                }
            } else {
                $formEncuesta->setNr_opciones($nrooptions);
                $formEncuesta->addElementsOpcion();
                
                $formEncuesta->addElementNombre();
                $formEncuesta->addElementPublicar();
            }
        }
        $this->view->formEncuesta = $formEncuesta;
    }
    
    public function grabarEncuesta($values)
    {
        $case = !empty($values['accion'])?$values['accion']:'';
        $publica = !empty($values['publica'])?$values['publica']:'';
        
        $objEncuesta = new Application_Model_Encuesta();
        $messages = array('success'=>true, 'mensaje'=>'Se ejecuto correctamente');
        $db = $this->getAdapter();
        if (!empty($publica)) {
            $data['activo'] = 1;
        }
        
        $nrooptions = $this->getConfig()->gestor->encuestas->nrooptions;
        try {
            $db->beginTransaction();
            switch ($case) {
                case 'N':

                        $data['actualizado_por'] = $data['creado_por'] = $this->auth['usuario']->id;
                        $data['nombre'] = !empty($values['nombre'])?$values['nombre']:'';
                        $data['pregunta'] = !empty($values['pregunta'])?$values['pregunta']:'';
                        $data['activo'] = !empty($values['activo'])?$values['activo']:0;
                        $data['fecha_actualizacion']= $data['fecha_registro']= date('Y-m-d h:i:s');
                        $data['numero_respuestas'] = 0;
                        if (!empty($data['activo'])) {
                            $objEncuesta->update(array('activo'=>'0'), '');
                        }
                        //var_dump($data); exit;
                        $id = $objEncuesta->insert($data);
                        if ( !empty($id) ) {
                            $objOpEnc = new Application_Model_OpcionEncuesta();
                            $nroreg = 0;
                            for ($j=0; $j<$nrooptions; $j++) {
                                if ( !empty($values['opcion'.($j+1)]) ) {
                                    $objOpEnc->insert(
                                        array(
                                            'encuesta_id'=>$id, 'opcion'=>$values['opcion'.($j+1)]
                                        )
                                    );
                                    $nroreg++;
                                }
                            }
                            $objEncuesta->update(
                                array('numero_opciones'=>$nroreg), "id = '".$id."'"
                            );
                        }

                    break;
                case 'E':
                    $idEnc = !empty($values['id'])?$values['id']:'';
                    $data['pregunta'] = !empty($values['pregunta'])?$values['pregunta']:'';
                    if (!empty($idEnc)) {
                        $objOpEnc = new Application_Model_OpcionEncuesta();
                        $nroreg = 0;
                        $objOpEnc->setEncuesta_id($idEnc);
                        $nrooptions = count($objOpEnc->getOpciones());
            
                        for ($j=0; $j<$nrooptions; $j++) {
                            if (!empty($values['idop'.($j+1)])) {
                                $where = "id = '".$values['idop'.($j+1)]."'";
                                if (!empty($values['opcion'.($j+1)])) {
                                    $objOpEnc->update(
                                        array('opcion'=>$values['opcion'.($j+1)]), $where
                                    );
                                    $nroreg++;
                                } else {
                                    $objOpEnc->delete($where);
                                }
                            } else {
                                if ( !empty($values['opcion'.($j+1)]) ) {
                                    $objOpEnc->insert(
                                        array(
                                            'encuesta_id'=>$idEnc, 
                                            'opcion'=>$values['opcion'.($j+1)]
                                        )
                                    );
                                    $nroreg++;
                                }
                            }
                        }
                        $data['numero_opciones'] = $nroreg;
                        $data['actualizado_por'] = $this->auth['usuario']->id;
                        $data['fecha_actualizacion'] = date('Y-m-d h:i:s');
                        if (!empty($publica)) {
                            $objEncuesta->update(array('activo'=>'0'), '');
                        }
                        $objEncuesta->update($data, "id = '".$idEnc."'");
                    }
                    break;
                default:
                    break;
            }
            $db->commit();
        } catch (Exception $e) {
            $messages['success'] = false;
            $messages['mensaje'] = 'Error';
            echo $e->getMessage();
            $db->rollBack();
        }
        //$this->_response->appendBody();
        echo $messages['mensaje'];
        //$this->_response->appendBody(Zend_Json::encode($messages));
    }
    
    public function grabarAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        
        $this->grabarEncuesta($this->_getAllParams());
        
    }
    
    public function vistaPreviaAction()
    {
        $this->_helper->layout->disableLayout();
        
        $id = $this->getRequest()->getPost('id', '');
        $objencuesta = new Application_Model_Encuesta();
        $objencuesta->setId($id);
        $this->view->encuesta = $objencuesta->getEncuesta();
        
        $objOpEnc = new Application_Model_OpcionEncuesta();
        $objOpEnc->setEncuesta_id($id);
        $this->view->opciones = $objOpEnc->getOpciones();
    }
    
    public function darBajaEncuestaAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $messages = array();
        $idEncuesta = $this->_getParam('idEnc', '');
        if (!empty($idEncuesta)) {
            
            $objEncu = new Application_Model_Encuesta();
            $data['elog'] = '1';
            $data['fecha_de_baja'] = date('Y-m-d h:i:s');
            $data['de_baja_por'] = $this->auth['usuario']->id;
            $objEncu->update($data, "id = '".$idEncuesta."'");
            $messages['success'] = true;
            $messages['mensaje'] = 'Se ejecuto correctamente';
        } else {
            $messages['success'] = false;
            $messages['mensaje'] = 'Error';
        }
        $this->_response->appendBody(Zend_Json::encode($messages));
    }
}

