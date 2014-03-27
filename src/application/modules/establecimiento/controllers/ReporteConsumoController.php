<?php

class Establecimiento_ReporteConsumoController extends App_Controller_Action_Establecimiento
{
    protected $_cupon;
    protected $_establecimiento_id;
    protected $_establecimiento_nombre;
    
    public function init()
    {
        /* Initialize action controller here */
        parent::init();
        $this->_cupon = new Application_Model_Cupon();
        $this->_establecimiento_id = isset($this->auth['establecimiento']['id'])?$this->auth['establecimiento']['id']:'';
        $this->_establecimiento_nombre = 
                isset($this->auth['establecimiento']['nombre'])?$this->auth['establecimiento']['nombre']:'';
    }

    public function indexAction()
    {
        Zend_Layout::getMvcInstance()->active =
            App_Controller_Action_Establecimiento::MENU_NAME_REPORTE_CONSUMO;
        $this->view->headTitle('Establecimiento');

        $this->view->headScript()->appendFile($this->mediaUrl . '/js/establecimiento/reporte_consumo.js');
        $this->view->headLink()->appendStylesheet(
            $this->mediaUrl . '/js/datepicker/themes/ui-lightness/ui.all.css', 'all'
        );
        $this->view->headScript()->appendFile($this->mediaUrl . '/js/datepicker/ui/ui.core.js');
        $this->view->headScript()->appendFile($this->mediaUrl . '/js/datepicker/ui/ui.datepicker.js');
        $this->view->headScript()->appendFile($this->mediaUrl . '/js/datepicker/ui/i18n/ui.datepicker-es.js');
        /*$this->view->headScript()->appendScript(
            "$(document).ready(function(){ $('select[name=estado]').val('redimido'); });"
        );*/
        $this->view->name_establecimiento = $this->_establecimiento_nombre;
        
        $this->view->filtroEstablecimiento = $this->_establecimiento_id;
        
        $formFiltro = new Application_Form_FiltroConsumos();
        $this->view->form = $formFiltro;

        $this->consumosPaginator();
    }
    
    protected function consumosPaginator($nropage = 1)
    {
        $perfil_id=$this->auth['usuario']->perfil_id;
        $id=$this->auth['usuario']->id;
        $redimido_por='';
        $opc_eliminar='';
        if ($perfil_id==4) {
            $redimido_por=$id;
        } elseif ($perfil_id==5) {
            $opc_eliminar=$id;
        }
        
        if (!empty($this->_establecimiento_id)):
            $this->_cupon->setEstablecimiento_id($this->_establecimiento_id);
        endif;
        
        $queryconsumos = $this->_cupon->getConsumosPorEstablecimiento($redimido_por);
        
        $this->view->redimido_por = $redimido_por;
        $this->view->opc_eliminar = $opc_eliminar;
        
        $page = $nropage;
        $nropagesconsumos = $this->getConfig()->establecimiento->consumos->nropaginas;

        $zp = Zend_Paginator::factory($queryconsumos);
        $paginator = $zp->setItemCountPerPage($nropagesconsumos);
        $this->view->nrofields = $paginator->getAdapter()->count();
        
        $this->view->totalitems = $paginator->getItemCount($paginator->getItemsByPage($page));
        $paginator->setCurrentPageNumber($page);
        $this->view->consumos = $paginator;
        
        $nroPorPage = $paginator->getItemCountPerPage();
        $nroPage = $paginator->getCurrentPageNumber();
        $nroReg = $paginator->getCurrentItemCount();
        $this->view->mostrando = "Mostrando ".
            ' '.(($nroPage-1)*$nroPorPage + 1).
            ' - '.((($nroPage-1)*$nroPorPage) + $nroReg).
            ' de '.$paginator->getTotalItemCount();
        $this->view->nroregistros = "Registros listados : ".$nroReg;
    }
    
