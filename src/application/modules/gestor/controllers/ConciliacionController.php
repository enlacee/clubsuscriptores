<?php

class Gestor_ConciliacionController extends App_Controller_Action_Gestor
{
    protected $_cupon;
    
    public function init()
    {
        parent::init();
        /* Initialize action controller here */
        Zend_Layout::getMvcInstance()->active
            = App_Controller_Action_Gestor::MENU_NAME_CONCILIACION;
        
        $this->_cupon = new Application_Model_Cupon();
        $p = new Plugin_CsrfProtect();
        $this->view->csrf = $p->session->key;
    }

    public function indexAction()
    {
        $this->view->headTitle('Conciliacion');
        $this->_forward('inicio');
    }
    
    public function inicioAction()
    {
        $js = sprintf("var dt = {logo : '%s'}", $this->mediaUrl . '/images/calendar.png');
        $this->view->headScript()->appendScript($js);
        $this->view->headLink()->appendStylesheet(
            $this->mediaUrl . '/js/datepicker/themes/ui-lightness/ui.all.css', 'all'
        );
        $this->view->headScript()->appendFile($this->mediaUrl . '/js/datepicker/ui/ui.core.js');
        $this->view->headScript()->appendFile($this->mediaUrl . '/js/datepicker/ui/ui.datepicker.js');
        $this->view->headScript()->appendFile($this->mediaUrl . '/js/datepicker/ui/i18n/ui.datepicker-es.js');
        $this->view->headScript()->appendFile($this->mediaUrl . '/js/gestor/conciliacion.js');

        $this->view->idEstablecimiento = $idE = $this->_getParam("establecimiento");
        $this->view->page = $page = $this->_getParam("page", 1);
        $this->view->ord = "ASC";

        $frm = new Application_Form_FiltroConciliacion();
        $frm->llenarCombos($idE);
        

        $idE = "";
        $fi = "";
        $ff = "";
        if ($this->_request->isPost()) {
            $this->view->submenu = true;
            //var_dump($cupones);
            $fechaini = $this->_getParam("fechaini");
            $fechafin = $this->_getParam("fechafin");
            $idE = $this->_getParam("establecimiento");

            $frm->setField("establecimiento", $idE);
            $frm->setField("fechaini", $fechaini);
            $frm->setField("fechafin", $fechafin);

            //busqueda
            $fi = explode("/", $fechaini);
            $fi = $fi[2]."-".$fi[1]."-".$fi[0];
            $ff = explode("/", $fechafin);
            $ff = $ff[2]."-".$ff[1]."-".$ff[0];
        }

        $objCupon = new Application_Model_Cupon();
        $paginator = $objCupon->listarCuponesConciliacion($idE, $fi, $ff);
        
        $this->view->MostrandoN  = $paginator->getItemCount($paginator->getItemsByPage($page));
        $this->view->MostrandoDe = $paginator->getTotalItemCount();
        $this->view->totalitems  = $paginator->getItemCount($paginator->getItemsByPage($page));
        $paginator->setCurrentPageNumber($page);
        
        $this->view->cupones = $paginator;

        $this->view->fechaini = $fi;
        $this->view->fechafin = $ff;

        $this->view->frm = $frm;
        $this->view->selectmenu = 'inicio';

        $nroPorPage = $paginator->getItemCountPerPage();
        $nroPage = $paginator->getCurrentPageNumber();
        $nroReg = $paginator->getCurrentItemCount();

        $this->view->mostrando = "Mostrando ".
            ' '.(($nroPage-1)*$nroPorPage + 1).
            ' - '.((($nroPage-1)*$nroPorPage) + $nroReg).
            ' de '.$paginator->getTotalItemCount();
        $this->view->nroregistros = "Registros listados : ".$nroReg;
    }

