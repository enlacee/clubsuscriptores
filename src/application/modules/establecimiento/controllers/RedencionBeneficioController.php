<?php

class Establecimiento_RedencionBeneficioController extends App_Controller_Action_Establecimiento
{
    protected $_beneficioVersion;
    
    public function init()
    {
        /* Initialize action controller here */
        parent::init();
        $this->_beneficioVersion = new Application_Model_BeneficioVersion();
        $this->_fecha = new Zend_Date();

        $p = new Plugin_CsrfProtect();
        $this->view->csrf = $p->session->key;
    }

    public function indexAction()
    {
        $this->_forward('inicio');
        Zend_Layout::getMvcInstance()->active = 
            App_Controller_Action_Establecimiento::MENU_NAME_INICIO;
//        Zend_Layout::getMvcInstance()->active = 
//            App_Controller_Action_Establecimiento::MENU_NAME_REDENCION_BENEFICIO;
//        $this->view->headTitle('Establecimiento');
        
    }
    
    public function inicioAction()
    {
        /*Zend_Layout::getMvcInstance()->active = 
           App_Controller_Action_Establecimiento::MENU_NAME_REDENCION_BENEFICIO;*/
        //$this->view->menu_post_sel = self::MENU_POST_SIDE_INICIO;
        $this->view->headScript()->appendFile(
            $this->config->app->mediaUrl . '/js/establecimiento/redimir_beneficio.js'
        );
        $this->view->imgPhoto= '';
        //$this->view->nombreEmp = $this->auth['establecimiento']['nombre'];
        $this->view->nombreUsu = $this->auth['datos']['nombres'];
        $this->view->idUsu = $this->auth['usuario']->id; 
        
        $formRediBeneIni = new Application_Form_RedimirBeneficioInicio();
        $formRediBeneIni->addTipoDocumento();
        $this->view->formRediBeneIni = $formRediBeneIni; 
    }
    
    public function promocionAction()
    {
        Zend_Layout::getMvcInstance()->active = 
           App_Controller_Action_Establecimiento::MENU_NAME_REDENCION_BENEFICIO;
        $this->view->menu_post_sel = self::MENU_POST_SIDE_PROMOCION;
        
        $this->view->imgPhoto= '' ;
        $this->view->nombreEmp = $this->auth['establecimiento']['nombre'];
        $this->view->nombreUsu = $this->auth['usuario']->nombre;
        $this->view->idUsu = $this->auth['usuario']->id;
    }
    
    public function reporteAction()
    {
        Zend_Layout::getMvcInstance()->active = 
           App_Controller_Action_Establecimiento::MENU_NAME_REDENCION_BENEFICIO;
        $this->view->menu_post_sel = self::MENU_POST_SIDE_REPORTE;
        
        $this->view->imgPhoto= '' ;
        $this->view->nombreEmp = $this->auth['establecimiento']['nombre'];
        $this->view->nombreUsu = $this->auth['usuario']->nombre;
        $this->view->idUsu = $this->auth['usuario']->id;
    }

