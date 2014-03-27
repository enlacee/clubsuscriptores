<?php

class Establecimiento_CuponController extends App_Controller_Action_Establecimiento
{
    protected $_beneficioVersion;
    
    public function init()
    {
        /* Initialize action controller here */
        parent::init();
        $this->_beneficioVersion = new Application_Model_BeneficioVersion();
        $this->_cupon = new Application_Model_Cupon();
        $this->_fecha = new Zend_Date();

        $p = new Plugin_CsrfProtect();
        $this->view->csrf = $p->session->key;
    }

    public function indexAction()
    {
        Zend_Layout::getMvcInstance()->active = 
            App_Controller_Action_Establecimiento::MENU_NAME_ELIMINADOS;
        $this->view->headTitle('Establecimiento');

        $this->view->headScript()->appendFile($this->mediaUrl . '/js/establecimiento/cupones_eliminados.js');
        $this->view->headLink()->appendStylesheet(
            $this->mediaUrl . '/js/datepicker/themes/ui-lightness/ui.all.css', 'all'
        );
        $this->view->headScript()->appendFile($this->mediaUrl . '/js/datepicker/ui/ui.core.js');
        $this->view->headScript()->appendFile($this->mediaUrl . '/js/datepicker/ui/ui.datepicker.js');
        $this->view->headScript()->appendFile($this->mediaUrl . '/js/datepicker/ui/i18n/ui.datepicker-es.js');
        /*$this->view->headScript()->appendScript(
            "$(document).ready(function(){ $('select[name=estado]').val('redimido'); });"
        );*/
        $this->view->name_establecimiento = $this->auth['establecimiento']['nombre'];

        $formFiltro = new Application_Form_FiltroConsumos();
        $this->view->form = $formFiltro;

        $this->consumosPaginator();
        
    }
    
    protected function consumosPaginator($nropage = 1)
    {
        $perfil_id=$this->auth['usuario']->perfil_id;
        $id=$this->auth['usuario']->id;
        $redimido_por=''; 
        if ($perfil_id==4) {
            $redimido_por=$id;
        }
        
        $idEst = $this->auth['establecimiento']['id'];
        if (!empty($idEst)):
            $this->_cupon->setEstablecimiento_id($idEst);
        endif;
        
        $queryconsumos = $this->_cupon->getConsumosPorEstablecimiento($redimido_por);
        
        $this->view->redimido_por = $redimido_por;
        
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
        $this->_cupon->setFecha_eliminado_from($this->_getParam('fecha_emision_desde', ''));
        $this->_cupon->setFecha_eliminado_at($this->_getParam('fecha_emision_hasta', ''));
        $this->_cupon->setContent_filtro(
            $this->_getParam('nombre_suscriptor', '%').
            $this->_getParam('tipo_documento', '%').' '.
            $this->_getParam('nro_documento', '')
        );
        $this->_cupon->setEstado('eliminado');
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
        $this->_cupon->setEstado('eliminado');
        $this->_cupon->setName_promo($this->_getParam('nombre_promo', ''));
        
        $idEst = $this->auth['establecimiento']['id'];
        if (!empty($idEst)):
            $this->_cupon->setEstablecimiento_id($idEst);
        endif;
        
        $queryconsumos = $this->_cupon->getConsumosPorEstablecimiento();
        
        $rs = $this->getAdapter()->fetchAll($queryconsumos);
        $this->view->consumos = $rs;
        $this->view->name_establecimiento = 
            $this->auth['establecimiento']['tipestnombre'].
            ' '.$this->auth['establecimiento']['nombre'];
        $this->view->fechadesde = $this->_getParam('fecha_emision_desde', '');
        $this->view->fechahasta = $this->_getParam('fecha_emision_hasta', '');
        $this->view->estado = $this->_getParam('estado', '');
        $this->view->totalreg = count($rs);

        $html = $this->view->render('cupon/imprimir-reporte-ok.phtml');
        
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
    
    public function eliminarAction()
    {
        $p = new Plugin_CsrfProtect();
        $this->view->csrf = $p->session->key;
        $this->_helper->layout->disableLayout();
//        $this->_helper->viewRenderer->setNoRender();
        $idCup = $this->getRequest()->getParam('idCup', '');
        $csrf= $this->getRequest()->getParam('csrf');
        empty($idCup)?exit:'';
        $data=0;$msj='';
        $objCupon = new Application_Model_Cupon();
        $objBeneficioVersion = new Application_Model_BeneficioVersion();        
        $objCupon->setId($idCup);
        $result=$objCupon->getInfoById();
        $this->view->cliente = $result['nombre'];
        $this->view->beneficio = $result['titulo'];
        $this->view->fecha = $result['fecha_redencion'];
        
        if ($this->_request->isPost()) {
            $db = $this->getAdapter();
            $db->beginTransaction();
            
            $messages = array();
            $db = $this->getAdapter();
            $db->beginTransaction();
            
            $where = $objCupon->getAdapter()->quoteInto('id = ?', $idCup);
            
            try {
                $update['estado']='eliminado';
                $update['fecha_eliminacion']=date('Y-m-d H:i:s');
                $update['eliminado_por']=$this->auth['usuario']->id;
                $objCupon->update($update, $where);
                
                $objBeneficioVersion->setBeneficio_id($result['beneficio_id']);
                $objBeneficioVersion->setDisminuyeStockActual('');
                
                $this->_helper->ContadoresCupon
                   ->actualizarCuponGeneradoSuscriptorBeneficio($result['suscriptor_id'], $result['beneficio_id'], '');
                $this->_helper->ContadoresCupon
                   ->actualizarCuponConsumidoSuscriptorBeneficio($result['suscriptor_id'], $result['beneficio_id'], '');
                
//                $messages['success'] = true;
//                $messages['mensaje'] = 'Se ejecuto correctamente';
                $msj  = 'Se ejecuto correctamente';
                $data = 1;
                
                $db->commit();
            } catch (Exception $e) {
                //echo $e->getMessage();
                $db->rollBack();
//                $messages['success'] = false;
//                $messages['mensaje'] = 'Error';
                $msj  = 'Error';
                $data = -1;
            }
            //$this->_response->appendBody(Zend_Json::encode($messages));
            
        }
        $this->view->idCup = $idCup;
        $this->view->data = $data;
        $this->view->msj = $msj;
    }
    
}