    public function filtroListConsumosAction()
    {
        $this->_helper->layout->disableLayout();
        //var_dump($this->_getAllParams()); exit;
        $page = $this->_getParam('page', 1);
        $this->_cupon->setFecha_emision_from($this->_getParam('fecha_emision_desde', ''));
        $this->_cupon->setFecha_emision_at($this->_getParam('fecha_emision_hasta', ''));
        $this->_cupon->setContent_filtro(
            $this->_getParam('nombre_suscriptor', '%').
            $this->_getParam('tipo_documento', '%').' '.
            $this->_getParam('nro_documento', '')
        );
        $estado=$this->_getParam('estado', '');
        $this->_cupon->setEstado($estado);
        if (empty( $this->_establecimiento_id )) :
            $this->_cupon->setEstablecimiento_id($this->_getParam('establecimiento', ''));
        endif;
        
        $this->view->estado = $estado;
        $this->_cupon->setName_promo($this->_getParam('nombre_promo', ''));
        $this->consumosPaginator($page);
    }
    
    public function imprimirReporteAction() 
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        
        $this->_cupon->setFecha_emision_from($this->_getParam('fecha_emision_desde', ''));
        $this->_cupon->setFecha_emision_at($this->_getParam('fecha_emision_hasta', ''));
        $this->_cupon->setContent_filtro(
            $this->_getParam('nombre_suscriptor', '%').
            $this->_getParam('tipo_documento', '%').' '.
            $this->_getParam('nro_documento', '')
        );
        $estado=$this->_getParam('estado', '');
        $this->_cupon->setEstado($estado);
        $this->view->estado = $estado;
        $this->_cupon->setName_promo($this->_getParam('nombre_promo', ''));
        
        $id=$this->auth['usuario']->id;
        $perfil_id=$this->auth['usuario']->perfil_id;
        $redimido_por=''; 
        if ($perfil_id==4) {
            $redimido_por=$id;
        }
        
        $this->view->redimido_por = $redimido_por;
        
        if (!empty($this->_establecimiento_id)):
            $this->_cupon->setEstablecimiento_id($this->_establecimiento_id);
        else:
            $this->_cupon->setEstablecimiento_id($this->_getParam('establecimiento', ''));
        endif;
        
        $queryconsumos = $this->_cupon->getConsumosPorEstablecimiento($redimido_por);
        
        $rs = $this->getAdapter()->fetchAll($queryconsumos);
        $this->view->consumos = $rs;
        $this->view->name_establecimiento = isset($this->auth['establecimiento']['nombre'])?
            ($this->auth['establecimiento']['tipestnombre'].' '.$this->auth['establecimiento']['nombre']):'';
        $this->view->fechadesde = $this->_getParam('fecha_emision_desde', '');
        $this->view->fechahasta = $this->_getParam('fecha_emision_hasta', '');
        $this->view->estado = $this->_getParam('estado', '');
        $this->view->totalreg = count($rs);

        $html = $this->view->render('reporte-consumo/imprimir-reporte-ok.phtml');
        
        $bin = $this->getConfig()->wkhtml->bin->path;
        if(count(explode("WIN", PHP_OS))>1) 
                $bin = APPLICATION_PATH."/../../bin/wkhtmltopdf/win/wkhtmltopdf.exe";
        
        try {
            $wkhtmltopdf = new Wkhtmltopdf(
                array(
                    'path' => ELEMENTS_ROOT . '/pdfs_tmp/',
                    'binpath' => $bin
                )
            );
            $wkhtmltopdf->setTitle("Reporte de Consumos");
            $wkhtmltopdf->setMargins(
                array('top' => 12, 'bottom' => 8, 'left' => 7, 'right' => 7)
            );
            $wkhtmltopdf->addDate();
            $wkhtmltopdf->addPage();
            $wkhtmltopdf->setHtml($html);
            $wkhtmltopdf->output(Wkhtmltopdf::MODE_EMBEDDED, "src/public/reporte_consumos.pdf");
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function imprimirReporteOkAction() 
    {
    }
}