    public function validaDniCuponAction()
    {
        $this->_helper->layout->disableLayout();
        $params = $this->_getAllParams();
        
        $this->view->value = $numDoc = $params['value'];
        
        $this->view->tipo = $tipo = $params['tipo'];
        $idEstablecimiento = isset($this->auth['establecimiento']['id'])?$this->auth['establecimiento']['id']:'';
        $perfil_id = ($this->auth['usuario']->perfil_id == 7)?$this->auth['usuario']->perfil_id:'';
        $this->view->col = $col = $this->_getParam('col', '');
        $this->view->ord = $ord = $this->_getParam('ord', 'DESC');

        $page = $this->_getParam('page', 1);
        $this->view->page = $page;
        //$page = 2;
        //$page=2;
        if (!empty($idEstablecimiento) || !empty($perfil_id)) {
            if ($tipo==0) { //BUSQUEDA POR DNI
                $this->view->tipodoc = $tipodoc = $params['tipodoc'];
                if ($numDoc!= '') {
                    $modelCupon = new Application_Model_Cupon();
                    $modelSuscriptor = new Application_Model_Suscriptor();
                    $td = explode('#', $tipodoc);
                    $tipodoc = $td[0];
                    //Valida Dni
                    $arraySuscriptor = $modelSuscriptor->getBuscarXDocNumSuscriptorInvitado($numDoc, $tipodoc);
                    $this->view->estado = $estado = $arraySuscriptor['activo'];
                    $this->view->nombres = $arraySuscriptor['nombres'];
                    $this->view->apellidos 
                        = $arraySuscriptor['apellido_paterno']. ' ' . $arraySuscriptor['apellido_materno'];

                    //$arrayCupon = $modelCupon->cuponXDni($numDoc, $tipodoc, $idEstablecimiento);
                    //$this->view->idSus = $idSus =  $arrayCupon['id_suscriptor'];
                      $this->view->idSus = $idSus =  $arraySuscriptor['id'];
                      //echo var_dump($arraySuscriptor); exit;
                    if ($arraySuscriptor) {
                        // Valida Tipo de Busqueda x DNI
                            //Tiene Cupon ?
                        
                            $modelBeneficio = new Application_Model_Beneficio();
//                            $modelBeneficio->setActivo(1);
//                            $modelBeneficio->setPublicado(1);
                            $modelBeneficio->setEstado('borrador',false);
                            $modelBeneficio->setFecha_vigencia();

                            //if ( count($arrayCupon) != 1 ) {
                                $paginator = $modelBeneficio->getPaginatorListarPromociones(
                                    $arraySuscriptor['id'],
                                    $idEstablecimiento, $ord, $col, $estado
                                );
                                $this->view->mostrando = "Mostrando "
                                     .$paginator->getItemCount($paginator->getItemsByPage($page))
                                     ." de "
                                     .$paginator->getTotalItemCount();

                                $paginator->setCurrentPageNumber($page);
                                $this->view->arrayPromo = $paginator;
                            /*} else {
                                    //Si es que su Usuario NO ESTA vigente y No tiene CUPO
                                     $this->view->arrayPromo = null;
                            }*/
                    } else {
//                        echo 'No existe el usuario o es invalido';
                    }
                }
            } else { //BUSQUEDA POR CUPON
                  $objCupon = new Application_Model_Cupon();
                  //$idEstablecimiento = $this->auth["establecimiento"]["id"];
                  $obj = $objCupon->getCuponDisponible($numDoc, $idEstablecimiento);
                  $result = 0;
                  if ($obj!=null) {
                      if ($obj[0]["idEstablecimiento"] == $idEstablecimiento || !empty($perfil_id) ) {
                          $result = 0;
                      } else {
                          $result = 1;
                      }
                  }
                  $this->view->nrocupon = $numDoc;
                  $this->view->cupon = $obj;
                  $this->view->result = $result;
            }
        }
    }
    
