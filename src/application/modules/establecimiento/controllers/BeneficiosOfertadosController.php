<?php

class Establecimiento_BeneficiosOfertadosController extends App_Controller_Action_Establecimiento
{
    protected $_beneficio;
    protected $_establecimiendo_id;
    
    public function init()
    {
        parent::init();
        $this->_beneficio = new Application_Model_Beneficio();
        $this->_establecimiendo_id = isset($this->auth['establecimiento']['id'])?$this->auth['establecimiento']['id']:'';
        $this->_beneficio->setEstablecimiento_id(!empty($this->_establecimiendo_id)?$this->_establecimiendo_id:'');
        $this->_beneficio->setActivo(1);
    }
    
    public function indexAction()
    {
        Zend_Layout::getMvcInstance()->active = 
            App_Controller_Action_Establecimiento::MENU_NAME_BENEFICIOS_OFERTADOS;
        $this->view->headTitle('Establecimiento');
        //$this->view->name_establecimiento = $this->auth['establecimiento']['nombre'];        
        $objFiltroB = new Application_Form_FiltroBeneficios();
        $objFiltroB->addOptionsEstadoBenefiosEstablecimiento();
        $this->view->form = $objFiltroB;
        $this->view->filtroEstablecimiento = $this->_establecimiendo_id;
        
        $this->beneficiosPaginator();
    }
    
    protected function beneficiosPaginator($nropage = 1,$order = '')
    {
        $querybenef = $this->_beneficio->getBeneficiosPorEstablecimiento(
            $order
        );
        
        $page = $nropage;
        $nropagesbenef = $this->getConfig()->establecimiento->beneficios->nropaginas;

        $zp = Zend_Paginator::factory($querybenef);
        $paginator = $zp->setItemCountPerPage($nropagesbenef);
        //$paginator = $zp;
        $this->view->nrofields = $paginator->getAdapter()->count();
        
        $this->view->totalitems = $paginator->getItemCount($paginator->getItemsByPage($page));
        $paginator->setCurrentPageNumber($page);
        $this->view->beneficios = $paginator;
        $this->view->order = $order;
        
        /*$this->view->mostrando = "Mostrando "
            . $paginator->getItemCount($paginator->getItemsByPage($page)) . " de "
            . $paginator->getTotalItemCount();*/
        $nroPorPage = $paginator->getItemCountPerPage();
        $nroPage = $paginator->getCurrentPageNumber();
        $nroReg = $paginator->getCurrentItemCount();
        
        $this->view->mostrando = "Mostrando ".
            ' '.(($nroPage-1)*$nroPorPage + 1).
            ' - '.((($nroPage-1)*$nroPorPage) + $nroReg).
            ' de '.$paginator->getTotalItemCount();
        $this->view->nroregistros = "Registros listados : ".$nroReg;
    }
    
    public function filtroListBeneficiosAction()
    {
        $this->_helper->layout->disableLayout();
        $page = $this->_getParam('page', 1);
        $order = $this->_getParam('order', '');
        $this->_beneficio->setNombre($this->_getParam('descripcion', ''));
        $this->_beneficio->setActivo($this->_getParam('estado', ''));
        if (empty($this->_establecimiendo_id)):
            $this->_beneficio->setEstablecimiento_id($this->_getParam('establecimiento', ''));
        endif;
        $this->beneficiosPaginator($page, $order);
    }
}

