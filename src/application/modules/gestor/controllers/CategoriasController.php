<?php

class Gestor_CategoriasController extends App_Controller_Action_Gestor
{

    public function init()
    {
        parent::init();
        /* Initialize action controller here */
        Zend_Layout::getMvcInstance()->active
            = App_Controller_Action_Gestor::MENU_NAME_CATEGORIAS;
        $p = new Plugin_CsrfProtect();
        $this->view->csrf = $p->session->key;
    }

    public function indexAction()
    {
        $this->_forward('listar-categorias');
    }
    
    public function listarCategoriasAction()
    {
        $this->view->headScript()->appendFile(
            $this->config->app->mediaUrl . '/js/gestor/filtro.categorias.js'
        );
        
        $this->view->headScript()->appendFile(
            $this->config->app->mediaUrl . '/js/gestor/categoria.js'
        );
        
        $formFiltroCategoria = new Application_Form_FiltroCategorias();
        $this->view->formFiltroCategoria = $formFiltroCategoria;
           
    }
    
    public function buscarCategoriasAction()
    {
        $this->_helper->layout->disableLayout();
        $params = $this->_getAllParams();
        $criteria = array();
        $this->view->col = $criteria['col'] = $this->_getParam('col', '');
        $this->view->ord = $criteria['ord'] = $this->_getParam('ord', 'DESC');
        $this->view->pag= $page = $this->_getParam('page', 1);
        if ($params['nombre'] <> '')
            $criteria['nombre'] = $params['nombre'];
        if ($params['estado'] <> '')
            $criteria['estado'] = $params['estado'];

        $categoria = new Application_Model_Categoria();
        $categoria = $categoria->getCategoriaPaginator($criteria);
        $categoria->setCurrentPageNumber($page);
        $this->view->categorias = $categoria;
        
        $nroPorPage = $categoria->getItemCountPerPage();
        $nroPage = $categoria->getCurrentPageNumber();
        $nroReg = $categoria->getCurrentItemCount();
        
        $this->view->mostrando = "Mostrando ".
            ' '.(($nroPage-1)*$nroPorPage + 1).
            ' - '.((($nroPage-1)*$nroPorPage) + $nroReg).
            ' de '.$categoria->getTotalItemCount();
        $this->view->nroregistros = "Registros listados : ".$nroReg;
    }

    public function nuevoAction()
    {
        $this->_helper->layout->disableLayout();
        $data = 0;
        
        $params = $this->_getAllParams();
        $formCategoria = new Application_Form_Categoria();
        $modelCategoria = new Application_Model_Categoria();
        $filterSlug = new App_Filter_Slug();
        
        if (isset($params['idCat']) && $params['idCat']!='') {
            $idCat = $params['idCat'];
            $this->view->idCat = $idCat;
            $arrayCategoria = $modelCategoria->getAdapter()
                ->fetchRow($modelCategoria->getCategoriaXiD($idCat));
            $formCategoria->setDefaults($arrayCategoria);
        }
        
        if ($this->_request->isPost()) {
             $paramsValue = $this->_getAllParams();
             $validForm = $formCategoria->isValid($paramsValue);
            if ($validForm) {
                $valuesCategoria = $formCategoria->getValues();
                if (isset($idCat)) {
                    $where = $modelCategoria->getAdapter()->quoteInto('id = ?', $idCat);
                    $valuesCategoria['fecha_actualizacion']= date('Y-m-d H:i:s');
                    $valuesCategoria['actualizado_por']= $this->auth['usuario']->id ;
                    $modelCategoria->update($valuesCategoria, $where);
                    $data = '1';
                } else {
                    $valuesCategoria['fecha_actualizacion'] = date('Y-m-d H:i:s');
                    $valuesCategoria['fecha_registro'] = date('Y-m-d H:i:s');
                    $valuesCategoria['actualizado_por'] = $this->auth['usuario']->id ;
                    $valuesCategoria['creado_por'] = $this->auth['usuario']->id;
                    $valuesCategoria['slug'] = $filterSlug->filter($valuesCategoria['nombre']);
                    $modelCategoria->insert($valuesCategoria);
                    $data = '2';
                }
            } else {
                if ($data == 0) {
                    $data = '-1';
                }
            }
        }
        
        $this->view->data = $data;
        $this->view->formCategoria = $formCategoria;
    }
    
    function cambioEstadoAction()
    {
        $this->_helper->layout->disableLayout();
        $params = $this->_getAllParams();
        $this->view->estado = $estadoIni = $params['estado'];
        $idsCate = $params['idsCat'];
        
        $modelCategoria  = new Application_Model_Categoria();
        $arrayCategorias = $modelCategoria->getAdapter()
            ->fetchAll($modelCategoria->getCategoriaXiD($idsCate));
        if ($this->_request->isPost()) {
            if ($estadoIni == 0) {
                $estadoN = 1;
            } else {
                $estadoN = 0;
            }
            foreach ($idsCate as $item) :
            
                $modelCategoria->update(
                    array('activo'=>$estadoN), 
                    $modelCategoria->getAdapter()->quoteInto('id = ?', $item)
                );
            
            endforeach;
        }
        $this->view->arrayCategoria = $arrayCategorias;
        
    }
    
    function validarNombreAction()
    {
        $data = 0;
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $allParams = $this->_getAllParams();
        $valNom = $allParams['valNom'];
        $idCat = $allParams['idCat'];
        
        if ($this->_request->isPost()) {
            $valBuscar = str_replace(' ', '', strtoupper($valNom));
            $modelCategoria = new Application_Model_Categoria();
            $arrayCategoria = $modelCategoria->validarNombre($valBuscar, $idCat);
            
            if ($arrayCategoria != false) {
                $data = 1; //existe
            } else {
                $data = 0; //no existe
            }
        }
        $this->_response->appendBody(Zend_Json::encode($data));
    }
    
    public function eliminarCategoriaAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer-> setNoRender();
        
        $allparams = $this->_getAllParams();
        $idCat = $allparams['idCat'];
        $data = 0;
        
        if ($this->_request->isPost()) {
            if ($idCat) {
                try {
                    $db = $this->getAdapter();
                    $db->beginTransaction();
                    $modelCategoria = new Application_Model_Categoria();
                    $where = $modelCategoria->getAdapter()->quoteInto('id = ?', $idCat);
                    $val = $modelCategoria->delete($where);
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
}