    public function redimirCuponAction()
    {
        $data = '0';
        $this->_helper->layout->disableLayout();
        $allParams = $this->_getAllParams();
        $this->view->idSus = $idSus = $allParams['idS'];
        $this->view->idBen = $idBen = $allParams['idB'];
//        $this->view->estado = $idEst = $allParams['est'];
        $this->view->tipo = $tipo = $allParams['tipo'];
        $this->view->nroC = $nroC = $this->_getParam('nroC', '');
        /*verificamos si esta activo el usuario*/
        $this->view->estado = $idEst = Application_Model_Suscriptor::getActivoXId($idSus);

        $msjValidezDatos = 'Verifique la validez de los datos.';
        $msjStockInsuf='Nro Solicitado Excede el Stock Disponible';
        $msjMaxSusc='Excedió el limite de cupos permitidos en este descuento';
        $msjStockCantidad='Nro solicitado excede el stock disponible en este descuento';
        $msjStockInsufMaxSuscriptor = 'Nro Solicitado Excede el Permitido por Suscriptor';
        $msjMaxSuscriptorConsumido = 'Se consumio el maximo permitido por Suscriptor';
        
        /*obtenemos el tipo de moneda del beneficio para luego registrarlo y utilizar en el form*/
        $objBeneficio = new Application_Model_Beneficio();
        $objBeneficio->setId($idBen);
        $this->view->monedaArray = $monedaArray = $objBeneficio->getTipoMonedaById();
        
        /*obtenemos el numero de cupones generados por el suscriptor y un beneficio dado*/
        $modelCupon = new Application_Model_Cupon();
        $modelCupon->setBeneficio_id($idBen);
        $modelCupon->setSuscriptor_id($idSus);
        $cantBenGenerados=$modelCupon->getCantCuponesByBenef();
        
        $objDetTipoPromo = new Application_Model_DetalleTipoPromocion();
        $objDetTipoPromo->setBeneficio_id($idBen);
        //##############################borrar
            //$rsTip = $objDetTipoPromo->getTiposPromocionByBenef($idBen, $cantBenGenerados);
        
        
        $nroSolicita = $this->_getParam('nrocupones', 1);
        $this->view->nro = $nroSolicita;
        $formRedimirCupon = new Application_Form_RedimirCupon();
        $formRedimirCupon->setNr_cuponGen($nroSolicita);
        $formRedimirCupon->addNumeroCupon();
                
        $messageNum = '';
        $cadsDisponible = '';
        
        $arrayCupon = $modelCupon->getDescripcionRedimirCupon($idSus, $idBen, 0, $nroC);
        $this->_helper->ContadoresCupon->insertSuscriptorBeneficio($idSus, $idBen);
        
//        $modelSusBen = new Application_Model_SuscriptorBeneficio();
//        $arrayCantCupon = $modelSusBen->getCuponesXGenerar($idSus, $idBen);
        
        $arrayCantCupon = array_merge(
            $objBeneficio->getDataRendencionBeneficio(), 
            $modelCupon->getCantRendencionByBeneficioAndSuscriptor(), 
            $modelCupon->getCantCuponesByBenefAndSuscrip()
        );
        
        $rsTipoPromo = $objDetTipoPromo->getTiposPromocionCmbByBenef($arrayCantCupon['tipo_redencion']);
        $jsRsTipoPromo = $objDetTipoPromo->getTiposPromocionCmbByBenef('descrip');
        $js = sprintf('var messageTipB = %s;', Zend_Json::encode($jsRsTipoPromo));        
        $this->view->cadScript = $js;
        $this->view->tipoFlag = $validStockTipoPromo= !empty($rsTipoPromo);
        
        
        //->Ticket #19999
        if (!empty($rsTipoPromo)) {
            $formRedimirCupon->addTipoPromocionCupon($rsTipoPromo);
            if($arrayCantCupon['tipo_redencion']==1){
                $formRedimirCupon->addMontoCupon();
            }
        } else { 
            $formRedimirCupon->addMontoCupon();
        }
        if ($tipo!=1) { 
            $formRedimirCupon->addNroCuponesGen();
        }
        
        //var_dump($arrayCantCupon);exit;
        
        //*APR=====> Validacion de los cupones disponibles a generar
        //*--------> El beneficio no tiene stock y ademas no tiene limite por suscriptor
        $validNrocupOne = $arrayCantCupon['sin_stock']==1 && $arrayCantCupon['sin_limite_por_suscriptor']==1;
        //*--------> El beneficio tiene limite de stock, pero no limite de cupones por suscriptor         
        $validNrocupTwo = $arrayCantCupon['sin_stock'] !=1 && $arrayCantCupon['sin_limite_por_suscriptor']==1 && 
            $arrayCantCupon['stock_actual']>0 && $arrayCantCupon['stock_actual'] >= $nroSolicita;
        /**--------> El beneficio tiene stock limitado y hay limite por suscriptor, la cantidad de cupones generados
         * no debe sobrepasar el maximo_por_suscriptor
         */
        $validNrocupThree = $arrayCantCupon['sin_stock'] !=1 && $arrayCantCupon['sin_limite_por_suscriptor']!=1 &&
            $arrayCantCupon['stock_actual']>=$nroSolicita && $arrayCantCupon['stock_actual'] > 0 &&
            ($arrayCantCupon['maximo_por_subscriptor'] >= ($arrayCantCupon['cupon_consumido']+$nroSolicita))
            ;
        /**--------> El suscriptor aun tiene cupones disponibles para generar, 
         * el beneficio tiene limite por suscriptor,
         * el beneficio no tiene un stock limite.
         */
        $validNrocupFour = $arrayCantCupon['sin_stock']==1 && $arrayCantCupon['sin_limite_por_suscriptor']!=1 &&
            ($arrayCantCupon['maximo_por_subscriptor'] >= ($arrayCantCupon['cupon_consumido']+$nroSolicita));
        
        //*--------> El suscriptor puede redimir los cupones generados por la web
        $validNrocupFive = $arrayCantCupon['cupon_generado'] >= ($arrayCantCupon['cupon_consumido']+$nroSolicita);
        
        //*APR=====> Validacion de los Msj 
        $validAmsjOne = $validNrocupFive || ($arrayCantCupon['sin_stock']!=1 && $arrayCantCupon['stock_actual'] >= 1)
                || $arrayCantCupon['sin_stock']==1;
        $validAmsjTwo = $validNrocupFive || $arrayCantCupon['sin_limite_por_suscriptor']==1
            || ($arrayCantCupon['sin_limite_por_suscriptor']!=1 
                && $arrayCantCupon['maximo_por_subscriptor'] >= ($arrayCantCupon['cupon_consumido']+$nroSolicita));
        //echo $arrayCantCupon['cupon_consumido'];exit;
//        $validAmsjTwo = ($validNrocupFive || $arrayCantCupon['sin_stock']==1
//                || ($arrayCantCupon['sin_stock']==0 && $arrayCantCupon['stock_actual']>=$nroSolicita))
//            && ($arrayCantCupon['sin_limite_por_suscriptor']==1 
//                && ()) ;
        
        //POST
        if ($this->_request->isPost()) {
            $valid = $formRedimirCupon->isValid($allParams);
            $valuesCupon = $formRedimirCupon->getValues();
            
            //echo 'ENTRO al POST';
            if (!empty($arrayCantCupon['id']) && $idEst == 1) {
                
                $validDisponible = true;
                if ($valid && ($validNrocupOne || $validNrocupTwo || $validNrocupThree || $validNrocupFour 
                        || $validNrocupFive) && $validDisponible) {
                    
                    $db = $this->getAdapter();
                    try {
                        $db->beginTransaction();
                        
                        $nrofila = 0;
                        //RECORREMOS LOS CUPONES GENERADOS
                        $arrayCuponUpdate['redimido_por'] = $this->auth['usuario']->id;
                        $arrayCuponUpdate['comentario_redencion'] = 
                            isset($allParams['comentario_redencion'])?$allParams['comentario_redencion']:null;
                        $arrayCuponUpdate['estado'] = Application_Model_Cupon::ESTADO_REDIMIDO;
                        $arrayCuponUpdate['medio_redencion'] = 
                            Application_Model_Cupon::MEDIO_PUBLICACION_WEB;
                        $nroCupGenDisp = count($arrayCupon);
                        $nroRedi = ($nroCupGenDisp <= $nroSolicita ? $nroCupGenDisp : $nroSolicita);
                        $nroNews = ($nroCupGenDisp <= $nroSolicita ? $nroSolicita - $nroCupGenDisp : 0);
//                        var_dump($arrayCupon);exit;
//                        echo $nroRedi.'_'.$nroNews;exit;
                        //var_dump($allParams, $valuesCupon); exit;
                        for ($icup = 0; $icup < $nroRedi; $icup++) {
                            $nrofila++;
                            $maximoByDetTipPro = "";
                            $where = $modelCupon->getAdapter()
                                ->quoteInto('id = ?', $arrayCupon[$icup]['id']);
                            $arrayCuponUpdate['fecha_redencion'] = date('Y-m-d H:i:s');
                            $arrayCuponUpdate['numero_comprobante'] =  $valuesCupon['numero_cupon'.$nrofila]; 
                            $arrayCuponUpdate['tipo_moneda_id'] =$monedaArray["tipo_moneda_id"];
                            $arrayCuponUpdate['fecha_consumo'] = date('Y-m-d H:i:s');
                            
                            if (!empty($allParams['tipoFlag'])) {
                                $part = explode('-', $valuesCupon['cbotipo'.$nrofila]); 
                                //$arrayCuponUpdate['monto_descontado'] = !empty($part[1])?$part[1]:0;
                                $arrayCuponUpdate['detalle_tipo_promocion_id'] = !empty($part[0])?$part[0]:NULL;
                                
                                $arrayCuponUpdate['tipo_redencion'] = $arrayCantCupon['tipo_redencion'];
                                if($arrayCantCupon['tipo_redencion']==1){
                                    $montoTipCierra=$valuesCupon['monto'.$nrofila];
                                    $monto_descontado= ($montoTipCierra*$part[4])/100;
                                    $arrayCuponUpdate['monto_descontado'] = $monto_descontado;
                                    $arrayCuponUpdate['numero_comprobante'] = $valuesCupon['numero_cupon1'];
                                    $arrayCuponUpdate['porcentaje_descuento'] = $part[4];
                                    $arrayCuponUpdate['precio_regular'] = ($montoTipCierra-$monto_descontado);
                                    $arrayCuponUpdate['precio_suscriptor'] = $montoTipCierra;
                                } else {
                                    $arrayCuponUpdate['monto_descontado'] = !empty($part[1])?$part[1]:0;
                                    $arrayCuponUpdate['precio_regular'] = $part[2];
                                    $arrayCuponUpdate['precio_suscriptor'] = $part[3];
                                }
                                
                                $maximoByDetTipPro = Application_Model_DetalleTipoPromocion::
                                    getDataById($part[0]);
                                
                                if ($arrayCantCupon["sin_limite_por_suscriptor"]==2) {
                                    $nCupGen = Application_Model_Cupon::
                                        getCantCuponesByBenefAndTipDescAndSuscriptor($idBen, $part[0], $idSus);
                                } else {
                                    $nCupGen = Application_Model_Cupon::
                                        getCantCuponesByBenefAndTipDescAndSuscriptor($idBen, null, $idSus);
                                }
                                if ($arrayCantCupon["sin_stock"]==2) {
                                    $nCupGenParaStock = Application_Model_Cupon::
                                        getCantCuponesByBenefAndTipDescAndSuscriptor($idBen, $part[0], null);
                                    $stockDisponibleDet['stock_actual'] = $maximoByDetTipPro['cantidad'] -
                                        ($nCupGenParaStock+1);
                                    $objDetTipoPromo->update($stockDisponibleDet, "id = '".$part[0]."'");
                                } else {
                                    $nCupGenParaStock = Application_Model_Cupon::
                                        getCantCuponesByBenefAndTipDescAndSuscriptor($idBen, null, null);
                                }
                            } else {
                                $arrayCuponUpdate['monto_descontado'] = $valuesCupon['monto'.$nrofila];
                                $nCupGen = Application_Model_Cupon::
                                    getCantCuponesByBenefAndTipDescAndSuscriptor($idBen, null, $idSus);
                            }
                            //echo $maximoByDetTipPro["maximo_por_suscriptor"].'_'.$nCupGen;exit;
                            if ($arrayCantCupon["sin_limite_por_suscriptor"]==2 && !empty($maximoByDetTipPro)) {
                                if ($maximoByDetTipPro['maximo_por_suscriptor'] < ($nCupGen+1)) {
                                    $msjException=$msjMaxSusc.': ';
                                    if (!empty($part[1])) {
                                        $msjException .= $maximoByDetTipPro['codigo'].'|'.$part[0];
                                    } else {
                                        $msjException .= $valuesCupon['monto'.$nrofila].'|';
                                    }
                                    throw new Exception($msjException);
                                }
                            } 
                            if ($arrayCantCupon["sin_stock"]==2 && !empty($maximoByDetTipPro)) {
                                if ($maximoByDetTipPro['cantidad'] < ($nCupGenParaStock+1)) {
                                    //echo $maximoByDetTipPro['maximo_por_suscriptor'].'_'.$nCupGen;exit;
                                    $msjException=$msjStockCantidad.': ';
                                    if (!empty($part[1])) {
                                        $msjException .= $maximoByDetTipPro['maximo_por_suscriptor'].'|'.$part[0].'|'.
                                                $maximoByDetTipPro['stock_actual'];
                                    } else {
                                        $msjException .= $valuesCupon['monto'.$nrofila].'|';
                                    }
                                    throw new Exception($msjException);
                                }
                            }
                            $modelCupon->update($arrayCuponUpdate, $where);
                        }

//                        $this->_helper->ContadoresCupon
//                            ->actualizarCuponConsumidoSuscriptorBeneficio(
//                                $idSus, $idBen, $nroRedi
//                            );

                        //Genera Cupon si es que aun tiene stock el usuario: NUEVOS CUPONES
                        $objbeneficioVersion = new Application_Model_BeneficioVersion($idBen);
                        $newCupon['comentario_redencion'] = $allParams['comentario_redencion'];
                        $newCupon['suscriptor_id'] = $idSus;
                        $newCupon['beneficio_id'] = $idBen;
                        $newCupon['fecha_inicio_vigencia'] = $objbeneficioVersion->getFecha_inicio_vigencia();
                        $newCupon['fecha_fin_vigencia'] = $objbeneficioVersion->getFecha_fin_vigencia();
                        $newCupon['estado'] = Application_Model_Cupon::ESTADO_REDIMIDO;

                        $newCupon['redimido_por'] = $this->auth['usuario']->id;
                        $newCupon['medio_redencion'] = Application_Model_Cupon::MEDIO_PUBLICACION_WEB;

                        for ($nr = 1; $nr <= $nroNews; $nr++) {
                            $nrofila++;
                            $maximoByDetTipPro = "";
                            $newCupon['fecha_redencion'] = date('Y-m-d H:i:s');
                            $newCupon['numero_comprobante'] = $valuesCupon['numero_cupon'.$nrofila];
                            if (!empty($allParams['tipoFlag'])) {
                                $part = explode('-', $valuesCupon['cbotipo'.$nrofila]); 
                                //$newCupon['monto_descontado'] = !empty($part[1])?$part[1]:0;
                                $newCupon['detalle_tipo_promocion_id'] = !empty($part[0])?$part[0]:NULL;
                                
                                $newCupon['tipo_redencion'] = $arrayCantCupon['tipo_redencion'];
                                if($arrayCantCupon['tipo_redencion']==1){
                                    $montoTipCierra=$valuesCupon['monto'.$nrofila];
                                    $monto_descontado= ($montoTipCierra*$part[4])/100;
                                    $newCupon['monto_descontado'] = $monto_descontado;
                                    $newCupon['numero_comprobante'] = $valuesCupon['numero_cupon1'];
                                    $newCupon['porcentaje_descuento'] = $part[4];
                                    $newCupon['precio_regular'] = $montoTipCierra;
                                    $newCupon['precio_suscriptor'] = ($montoTipCierra-$monto_descontado);
                                } else {
                                    $newCupon['monto_descontado'] = !empty($part[1])?$part[1]:0;
                                    $newCupon['precio_regular'] = $part[2];
                                    $newCupon['precio_suscriptor'] = $part[3];
                                }
                                
                                
                                $maximoByDetTipPro = Application_Model_DetalleTipoPromocion::
                                    getDataById($part[0]);
                                
                                if ($arrayCantCupon["sin_limite_por_suscriptor"]==2) {
                                    $nCupGen = Application_Model_Cupon::
                                        getCantCuponesByBenefAndTipDescAndSuscriptor($idBen, $part[0], $idSus);
                                } else {
                                    $nCupGen = Application_Model_Cupon::
                                        getCantCuponesByBenefAndTipDescAndSuscriptor($idBen, null, $idSus);
                                }
                                if ($arrayCantCupon["sin_stock"]==2) {
                                    $nCupGenParaStock = Application_Model_Cupon::
                                        getCantCuponesByBenefAndTipDescAndSuscriptor($idBen, $part[0], null);
                                    $stockDisponibleDet['stock_actual'] = $maximoByDetTipPro['cantidad'] -
                                        ($nCupGenParaStock+1);
                                    $objDetTipoPromo->update($stockDisponibleDet, "id = '".$part[0]."'");
                                } else {
                                    $nCupGenParaStock = Application_Model_Cupon::
                                        getCantCuponesByBenefAndTipDescAndSuscriptor($idBen, null, null);
                                }
                            } else {
                                $newCupon['monto_descontado'] = $valuesCupon['monto'.$nrofila];
                                $nCupGen = Application_Model_Cupon::
                                    getCantCuponesByBenefAndTipDescAndSuscriptor($idBen, null, $idSus);
                            }
                            //echo $arrayCantCupon["maximo_por_subscriptor"].'_'.$nCupGen;exit;
                            if ($arrayCantCupon['sin_stock'] != 1 && $arrayCantCupon["stock_actual"]<= 0) {
                                $msjException="( ".$msjStockInsuf.'|';
                                throw new Exception($msjException);
                            } else if ($arrayCantCupon["sin_limite_por_suscriptor"]==0 && 
                                    $arrayCantCupon["maximo_por_subscriptor"]<($nCupGen+1)) {
                                $msjException="( ".$msjStockInsufMaxSuscriptor.'|';
                                throw new Exception($msjException);
                            } else if ($arrayCantCupon["sin_limite_por_suscriptor"]==2 && !empty($maximoByDetTipPro)) {
                                if ($maximoByDetTipPro['maximo_por_suscriptor'] < ($nCupGen+1)) {
                                    //echo $maximoByDetTipPro['maximo_por_suscriptor'].'_'.$nCupGen;exit;
                                    $msjException=$msjMaxSusc.': ';
                                    if (!empty($part[1])) {
                                        $msjException .= $maximoByDetTipPro['codigo'].'|'.$part[0];
                                    } else {
                                        $msjException .= $valuesCupon['monto'.$nrofila].'|';
                                    }
                                    throw new Exception($msjException);
                                }
                            } 
                            
                            if ($arrayCantCupon["sin_stock"]==0 && 
                                    $arrayCantCupon["stock"]<($nCupGenParaStock+1)) {
                                $msjException="( ".$msjStockCantidad.'|';
                                throw new Exception($msjException);
                            } else if ($arrayCantCupon["sin_stock"]==2 && !empty($maximoByDetTipPro)) {
                                if ($maximoByDetTipPro['cantidad'] < ($nCupGenParaStock+1)) {
                                    //echo $maximoByDetTipPro['maximo_por_suscriptor'].'_'.$nCupGen;exit;
                                    $msjException=$msjStockCantidad.': ';
                                    if (!empty($part[1])) {
                                        $msjException .= $maximoByDetTipPro['codigo'].'|'.$part[0].'|'.$maximoByDetTipPro['stock_actual'];
                                    } else {
                                        $msjException .= $valuesCupon['monto'.$nrofila].'|';
                                    }
                                    throw new Exception($msjException);
                                }
                            }
                            
                            $newCupon['tipo_moneda_id'] =$monedaArray["tipo_moneda_id"];
                            $newCupon['fecha_consumo'] = date('Y-m-d H:i:s');

                            $newCupon['fecha_emision'] = $this->_fecha->toString('YYYY-MM-dd HH:mm:ss');
                            //$newCupon['codigo'] = date("Ymdhis").$nr.rand(0, 9);
                            $newCupon['codigo'] = $this->_helper->CodigoCupon->generar();
                            $modelCupon->insert($newCupon);
                        }
                            
                        //disminuir el stock del beneficio
                        $this->_beneficioVersion->setBeneficio_id($idBen);
                        if ($arrayCantCupon['sin_stock'] != 1) {
                            $this->_beneficioVersion->setDisminuyeStockActual($nroNews);
                        }

                        $this->_helper->ContadoresCupon
                            ->actualizarCuponGeneradoSuscriptorBeneficio($idSus, $idBen, $nroNews);
                        $this->_helper->ContadoresCupon
                            ->actualizarCuponConsumidoSuscriptorBeneficio($idSus, $idBen, $nroNews);
                        
                        $db->commit();
                        $data = '1';
                    } catch (Exception $e) {
                        //echo $e->getMessage();
                        $db->rollBack();
                        $data = '-1';
                        $valGetMsj =$e->getMessage();
                        
                        $arrayGetMsj  = isset($valGetMsj)?
                        explode("|", $valGetMsj):array();
                        
                        $nCupGen="";$maximoByDetTipPro="";$msjCatc="";
                        if ( !empty($arrayGetMsj[1]) ) {
                            $maximoByDetTipPro = Application_Model_DetalleTipoPromocion::
                                    getDataById($arrayGetMsj[1]);
                            $nCupGen = Application_Model_Cupon::
                                    getCantCuponesByBenefAndTipDescAndSuscriptor($idBen, $arrayGetMsj[1], $idSus);
                            if ( isset($arrayGetMsj[2]) ) {
                                $msjCatc=' (Stock. ';
                                $nCupGen=$maximoByDetTipPro['stock_actual']-$nCupGen;
                            } else {
                                $msjCatc=' (Max. ';
                                $nCupGen=$maximoByDetTipPro['maximo_por_suscriptor']-$nCupGen;
                            }
                            
                            $nCupGen = ($nCupGen<1)?0:$nCupGen;
                            $msjCatc=$msjCatc.$nCupGen. ' Cupos';
                        }
                        $cadsDisponible = $arrayGetMsj[0].' '.$msjCatc .')';
                        
                    }
                } else {
                    $p = new Plugin_CsrfProtect();
                    $p->_initializeTokens();
                    $this->view->csrf = $p->session->key;
                    
                    //var_dump($arrayCantCupon['maximo_por_subscriptor']);
                    if ($arrayCantCupon['stock_actual']<$nroSolicita && $arrayCantCupon['sin_stock']!=1) {
                        $data = '-1';
                        $messageNum = $msjStockInsuf;
                    } elseif ($arrayCantCupon['sin_limite_por_suscriptor']!=1 
                        && $arrayCantCupon['maximo_por_subscriptor'] < 
                        ($nroSolicita + $arrayCantCupon['cupon_consumido'])) {
                        $messageNum = $msjStockInsufMaxSuscriptor;
                        $data = '-1';
                    }
                    //var_dump($arrayCupon, $allParams); exit;
                    if ($data == '0') {
                        $data = '-1';
                        //$messageNum = $msjStockInsufMaxSuscriptor;
                        $messageNum = $msjValidezDatos;
                    }
                }
            } else {
                $data = '-1';
                $messageNum = 'El suscriptor esta inactivo';
            }
        } else {
            if (!$validAmsjOne) {
                $data = '-4';             
            } elseif (!$validAmsjTwo) { 
                $data = '-1';$messageNum = $msjMaxSuscriptorConsumido;
            } elseif (!($validNrocupOne || $validNrocupTwo || $validNrocupThree || $validNrocupFour 
                    || $validNrocupFive)) {
                $data = '-2';
            }
        }
        if ($idEst == 0) { 
            $data = '-3'; 
        }
        
        if (!empty($arrayCupon[0])) { 
            $arrayCupon = $arrayCupon[0];
        } else { 
            $arrayCupon = $modelCupon->getInfoRedimeBenefSuscrip($idSus, $idBen);
        }
        
        $this->view->tipo_redencion = $arrayCantCupon['tipo_redencion'];
        $this->view->mensajeStock = $cadsDisponible;
        $this->view->menssageNro = $messageNum;
        $this->view->logComportamiento = $data;
        $this->view->datosRedimir = $arrayCupon;
        $this->view->formRedimirC = $formRedimirCupon;
    }
    
