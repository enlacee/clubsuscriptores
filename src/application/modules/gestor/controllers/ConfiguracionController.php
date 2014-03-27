<?php

class Gestor_ConfiguracionController extends App_Controller_Action_Gestor
{

    public function init()
    {
        parent::init();
        /* Initialize action controller here */
        $this->_parametro = new Application_Model_Parametro();
    }

    public function indexAction()
    {
        Zend_Layout::getMvcInstance()->active
            = App_Controller_Action_Gestor::MENU_NAME_CONFIGURACION;

        $this->view->headScript()->appendFile(
            $this->mediaUrl . '/js/gestor/parametro.js'
        );
        
        $this->view->headTitle('Configuración');
        
        $objFiltConf = new Application_Form_FiltroConfiguracion();
        $this->view->form = $objFiltConf;
        
        $this->paramPaginator();
    }
    
    public function listaParametrosAction()
    {
        $this->_helper->layout->disableLayout();
        $page = $this->_getParam('page', 1);
        $this->_parametro->setTipo($this->_getParam('tipo_parametro', ''));
        $this->paramPaginator($page);
    }
    
    protected function paramPaginator($nropage = 1)
    {
        $queryParametro = $this->_parametro->getParametros();
        
        $page = $nropage;
        $nropagesParam = $this->getConfig()->gestor->parametro->nropaginas;

        $zp = Zend_Paginator::factory($queryParametro);
        $paginator = $zp->setItemCountPerPage($nropagesParam);
        //$paginator = $zp;
        $this->view->nrofields = $paginator->getAdapter()->count();
        
        $this->view->totalitems = $paginator->getItemCount($paginator->getItemsByPage($page));
        $paginator->setCurrentPageNumber($page);
        $this->view->parametros = $paginator;
        
        $nroPorPage = $paginator->getItemCountPerPage();
        $nroPage = $paginator->getCurrentPageNumber();
        $nroReg = $paginator->getCurrentItemCount();
        
        $this->view->mostrando = "Mostrando ".
            ' '.(($nroPage-1)*$nroPorPage + 1).
            ' - '.((($nroPage-1)*$nroPorPage) + $nroReg).
            ' de '.$paginator->getTotalItemCount();
        $this->view->nroregistros = "Registros listados : ".$nroReg;
    }
    
    public function editarParametroAction()
    {
        $this->_redirect('/gestor/configuracion/index');
        $this->_helper->layout->disableLayout();
        //var_dump($this->_getAllParams()); exit;
        $idParam = $this->_getParam('id', '');
        $this->_parametro->setId($idParam);
        $sqlParam = $this->_parametro->getParametros();
        $rsParam = $this->getAdapter()->fetchRow($sqlParam);
        //echo $sqlParam; exit;
        $this->view->param = $rsParam;
        $this->view->edit = $rsParam['es_editable'];
        $this->view->codigo = $idParam;
    }
    
    public function operaAction()
    {
        exit;
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $case = $this->_getParam('case', '');
        //var_dump($this->_getAllParams()); exit;
        $db = $this->getAdapter();
        switch ($case) {
            case 'edit':
                $message = 'Se ejecuto correctamente';
                try {
                    $db->beginTransaction();
                    
                    $objParam = new Application_Model_Parametro();
                    $valor = $this->getRequest()->getPost('valor', '');
                    $id = $this->getRequest()->getPost('idParam', '');
                    $update = $this->getRequest()->getPost('chkupdatep', '');
                    $update = (!empty($update)?'0':'1');
                    $objParam->update(
                        array(
                            'valor'=>$valor,
                            'actualizar'=>$update,
                            'fecha_actualizacion'=>date('Y-m-d h:i:s'),
                            'actualizado_por'=>$this->auth['usuario']->id
                        ),
                        "id='".$id."'"
                    );
                    if (empty($update)) {
                        $this->_parametro->setId($id);
                        $sqlParam = $this->_parametro->getParametros();
                        $rsParam = $this->getAdapter()->fetchRow($sqlParam);
                        $con = new Zend_Config_Writer_Ini();
                        $valorx = explode('.', $rsParam['variable']);
                        $execute = '';
                        foreach ($valorx as $indice):
                            $execute.="['".$indice."']";
                        endforeach;
                        $pathFile = APPLICATION_PATH . '/configs/'.$rsParam['archivo'];
                        $x = new Zend_Config_Ini($pathFile);
                        $arreglo = $x->toArray();
                        $valors = str_replace('"', '', $valor);
                        eval('$arreglo'.$execute.' = "'.$valors.'";');
                        $con->write($pathFile, new Zend_Config($arreglo));
                    }
                    $db->commit();
                } catch (Exception $e) {
                    $message = 'Error';
                    echo $e->getTraceAsString();
                    echo $e->getMessage();
                    $db->rollBack();
                }
                $this->_response->appendBody($message);
                break;
            default:
                break;
        }
    }
}