    public function filtrosIndexAction()
    {
        $objCupon = new Application_Model_Cupon();

        $this->view->idEstablecimiento = $idE = $this->_getParam("idestablecimiento");
        $this->view->fechaini = $fi = $this->_getParam("fechaini");
        $this->view->fechafin = $ff = $this->_getParam("fechafin");

        $this->view->promociones = $p = $this->_getParam("promociones");
        $this->view->estado = $e = $this->_getParam("estado");
        $this->view->voucher = $v = $this->_getParam("voucher");

        $this->view->page = $page = $this->_getParam("page", 1);
        $this->view->ord = $ord = $this->_getParam("ord");
        $this->view->col = $col = $this->_getParam("col");
        
        $paginator = $objCupon->listarCuponesConciliacion($idE, $fi, $ff, $p, $e, $v, $ord, $col);

        $this->view->MostrandoN  = $paginator->getItemCount($paginator->getItemsByPage($page));
        $this->view->MostrandoDe = $paginator->getTotalItemCount();
        $this->view->totalitems  = $paginator->getItemCount($paginator->getItemsByPage($page));
        $paginator->setCurrentPageNumber($page);

        $this->view->cupones = $paginator;

        $nroPorPage = $paginator->getItemCountPerPage();
        $nroPage = $paginator->getCurrentPageNumber();
        $nroReg = $paginator->getCurrentItemCount();

        $this->view->mostrando = "Mostrando ".
            ' '.(($nroPage-1)*$nroPorPage + 1).
            ' - '.((($nroPage-1)*$nroPorPage) + $nroReg).
            ' de '.$paginator->getTotalItemCount();
        $this->view->nroregistros = "Registros listados : ".$nroReg;
        //var_dump($this->view->csrf);
        echo $this->view->partial("conciliacion/_main-conciliacion.phtml", $this->view);
        exit;
    }
    
    public function conciliarCuponAction() 
    {
        $objCupon = new Application_Model_Cupon();
        $conciliar = $this->_getParam("conciliar");
        if (isset($conciliar) && $conciliar!="") {
            $obj = $objCupon->find($conciliar);
            $data = array("estado"=>"conciliado");
            if ($obj[0]->estado=="conciliado")
                $data = array("estado"=>"redimido");

            $where = $objCupon->getAdapter()->quoteInto("id=?", $conciliar);
            $objCupon->update($data, $where);
        }
    }

    public function editarCuponAction()
    {
        $this->_helper->layout->disableLayout();
        $id = $this->_getParam("id");
        if (isset($id)) {
            $obj = new Application_Model_Cupon();
            $cupon = $obj->getCuponxIdEditar($id);
            $this->view->cupon = $cupon;
        }
    }
    public function grabarCuponAction()
    {
        $this->_helper->layout->disableLayout();
        $id = $this->_getParam("id");
        if (isset($id)) {
            $comentario = $this->_getParam("comentario");
            $voucher = $this->_getParam("voucher");
            $obj = new Application_Model_Cupon();
            $arreglo = array(
                "comentario" => $comentario,
                "numero_comprobante" => $voucher
            );
            $where = $obj->getAdapter()->quoteInto("id=?", $id);
            $obj->update($arreglo, $where);
            $this->_response->appendBody("1");
            exit;
        }
        $this->_response->appendBody("0");
        exit;
    }
    