    public function redimirLoteAction()
    {
        $this->_helper->layout->disableLayout();
        //var_dump($this->_getAllParams()); exit;
        $nro = $this->_getParam('nrocupones', 0);
        $idB = $this->_getParam('idB', 0);
        $values = $this->getRequest()->getPost();
        
        $objBeneficio = new Application_Model_Beneficio();
        $objBeneficio->setId($idB);
        $arrayCantCupon=$objBeneficio->getDataRendencionBeneficio(); 
        $this->view->monedaArray = $objBeneficio->getTipoMonedaById();
        /*verificamos si el beneficio tiene tipos de promocion asignados*/
        $modelCupon = new Application_Model_Cupon();
        $modelCupon->setBeneficio_id($idB);
//        $cantBenGenerados=$modelCupon->getCantCuponesByBenef();
        $objDetTipPro = new Application_Model_DetalleTipoPromocion();
        $objDetTipPro->setBeneficio_id($idB);
        $rsTipPro = $objDetTipPro->getTiposPromocionCmbByBenef($arrayCantCupon['tipo_redencion']);
        $this->view->tipoFlag = !empty($rsTipPro);
        
        $frmRedCupon = new Application_Form_RedimirCupon();
        $frmRedCupon->setNr_cuponGen($nro);
        
        if (!empty($rsTipPro)) {
            $frmRedCupon->addTipoPromocionCupon($rsTipPro);
            if($arrayCantCupon['tipo_redencion']==1){
                $frmRedCupon->addMontoCupon();
            }
        } else {
            $frmRedCupon->addMontoCupon();
        }
        $frmRedCupon->addNumeroCupon();
        
        $frmRedCupon->setDefaults($values);
        $this->view->tipo_redencion = $arrayCantCupon['tipo_redencion'];
        $this->view->formRedimirC = $frmRedCupon;
        $this->view->nro = $nro;
    }
}

