<?php

class Admin_PerfilesController extends App_Controller_Action_Admin
{
    protected $_perfil;
    protected $_optionPerf;
    protected $_option;
    protected $_formPerfil;
    
    public function init()
    {
        parent::init();
        /* Initialize action controller here */
        $this->_perfil = new Application_Model_Perfil();
        $this->_optionPerf = new Application_Model_OpcionPerfil();
        $this->_option = new Application_Model_Opcion();
        $p = new Plugin_CsrfProtect();
        $this->view->csrf = $p->session->key;
    }

    public function indexAction()
    {
        Zend_Layout::getMvcInstance()->active = App_Controller_Action_Admin::MENU_NAME_PERFILES;
        
        $this->view->headScript()->appendFile(
            $this->mediaUrl . '/js/admin/perfiles.js'
        );
        
        $this->view->headTitle('Perfiles');
        
        $objFiltroP = new Application_Form_FiltroPerfiles();
        $this->view->form = $objFiltroP;
        
        $this->perfilesPaginator();
    }
    
    protected function perfilesPaginator()
    {
        $rsPerfiles = $this->_perfil->getPerfiles();
        $nroReg = count($rsPerfiles);
        //var_dump($rsPerfiles); exit;
        $this->view->totalitems = $nroReg;
        $this->view->perfiles = $rsPerfiles;
        
        $this->view->mostrando = "";
        $this->view->nroregistros = "Registros listados : ".$nroReg;
    }
    