    public function reporteAction()
    {
        $this->view->headScript()->appendFile(
            $this->mediaUrl . '/js/gestor/conciliacion2.js'
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
        $this->view->selectmenu = 'reporte';
        
        $frmConcilia = new Application_Form_FiltroConciliacion();
        $this->view->form = $frmConcilia;
        
        $this->redimidosConciliaPaginator();
    }
    
    protected function redimidosConciliaPaginator($nropage = 1, $establecimientoId = '')
    {
        $queryRedimidosC = 
            $this->_cupon->getConciliadosPorEstablecimientoYPromo($establecimientoId);
        
        $page = $nropage;
        $nropagesRedimidosC = $this->getConfig()->gestor->redimidosconcilia->nropaginas;

        $zp = Zend_Paginator::factory($queryRedimidosC);
        $paginator = $zp->setItemCountPerPage($nropagesRedimidosC);
        //$paginator = $zp;
        $this->view->nrofields = $paginator->getAdapter()->count();
        
        $this->view->totalitems = $paginator->getItemCount($paginator->getItemsByPage($page));
        $paginator->setCurrentPageNumber($page);
        $this->view->redimidos = $paginator;
        
        $nroPorPage = $paginator->getItemCountPerPage();
        $nroPage = $paginator->getCurrentPageNumber();
        $nroReg = $paginator->getCurrentItemCount();
        
        $this->view->mostrando = "Mostrando ".
            ' '.(($nroPage-1)*$nroPorPage + 1).
            ' - '.((($nroPage-1)*$nroPorPage) + $nroReg).
            ' de '.$paginator->getTotalItemCount();
        $this->view->nroregistros = "Registros listados : ".$nroReg;
    }
    
    public function listaRedimidosAction()
    {
        $this->_helper->layout->disableLayout();
        $page = $this->_getParam('page', 1);
        $estId = $this->_getParam('establecimiento', '');
        $fecIni = $this->_getParam('fecha_consumo_desde', '');
        $fecFin = $this->_getParam('fecha_consumo_hasta', '');
        $this->_cupon->setFecha_consumo_from($fecIni);
        $this->_cupon->setFecha_consumo_at($fecFin);
        $this->_cupon->setBeneficio_id($this->_getParam('promocion', ''));
        $cadFecha = '';
        if(!empty($fecIni) || !empty($fecFin)):
            if(!empty($fecIni) && !empty($fecFin)):
                $cadFecha = 'De '.$fecIni.' al '.$fecFin;
            elseif(!empty($fecIni) && empty($fecFin)):
                $cadFecha = 'Desde '.$fecIni;
            elseif(empty($fecIni) && !empty($fecFin)):
                $cadFecha = 'Hasta '.$fecFin;
            endif;
        endif;
        $this->view->cad_fecha = $cadFecha;
        $this->redimidosConciliaPaginator($page, $estId);
    }
    
    public function jsonAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $case = $this->_getParam('case', '');
        
        $data = array();
        switch ($case) {
            case 'getDatos':
                $idEst = $this->_getParam('establecimientoId', '');
                $fecIni = $this->_getParam('fecha_consumo_ini', '');
                $fecFin = $this->_getParam('fecha_consumo_fin', '');
                //if (!empty($idEst)):
                    $objCupon = new Application_Model_Cupon();
                    
                    $objCupon->setFecha_consumo_from($fecIni);
                    $objCupon->setFecha_consumo_at($fecFin);
                    $promos = $objCupon->getBeneficiosConciliadosPorEstabYFecConsumo($idEst);
                    $montoTot = 0;
                    $data['promos'] = array();
                    foreach ($promos as $ind => $value) {
                        $data['promos'][$ind]['id'] = $value['id'];
                        $data['promos'][$ind]['titulo'] = $value['titulo'];
                        $montoTot+= $value['total'];
                    }
                    $datEstab = Application_Model_Establecimiento::getEstablecimiento($idEst);
                    $data['nombre'] = $datEstab['nombre'];
                    /*if (empty($idEst)):
                        $data['nombre'] = 'Todos';
                    endif;*/
                    $data['total_conciliado'] = $montoTot;
                    $data['cad_fecha'] = '';
                    if(!empty($fecIni) || !empty($fecFin)):
                        if(!empty($fecIni) && !empty($fecFin)):
                            $data['cad_fecha'] = 'De '.$fecIni.' al '.$fecFin;
                        elseif(!empty($fecIni) && empty($fecFin)):
                            $data['cad_fecha'] = 'Desde '.$fecIni;
                        elseif(empty($fecIni) && !empty($fecFin)):
                            $data['cad_fecha'] = 'Hasta '.$fecFin;
                        endif;
                    endif;
                /*else:
                    $data['promos'] = array();
                    $data['nombre'] = 'Todos';
                    $data['total_conciliado'] = '00.00';
                    $data['cad_fecha'] = '';
                endif;*/
                //var_dump($data); exit;
                break;
            default:
                break;
        }
        $this->_response->appendBody(Zend_Json::encode($data));
    }
    
    public function imprimirReporteAction() 
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        
        $page = $this->_getParam('page', 1);
        $estId = $this->_getParam('establecimiento', '');
        $fecIni = $this->_getParam('fecha_consumo_desde', '');
        $fecFin = $this->_getParam('fecha_consumo_hasta', '');
        $proId = $this->_getParam('promocion', '');
        $this->_cupon->setFecha_consumo_from($fecIni);
        $this->_cupon->setFecha_consumo_at($fecFin);
        $this->_cupon->setBeneficio_id($proId);
        $cadFecha = '';
        if(!empty($fecIni) || !empty($fecFin)):
            if(!empty($fecIni) && !empty($fecFin)):
                $cadFecha = 'De '.$fecIni.' al '.$fecFin;
            elseif(!empty($fecIni) && empty($fecFin)):
                $cadFecha = 'Desde '.$fecIni;
            elseif(empty($fecIni) && !empty($fecFin)):
                $cadFecha = 'Hasta '.$fecFin;
            endif;
        endif;
        $this->view->cad_fecha = $cadFecha;
        
        $queryRedimidosC = $this->_cupon->getConciliadosPorEstablecimientoYPromo($estId);
        $rs = $this->getAdapter()->fetchAll($queryRedimidosC);
        $this->view->redimidos = $rs;
        $this->view->totalreg = count($rs);
        $this->view->name_establecimiento = 'Todos';
        if (!empty($estId)):
            $datEstab = Application_Model_Establecimiento::getEstablecimiento($estId);
            $this->view->name_establecimiento = $datEstab['nombre'];
        endif;
        $this->view->name_promo = 'Todos';
        if (!empty($proId)):
            $objBenef = new Application_Model_Beneficio();
            $datPromo = $objBenef->find($proId)->toArray();
            //var_dump($datPromo[0]); exit;
            $this->view->name_promo = $datPromo[0]['titulo'];
        endif;
        
        //$domPdf = $this->_helper->getHelper('DomPdf');
        $html = $this->view->render('conciliacion/imprimir-reporte-ok.phtml');
        /*$mvc = Zend_Layout::getMvcInstance();
        $layout = $mvc->render('reporte_consumos_pdf');
        $layout = str_replace("<!--reporte-->", $html, $layout);
        $layout = str_replace("\"", "'", $layout);
        $domPdf->mostrarPDF($layout, 'A4', "landscape", "reporte_conciliacion.pdf");*/
        
        $bin = APPLICATION_PATH."/../../bin/wkhtmltopdf/linux/wkhtmltopdf-i386";
        if(count(explode("WIN", PHP_OS))>1) 
                $bin = APPLICATION_PATH."/../../bin/wkhtmltopdf/win/wkhtmltopdf.exe";
        
        try {
            $wkhtmltopdf = new Wkhtmltopdf(
                array('path' => ELEMENTS_ROOT . '/pdfs_tmp/','binpath' => $bin)
            );
            $wkhtmltopdf->setTitle("Reporte de Conciliación");
            //$wkhtmltopdf->setZoom("1.3");
            //echo $html; exit;
            $wkhtmltopdf->setMargins(
                array('top' => 12, 'bottom' => 8, 'left' => 7, 'right' => 7)
            );
            $wkhtmltopdf->addDate();
            $wkhtmltopdf->addPage();
            $wkhtmltopdf->setHtml($html);
            $wkhtmltopdf->output(Wkhtmltopdf::MODE_EMBEDDED, "src/public/reporte_conciliacion.pdf");
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    
    public function imprimirReporteOkAction() 
    {
    }
}