    public function listaPerfilesAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_perfil->setNombre($this->_getParam('perfil', ''));
        $this->perfilesPaginator();
    }
    
    public function operaPerfilAction()
    {
        $this->_helper->layout->disableLayout();

        $id = $this->_getParam('id', '');
        //var_dump($this->_getAllParams()); exit;
        $this->_formPerfil = new Application_Form_Perfil();
        if ($this->_request->isPost()) {
            $values = $this->getRequest()->getPost();
            $moduleid = $values['modulo'];
            
            $rsOp = array();
            if (!empty($values['idperfil'])) {
                $this->_optionPerf->setPerfil_id($values['idperfil']);
                $rsOp = $this->_optionPerf->getOpcionesByPerfil(0);
            } else {
                $this->_option->setModulo_id($moduleid);
                $rsOp = $this->_option->getOpcionesByModulo();
            }
            $objmod = new Application_Model_Modulo();
            $this->view->nombreModulo = $objmod->getNombreModulo($moduleid);
            $nroOpt = 0;
            //var_dump($values,$rsOp); exit;
            foreach ($rsOp as $key => $value) {
                if (array_key_exists('chkActivo'.$value['id'], $values)) {
                    $value['activo'] = $values['chkActivo'.$value['id']];
                    if (!empty($value['activo'])) {
                        $nroOpt++;
                    }
                }
            }
            $this->view->msjOpt = '';
            if ($nroOpt<=0) {
                $this->view->msjOpt = 'Ingrese al menos una Opción';
            }
            $this->_formPerfil->addOptionsPerfil($rsOp);
            $this->view->options = $rsOp;
            $this->_formPerfil->setDefaults($values);
            $this->view->titulo = ($values['opera']=='N'?'Nuevo':'Editar');
            $this->view->opera = $values['opera'];
            $this->view->idperfil = $values['idperfil'];
            if ($values['opera']=='E') {
                $this->_formPerfil->setDisabledModulo($moduleid);
            }
            
            //validacion de nombre de perfil
            $this->_perfil->setId($values['idperfil']);
            $validName = $this->_perfil->validNameProfileWithId($values['nombre']);
            $this->view->alertName = '';
            if (!$validName) {
                $this->view->alertName = 'Nombre Registrado';
            }
            
            if ($this->_formPerfil->isValid($values) && $nroOpt>0 && $validName) {
                $this->view->result = 'éxito';
                $this->view->state = 1;
                $this->savePerfil($values);
                //exit;
            } else {
                $this->view->state = 0;
                $this->view->result = 'No Esta Bien';
                
                $p = new Plugin_CsrfProtect();
                $p->_initializeTokens();
                $this->view->csrf = $p->session->key;
                //exit;
            }
        } else {
            $this->view->nombreModulo = '';
            $this->view->titulo = 'Nuevo';
            $this->view->opera = 'N';
            $this->view->idperfil = '';
            $rsOp = array();
            if (!empty($id)) {
                $this->view->titulo = 'Editar';
                $this->view->opera = 'E';
                $this->view->idperfil = $id;
                $this->_perfil->setId($id);
                $rs = $this->_perfil->getPerfilById();
                $this->_optionPerf->setPerfil_id($id);
                $rsOp = $this->_optionPerf->getOpcionesByPerfil(0);
                //var_dump($rs,$rsOp); exit;
                $this->_formPerfil->setNombre($rs['nombre']);
                $this->_formPerfil->setDescripcion($rs['descripcion']);
                //var_dump($rsOp[0]); exit;
                $this->_formPerfil->setDisabledModulo($rsOp[0]['modulo_id']);
                $this->_formPerfil->addOptionsPerfil($rsOp);
                $this->view->nombreModulo = $rsOp[0]['nombremod'];
            }
            $this->view->options = $rsOp;
        }
        $this->view->form = $this->_formPerfil;
    }
    public function opcionesModuloAction()
    {
        $this->_formPerfil = new Application_Form_Perfil();
        $this->_helper->layout->disableLayout();
        $module = $this->_getParam('idmodulo', '');
        //var_dump($module); exit;
        $this->_option->setModulo_id($module);
        $rsOpts = $this->_option->getOpcionesByModulo();
        $this->_formPerfil->addOptionsPerfil($rsOpts);
        
        $this->view->options = $rsOpts;
        $this->view->form = $this->_formPerfil;
    }
    
    public function savePerfil(Array $post = array())
    {
        //var_dump($post); exit;
        $opera = $post['opera'];
        $db = $this->getAdapter();
        try {
            $objperf = new Application_Model_Perfil();
            $objOperf = new Application_Model_OpcionPerfil();
            
            $db->beginTransaction();
            switch ($opera) {
                case 'N':
                    $data = array();
                    $data['nombre'] = $post['nombre'];
                    $data['descripcion'] = $post['descripcion'];
                    $data['creado_por'] = $this->auth['usuario']->id;
                    //$data['actualizado_por'] = NULL;
                    $data['fecha_registro'] = date('Y-m-d h:i:s');
                    //$data['fecha_actualizacion'] = NULL;
                    $data['modulo_id'] = $post['modulo'];
                    $idperf = $objperf->insert($data);
                    $dataOP['perfil_id'] = $idperf;
                    foreach ($post as $key => $value) {
                        $extrae = substr($key, 0, -1);
                        $nro = substr($key, -1);
                        //if ($extrae=='chkActivo') {
                        $position = strrpos($key, 'chkActivo');
                        if ($position===false) {
                        } else {
                            $nro = substr($key, strlen('chkActivo'));
                            //var_dump($nro); //exit;
                            $dataOP['opcion_id'] = $post['idoption'.$nro];
                            $dataOP['activo'] = $post['chkActivo'.$nro];
                            $objOperf->insert($dataOP);
                        }
                    }
                    break;
                case 'E':
                    $data = array();
                    $idperf = $post['idperfil'];
                    $data['nombre'] = $post['nombre'];
                    $data['descripcion'] = $post['descripcion'];
                    $data['actualizado_por'] = $this->auth['usuario']->id;
                    $data['fecha_actualizacion'] = date('Y-m-d h:i:s');
                    $objperf->update($data, "id = '".$idperf."'");
                    
                    foreach ($post as $key => $value) {
                        $extrae = substr($key, 0, -1);
                        $nro = substr($key, -1);
                        //if ($extrae=='chkActivo') {
                        $position = strrpos($key, 'chkActivo');
                        if ($position===false) {
                        } else {
                            $nro = substr($key, strlen('chkActivo'));
                            $dataOP['opcion_id'] = $post['idoption'.$nro];
                            $dataOP['activo'] = $post['chkActivo'.$nro];
                            $objOperf->update(
                                $dataOP,
                                "perfil_id = '".$idperf."' and opcion_id='".
                                $post['idoption'.$nro]."'"
                            );
                        }
                    }
                    break;
                default:
                    break;
            }
        $db->commit();
        echo 'Datos Grabados exitosamente';
        } catch (Exception $e) {
            $db->rollBack();
            echo $e->getMessage();
        }
    }
    
    public function deleteAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $id = $this->getRequest()->getPost('id');
        //var_dump($this->_getAllParams()); exit;
        $db = $this->getAdapter();
        try {
            $db->beginTransaction();
            $objPerfil = new Application_Model_Perfil();
            $objOpciPer = new Application_Model_OpcionPerfil();
            
            $objOpciPer->delete("perfil_id = '".$id."'");
            $objPerfil->delete("id = '".$id."'");
            $db->commit();
            echo 'Se eliminó correctamente';
        } catch (Exception $e) {
            $db->rollBack();
            echo 'Error :'.$e->getMessage();
        }
    }
}

