<?php

class Gestor_BeneficiosController extends App_Controller_Action_Gestor
{
    
    public function init()
    {
        /* Initialize action controller here */
        parent::init();
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_redirect('/gestor');
        }
        Zend_Layout::getMvcInstance()->active = App_Controller_Action_Gestor::MENU_NAME_BENEFICIOS;
        $p = new Plugin_CsrfProtect();
        $this->view->csrf = $p->session->key;
    }

    public function indexAction()
    {
        $this->_forward('listar-beneficios');
    }

    public function listarBeneficiosAction()
    {
        //$this->view->headScript()->offsetSetFile(1, '');
        $js = sprintf("var dt = {logo : '%s'}", $this->mediaUrl . '/images/calendar.png');
        $this->view->headScript()->appendScript($js);
        $this->view->headLink()->appendStylesheet(
            $this->mediaUrl . '/js/datepicker/themes/ui-lightness/ui.all.css', 'all'
        );
        $this->view->headScript()->appendFile($this->mediaUrl . '/js/datepicker/ui/ui.core.js');
        $this->view->headScript()->appendFile($this->mediaUrl . '/js/datepicker/ui/ui.datepicker.js');
        $this->view->headScript()->appendFile($this->mediaUrl . '/js/datepicker/ui/i18n/ui.datepicker-es.js');
        $this->view->headScript()->appendFile($this->mediaUrl . '/js/gestor/filtro.beneficios.js');
        $this->view->headScript()->appendFile($this->mediaUrl . '/js/gestor/beneficio.editar.js');
        $formFiltroBeneficio = new Application_Form_FiltroBeneficios();
        $formFiltroBeneficio->addOptionsEstadoListaBeneficios();
        $this->view->formFiltroBeneficio = $formFiltroBeneficio;
    }
    
    public function buscarBeneficiosAction()
    {
        $this->_helper->layout->disableLayout();
        //$params = $this->_getAllParams();
        $nombre = $this->_getParam('nombre', '');
        $tipoBeneficio = $this->_getParam('tipo_beneficio', 0);
        $establecimiento = $this->_getParam('establecimiento', 0);
        $categoria = $this->_getParam('categoria', 0);
        $estado = $this->_getParam('estado', 0);
        
        $this->view->col = $this->_getParam('col', '');
        $this->view->ord = $this->_getParam('ord', 'DESC');
        $page = $this->_getParam('page', 1);
        $criteria = array();
        if (!empty($nombre))
            $criteria['nombre'] = $nombre;
        if ($tipoBeneficio > 0)
            $criteria['tipo_beneficio'] = $tipoBeneficio;
        if ($establecimiento > 0)
            $criteria['establecimiento'] = $establecimiento;
        if ($categoria > 0)
            $criteria['categoria'] = $categoria;
        if ($estado > 0) {
            $criteria['publicado'] = Application_Form_Beneficio::$fromEstadoBeneficio[$estado]['publicado'];
            $criteria['activo'] = Application_Form_Beneficio::$fromEstadoBeneficio[$estado]['activo'];
        }
        $beneficios = new Application_Model_Beneficio();
        $beneficios = $beneficios->getBeneficiosPaginator($criteria);
        $beneficios->setCurrentPageNumber($page);
        
        $nroPorPage = $beneficios->getItemCountPerPage();
        $nroPage = $beneficios->getCurrentPageNumber();
        $nroReg = $beneficios->getCurrentItemCount();
        
        $this->view->mostrando = "Mostrando ".
            ' '.(($nroPage-1)*$nroPorPage + 1).
            ' - '.((($nroPage-1)*$nroPorPage) + $nroReg).
            ' de '.$beneficios->getTotalItemCount();
        $this->view->beneficios = $beneficios;
        $this->view->nroregistros = "Registros listados : ".$nroReg;
    }

    public function nuevoBeneficioAction()
    {
//        var_dump(ini_get('upload_max_filesize'));
//        var_dump(ini_get('post_max_size'));
        $js = sprintf(
            'var dt = {logo : "%s"}, nbfields = %s, deleteLogo = "%s", idSorteoResultado = %s
                , idSorteoDisponibles = %s, tipBen_idConcurso = %s;',
            $this->mediaUrl . '/images/calendar.png', 
            15, 
            $this->mediaUrl . '/images/delete.jpg',
            $this->config->categoria_id->sorteo->resultado,
            $this->config->categoria_id->sorteo->disponible,                
            $this->config->tipo_beneficio_id->concurso
        );
        $this->view->headScript()->appendScript($js);
        $alto = empty($this->config->catalogo->pagina->alto)? 1120:$this->config->catalogo->pagina->alto;
        $jsA = sprintf('var apmaximo = %s;', $alto - 220);
        $this->view->headScript()->appendScript($jsA);
        $maxfilesize = 
            empty($this->config->beneficios->file->maxsize) ? "1048576" : $this->config->beneficios->file->maxsize;
        $maxfileinfosize = 
            empty($this->config->beneficios->file->maxsize) ? "5242880" : 
            $this->config->beneficios->file->info->maxsize;
        $jsF = sprintf('var maxPDFsize = %s , maxPDFinfosize = %s;', $maxfilesize, $maxfileinfosize);
        $this->view->headScript()->appendScript($jsF);
        $this->view->headLink()
            ->appendStylesheet($this->mediaUrl . '/js/datepicker/themes/ui-lightness/ui.all.css', 'all');
        $this->view->headScript()->appendFile($this->mediaUrl . '/js/datepicker/ui/ui.core.js');
        $this->view->headScript()->appendFile($this->mediaUrl . '/js/datepicker/ui/ui.datepicker.js');
        $this->view->headScript()->appendFile($this->mediaUrl . '/js/datepicker/ui/i18n/ui.datepicker-es.js');
        $this->view->headScript()->appendFile($this->mediaUrl . '/js/gestor/jquery.cleditor.min.js');
        $this->view->headScript()->appendFile($this->mediaUrl . '/js/gestor/beneficio.js');
        $this->view->headScript()->appendFile($this->mediaUrl . '/js/gestor/beneficio.logo.uploader.js');
        $this->view->headScript()->appendFile($this->mediaUrl . '/js/gestor/beneficio.pdf.uploader.js');
        $this->view->headScript()->appendFile($this->mediaUrl . '/js/gestor/beneficio.pdf_info.uploader.js');
        $this->viewImgPhoto = '';
        
        $hasDetalle = false;
        $hasPDF = false;
        $formBeneficio = new Application_Form_Beneficio();

        $formBeneVersion = new Application_Form_BeneficioVersion();
        $formsDetalle = array();
        $formBeneficio->fillOpcionEstados();
        $formBeneficio->setDestacado();
        $formBeneficio->setEstado();
        $formBeneficio->setChapitaColor();
        
        if ($this->getRequest()->isPost()) {
            $allParams = $this->getRequest()->getParams();
            //->Ticket 20162
            $tipoRedencion=$allParams['tipo_redencion'];
            $formBeneficio->setDefaults($allParams);
            $formBeneVersion->setDefaults($allParams);
            if (isset($allParams['detTipDesc'])) {
                $gMaximoPorSubscriptor=0;
                $gStockTotal=0;
                foreach ($allParams['detTipDesc'] as $key=>$val) {
                    $formDetalle = new Application_Form_DetalleBeneficio();
                    $formDetalle->add_hiddenId();
                    $formDetalle->add_TipoRedencion($tipoRedencion);
                    $detalle[$key]['d_codigo'] = $val["d_codigo"];
                    if ($tipoRedencion==1) {
                        $detalle[$key]['d_porcentaje_descuento']  = $val["d_porcentaje_descuento"];
                    } else {
                        $detalle[$key]['d_precio_regular']  = $val["d_precio_regular"];
                        $detalle[$key]['d_precio_suscriptor']  = $val["d_precio_suscriptor"];                        
                    }
                    //$detalle[$key]['d_descuento']    = 0.00 + $val["d_descuento"];
                    $detalle[$key]['d_descripcion']  = $val["d_descripcion"];
                    $cantD = $detalle[$key]['d_cantidad']  = isset($val["d_cantidad"])?$val["d_cantidad"]:0;
                    $maxSusD =$detalle[$key]['d_maximo_por_suscriptor'] = isset($val["d_maximo_por_suscriptor"])?
                        $val["d_maximo_por_suscriptor"]:0;
                    $gMaximoPorSubscriptor=$gMaximoPorSubscriptor+$maxSusD;
                    $gStockTotal=$gStockTotal+$cantD;
                    $detalle[$key]['d_hidden']     = (empty($val["d_hidden"]))?"":($val["d_hidden"]);
                    $formDetalle->setDefaults($detalle[$key]);
                    $formsDetalle[] = $formDetalle;
                }
            }
            
            if (isset($allParams['categorias_disponibles'])) {
                $formBeneficio->fillCategorias('categorias_disponibles', $allParams['categorias_disponibles']);
            }
            if (isset($allParams['categorias_seleccionadas']))
                $formBeneficio->fillCategorias('categorias_seleccionadas', $allParams['categorias_seleccionadas']);
            $formBeneficio->setChapitaColor($allParams['chapita_color']);
            
            if (in_array($this->config->categoria_id->sorteo->resultado, $allParams['categorias_seleccionadas'])) {
                $hasPDF = true;
                $formBeneficio->uploadPDF();
            } else {
                $formBeneficio->uploadPDF(false);
            }            
            $isValidBeneficio = $formBeneficio->isValid($allParams);
//            var_dump($formBeneficio->getErrors());
//            var_dump($allParams);
            if ($isValidBeneficio) {
                try {
                    $beneficio = new Application_Model_Beneficio();
                    $db = $beneficio->getAdapter();
                    $db->beginTransaction();
                    
                    $arrMax = $beneficio->getMaxBeneficio();
                    $utilfile = $this->_helper->getHelper('UtilFiles');
                    $nuevoNombre = $utilfile->_renameFile($formBeneficio, 'path_logo','promo',
                            array("slug"=>$this->view->TextToUrl($allParams['titulo']), "id"=>($arrMax["id"]+1)) );
                    
                    
                    $valuesBeneficio = $formBeneficio->getValues();
                    $valuesBeneficio['path_logo'] = $nuevoNombre;
                    
                    $catSelecionadas = $valuesBeneficio['categorias_seleccionadas'];
                    unset($valuesBeneficio['categorias_seleccionadas']);
                    unset($valuesBeneficio['categorias_disponibles']);

                    //SETEO DE VALORES RESPECTO AL ESTADO DEL BENEFICIO
                    $valuesBeneficio['publicado'] = Application_Form_Beneficio
                        ::$fromEstadoBeneficio[$valuesBeneficio['estado']]['publicado'];
                    $valuesBeneficio['activo'] = Application_Form_Beneficio
                        ::$fromEstadoBeneficio[$valuesBeneficio['estado']]['activo'];
                    unset($valuesBeneficio['estado']);
                    
                    
                    $valuesBeneficio['creado_por']
                        = $valuesBeneficio['actualizado_por'] = $this->auth['usuario']->id;
                    if ($valuesBeneficio['sin_limite_por_suscriptor'] == 1) {
                        unset($valuesBeneficio['maximo_por_subscriptor']);
                    } elseif ($valuesBeneficio['sin_limite_por_suscriptor'] == 2) {
//                        if (!empty($gMaximoPorSubscriptor)) {
//                            $valuesBeneficio['maximo_por_subscriptor']=$gMaximoPorSubscriptor;
//                        }
                    } else {
                        $valuesBeneficio['sin_limite_por_suscriptor'] = 0;
//                        if (!empty($gMaximoPorSubscriptor)) {
//                            $valuesBeneficio['maximo_por_subscriptor']=$gMaximoPorSubscriptor;
//                        }
                    }
                    $date = date('Y-m-d H:i:s');
                    $valuesBeneficio['fecha_registro'] = $date;
                    
                    $establecimiento =
                        Application_Model_Establecimiento::getEstablecimiento($valuesBeneficio['establecimiento_id']);
                    if ($valuesBeneficio['telefono_info_establecimiento'] == 1) {
                        $valuesBeneficio['telefono_info'] = $establecimiento['telefono_contacto'];
                    }
                    if ($valuesBeneficio['email_info_establecimiento'] == 1) {
                        $valuesBeneficio['email_info'] = $establecimiento['email_contacto'];
                    }
                    $tipoBeneficio = 
                        Application_Model_TipoBeneficio::getTipoBeneficio($valuesBeneficio['tipo_beneficio_id']);
                    $valuesBeneficio['slug'] = $this->_crearSlug(
                        $tipoBeneficio['nombre'], 
                        $establecimiento['nombre'], 
                        $valuesBeneficio['titulo']
                    );
                    
                    unset($valuesBeneficio['email_info_establecimiento']);
                    unset($valuesBeneficio['telefono_info_establecimiento']);
                    $pdfName = $valuesBeneficio['pdf_name'];
                    $pdfInfoName = $valuesBeneficio['pdf_info_name'];
                    unset($valuesBeneficio['pdf_name']);
                    unset($valuesBeneficio['pdf_resultado']);
                    unset($valuesBeneficio['pdf_info']);
                    unset($valuesBeneficio['pdf_info_name']);
                    //DESTACADOS
                    $valuesBeneficio['es_destacado'] = Application_Form_Beneficio
                        ::$fromDestacado[$valuesBeneficio['destacado']]['es_destacado'];
                    $valuesBeneficio['es_destacado_principal'] = Application_Form_Beneficio
                        ::$fromDestacado[$valuesBeneficio['destacado']]['es_destacado_principal'];
                    unset($valuesBeneficio['destacado']);
                    $valuesBeneficio['informacion_adicional'] = trim($valuesBeneficio['informacion_adicional']);
                    
                    $version = new Application_Model_BeneficioVersion();
                    $valuesBeneficioVersion = $formBeneVersion->getValues();
                    $beneficioLastId = $beneficio->insert($valuesBeneficio);
                    Application_Model_Establecimiento
                    ::updateNumeroBeneficiosEstablecimiento($valuesBeneficio['establecimiento_id']);
                    
                    //CODIGO IDENTIFICADOR DEL BENEFICIO
                    $prefix = array(1 => '0000', 2 => '000', 3 => '00', 4 => '0', 5 => '');
                    $zero = $prefix[strlen('' . $beneficioLastId)];
                    $codigo = 'B' . date('Ym') . $zero . $beneficioLastId;
                    $where = $db->quoteInto('id = ?', $beneficioLastId);
                    $beneficio->update(array('codigo' => $codigo), $where);

                    //CATEGORIAS
                    $categoriasBeneficio = new Application_Model_CategoriaBeneficio();
                    foreach ($catSelecionadas as $categoria) {
                        $categoriasBeneficio
                            ->insert(array('beneficio_id' => $beneficioLastId, 'categoria_id' => $categoria));
                        if ($categoria == $this->config->categoria_id->sorteo->resultado) {
                            $name =  "" . time(). "_" . $beneficioLastId . ".pdf";
                            $path = $this->config->paths->elementsSorteoRoot;
                            rename($path . "temp/" . $pdfName, $path . $name);
                            $beneficio->update(array('pdf_file' => $name), $where);
                        }
                    }
                    
                    if ($pdfInfoName != '') {
                        $name =  "" . time(). "i_" . $beneficioLastId . ".pdf";
                        $path = $this->config->paths->elementsSorteoRoot;
                        rename($path . "temp/" . $pdfInfoName, $path . $name);
                        $beneficio->update(array('pdf_info_file' => $name), $where);
                    } else {
                        unset($valuesBeneficio['pdf_info_descrip']);
                    }
                    //BENEFICIOS VERSION
                    $valuesBeneficioVersion['beneficio_id'] = $beneficioLastId;
//                    if (!$hasDetalle) {
                        if ($valuesBeneficio['sin_stock'] == 1) {
                            unset($valuesBeneficioVersion['stock']);
                            $valuesBeneficioVersion['stock_actual'] = 0;
                        } else {
                            if ($valuesBeneficio['sin_stock']==2) {
                                $valuesBeneficioVersion['stock']=$gStockTotal;
                            }
                            $valuesBeneficioVersion['stock_actual'] = $valuesBeneficioVersion['stock'];
                        }
//                    } else {
//                        $valuesBeneficioVersion['stock_actual'] = $stockTotal;
//                        $valuesBeneficioVersion['stock'] = $stockTotal;
//                    }
                    $valuesBeneficioVersion['fecha_inicio_vigencia']
                        = date_format(
                            DateTime::createFromFormat('d/m/Y', $valuesBeneficioVersion['fecha_inicio_vigencia']), 
                            'Y-m-d'
                        );
                    $valuesBeneficioVersion['fecha_fin_vigencia']
                        = date_format(
                            DateTime::createFromFormat('d/m/Y', $valuesBeneficioVersion['fecha_fin_vigencia']),
                            'Y-m-d'
                        );
                    $valuesBeneficioVersion['fecha_inicio_publicacion']
                        = date_format(
                            DateTime::createFromFormat('d/m/Y', $valuesBeneficioVersion['fecha_inicio_publicacion']), 
                            'Y-m-d'
                        );
                    $valuesBeneficioVersion['fecha_fin_publicacion']
                        = date_format(
                            DateTime::createFromFormat('d/m/Y', $valuesBeneficioVersion['fecha_fin_publicacion']),
                            'Y-m-d'
                        );
                    $valuesBeneficioVersion['fecha_registro'] = $date;
                    $valuesBeneficioVersion['activo'] = 1;

                    $version->insert($valuesBeneficioVersion);
                    
                    //DETALLE BENEFICIO
                    $objDetalle = new Application_Model_DetalleTipoPromocion();

                    foreach ($formsDetalle as $form) {
                        $values = $form->getValues();
                        if ($form->isValid($values)) {
                            if ($valuesBeneficio['sin_limite_por_suscriptor'] == 2) {
                                $datoInsert['maximo_por_suscriptor']=$values['d_maximo_por_suscriptor'];
                            }
                            if ($valuesBeneficio['sin_stock'] == 2) {
                                $datoInsert['cantidad']=$values['d_cantidad'];
                                $datoInsert['stock_actual']=$values['d_cantidad'];
                            }
                            $datoInsert['beneficio_id']=$beneficioLastId;
                            $datoInsert['codigo']=$values['d_codigo'];
                            //$datoInsert['descuento']=$values['d_descuento'];
                            if ($tipoRedencion==1) {
                                $datoInsert['porcentaje_descuento']=$values['d_porcentaje_descuento'];
                            } else {
                                $datoInsert['precio_regular']=$values['d_precio_regular'];
                                $datoInsert['precio_suscriptor']=$values['d_precio_suscriptor'];
                                if($datoInsert['precio_regular'] <= $datoInsert['precio_suscriptor']){
                                    $messageErr = 'Error: Precio suscriptor no puede ser mayor que precio regular.';
                                    throw new Exception($messageErr);                                    
                                }
                                $datoInsert['descuento']=($datoInsert['precio_regular']-$datoInsert['precio_suscriptor']);
                            }
                            $datoInsert['descripcion']=$values['d_descripcion'];
                            $objDetalle->insert($datoInsert);
                        } else {
                            throw new Exception('Hubo un error en la generación de detalles del tipo promoción');
                        }
                    }
                    $db->commit();
                    
                    Application_Model_Beneficio::updateIndexBeneficios($beneficioLastId);
                    @$beneficio->getDestacadosPortada(false);
                    @$beneficio->getMainDestacado(false);

                    $this->getMessenger()->success('El beneficio fue agregado satisfactoriamente');
                    $this->_redirect('gestor/beneficios');
                } catch (Exception $e) {
                    $db->rollBack();
                    $this->getMessenger()->error($e->getMessage());
                }
            }
        } else {
            $formBeneficio->fillCategorias('categorias_disponibles');
        }
        $this->view->formsDetalle = $formsDetalle;
        $this->view->formBeneficio = $formBeneficio;
        $this->view->formBeneVersion = $formBeneVersion;

    }

    public function cargafotoAction()
    {
        $img = 'path_logo';
        $r = $this->getRequest();
        if ($r->isPost()) {
            $session = $this->getSession();
            if ($session->__isset("tmp_img")) {
                @unlink($session->__get("tmp_img"));
            }
            $tamanomax = $r->__get("filesize");
            $tamano = $_FILES[$img]['size'];
            if ($tamano <= $tamanomax) {
                $utilfile = $this->_helper->getHelper('UtilFiles');
                $archivo = $_FILES[$img]['name'];
                $tipo = $utilfile->_devuelveExtension($archivo);
                $nombrearchivo = "elements/images/beneficios/temp/temp_" . time() . "." . $tipo;
                $session->__set("tmp_img", $nombrearchivo);
                move_uploaded_file($_FILES[$img]['tmp_name'], $nombrearchivo);
                $imgx = new ZendImage();
                $imgx->loadImage(APPLICATION_PATH . "/../public/" . $nombrearchivo);
                echo "success|<img height='115px' width='210px' style='height:115px;width:210px'
                    src='" . SITE_URL . '/' . $nombrearchivo . "' />";
            } else {
                echo "error|Tamaño de archivo sobrepasa el limite Permitido";
            }
        } else {
            echo "error|ERROR";
        }
        die();
    }

    public function cargafoto2Action()
    {
        $img = 'path_logo_preview';
        $r = $this->getRequest();
        if ($r->isPost()) {
            $inputdesc = $r->__get("input-desc");
            $tamanomax = $r->__get("filesize");
            $tamano = $_FILES[$img]['size'];
            if ($tamano <= $tamanomax) {
                $utilfile = $this->_helper->getHelper('UtilFiles');
                $archivo = $_FILES[$img]['name'];
                $tipo = $utilfile->_devuelveExtension($archivo);
                $nombrearchivo = "elements/images/sociales/temp/temp_" . time() . "." . $tipo;
                move_uploaded_file($_FILES[$img]['tmp_name'], $nombrearchivo);
                $imgx = new ZendImage();
                $imgx->loadImage(APPLICATION_PATH . "/../public/" . $nombrearchivo);
                /* echo "success|<img height='115px' width='210px' style='height:115px;width:210px'
                  src='" . SITE_URL . '/' . $nombrearchivo . "' />"; */
                echo "success|$nombrearchivo|$inputdesc";
            } else {
                echo "error|Tamaño de archivo sobrepasa el limite Permitido";
            }
        } else {
            echo "error|ERROR";
        }
        die();
    }

    public function cargaPdfAction()
    {
        $img = 'pdf_resultado';
        $r = $this->getRequest();
        if ($r->isPost()) {
            $session = $this->getSession();
            if ($session->__isset("tmp_pdf")) {
                @unlink($session->__get("tmp_pdf"));
            }
//            $tamanomax = $r->__get("filesize");
        $tamanomax = 
            empty($this->config->beneficios->file->maxsize) ? "1048576" : $this->config->beneficios->file->maxsize;
//            $tamanomax = $this->config->beneficios->file->maxsize;
            $tamano = $_FILES[$img]['size'];
            if ($tamano <= $tamanomax) {
                $nombre = "temp_" . time() . ".pdf";
//                $path = "elements/beneficios/sorteos/temp/" . $nombre;
                $path = $this->config->paths->elementsSorteoRoot . 'temp/' . $nombre;
                $session->__set("tmp_pdf", $path);
                move_uploaded_file($_FILES[$img]['tmp_name'], $path);
                echo "success|".$nombre;
            } else {
                echo "error|Tamaño de archivo sobrepasa el limite Permitido";
            }
        } else {
            echo "error|ERROR";
        }
        die();
    }
    
    public function cargaPdfInfoAction()
    {
        $img = 'pdf_info';
        $r = $this->getRequest();
        if ($r->isPost()) {
            $session = $this->getSession();
            if ($session->__isset("tmp_pdf2")) {
                @unlink($session->__get("tmp_pdf2"));
            }
//            $tamanomax = $r->__get("filesize");
        $tamanomax = 
            empty($this->config->beneficios->file->maxsize) ? "1048576" : $this->config->beneficios->file->maxsize;
//            $tamanomax = $this->config->beneficios->file->maxsize;
            $tamano = $_FILES[$img]['size'];
            if ($tamano <= $tamanomax) {
                $nombre = "temp_" . time() . "i.pdf";
//                $path = "elements/beneficios/sorteos/temp/" . $nombre;
                $path = $this->config->paths->elementsSorteoRoot . 'temp/' . $nombre;
                $session->__set("tmp_pdf2", $path);
                move_uploaded_file($_FILES[$img]['tmp_name'], $path);
                echo "success|".$nombre;
            } else {
                echo "error|Tamaño de archivo sobrepasa el limite Permitido";
            }
        } else {
            echo "error|ERROR";
        }
        die();
    }
    
    public function eliminarfotoAction()
    {
        if ($this->_request->isPost()) {
            $img = $this->_getParam("img");
            @unlink($img);
        }
        die();
    }

    private function _crearSlug($tipo, $establecimiento, $titulo)
    {
        $slugFilter = new App_Filter_Slug();
        $slug = $slugFilter->filter($tipo . ' ' . $establecimiento . ' ' . $titulo);
        return $slug;
    }
    
    public function obtenerBeneficioAction() 
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
//        $params = $this->_getAllParams();
        $config = $this->getConfig();
        $this->view->elementsUrl = $config->app->elementsUrl;
        $this->view->mediaUrl = $config->app->mediaUrl;
        $this->view->sufixlittle = $config->beneficios->logo->sufix->little;
        
        $id = $this->_getParam('id', false);
        $tiPromoB = array();
        if (!$id) {
            echo "<strong>Debe seleccionar un beneficio de la lista.</strong>";
        } else {
            $obj = new Application_Model_Beneficio();
            $beneficio = $obj->getBeneficioInfoById($id);
            
            $formBeneficio = new Application_Form_Beneficio();
            $formBeneficioVersion = new Application_Form_BeneficioVersion();
            $formBeneficio->fillOpcionEstados(
                Application_Form_Beneficio
                ::$estadosBeneficio[$beneficio['publicado']][$beneficio['activo']]
            );
            $formBeneficio->setEstado(
                Application_Form_Beneficio
                ::$estadosBeneficio[$beneficio['publicado']][$beneficio['activo']]
            );
            
            $formBeneficio->setDestacado(
                Application_Form_Beneficio
                ::$destacados[$beneficio['es_destacado']][$beneficio['es_destacado_principal']]
            );
            
            if ($beneficio['publicado'] == 1 && $beneficio['activo'] == 1) {
                $this->_helper->viewRenderer('editar_beneficio_vigente');
                $beneficio['version']['fecha_fin_vigencia'] 
                    = date('d/m/Y', strtotime($beneficio['version']['fecha_fin_vigencia']));
                $formBeneficio->fillOpcionEstados(3);
                
                //Edicion de los Detalle de Tipo Promocion por Beneficio
                $objTipPromoB = new Application_Model_DetalleTipoPromocion();
                $flagT = $objTipPromoB->getExisteDetalleTipPromo($id);
                if ($flagT) {
                    $tiPromoB = Application_Model_DetalleTipoPromocion
                    ::getDetalleTipoPromocionByBeneficioId($id);
                    //var_dump($tiPromoB);
                }
                
            } elseif ($beneficio['publicado'] == 0) {
                $this->_helper->viewRenderer('editar_beneficio_no_vigente');
                $beneficio['version']['fecha_inicio_vigencia'] 
                    = date('d/m/Y', strtotime($beneficio['version']['fecha_inicio_vigencia']));
                $beneficio['version']['fecha_fin_vigencia'] 
                    = date('d/m/Y', strtotime($beneficio['version']['fecha_fin_vigencia']));
            } elseif ($beneficio['publicado'] == 1 && $beneficio['activo'] == 0) {
                echo "<strong>El Beneficio seleccionado no es editable</strong>";
            }
            $formBeneficio->fillCategorias(
                'categorias_seleccionadas', 
                array_keys($beneficio['categorias'])
            );
            $formBeneficio->fillCategorias(
                'categorias_disponibles', 
                array_keys($beneficio['categorias']),
                false
            );
            $formBeneficio->setDefaults($beneficio);
            $formBeneficioVersion->setDefaults($beneficio['version']);
            
            $this->view->tipPromoBen = $tiPromoB;
            $this->view->formBeneficio = $formBeneficio;
            $this->view->formBeneficioVersion = $formBeneficioVersion;
            $this->view->beneficio = $beneficio;
            $this->render();
        }
    }
    
    public function obtenerEstablecimientoInfoAction () 
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $id = $this->_getParam('id', false);
        $est = new Application_Model_Establecimiento();
        $est = $est->getEstablecimiento($id);
        $info = array ('email' => $est['email_contacto'], 'telefono' => $est['telefono_contacto']);
        $this->_response->appendBody(Zend_Json::encode($info));
    }
    
    public function obtenerStockMinimoAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $idBen = $this->_getParam('idBen', false);
        $cant = $this->_getParam('cant', false);
        $idTipDesc = $this->_getParam('idTipDesc', false);
        $est = new Application_Model_Cupon();
        $est = $est->getCantCuponesByBenefAndCantGestorGenAndTipDesc($idBen, $cant, $idTipDesc);
        $info = array('valor' => $est["valor"], 'cant' => $est["cant"]);
//        var_dump($info);exit;
        $this->_response->appendBody(Zend_Json::encode($info));
    }
    
    public function obtenerCuponMinSuscriptorAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $idBen = $this->_getParam('idBen', false);
        $cant = $this->_getParam('cant', false);
        $est = new Application_Model_Cupon();
        $est = $est->getCantCuponesBySucripGen($idBen, $cant);
        $info = array('valor' => $est["valor"], 'cant' => $est["cant"]);
//        var_dump($info);exit;
        $this->_response->appendBody(Zend_Json::encode($info));
    }
    
    public function obtenerCuponMaxSuscriptorTipdescAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $idBen = $this->_getParam('idBen', false);
        $cant = $this->_getParam('cant', false);
        $idTipDesc = $this->_getParam('idTipDesc', false);
        $est = new Application_Model_Cupon();
        $est = $est->getCantCuponesBySucripGen($idBen, $cant, $idTipDesc);
        $info = array('valor' => $est["valor"], 'cant' => $est["cant"]);
//        var_dump($info);exit;
        $this->_response->appendBody(Zend_Json::encode($info));
    }

    public function editarBeneficioAjaxAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $params = $this->_getAllParams();
        $reponse = array(
            'result' => false,
            'message' => 'Err'
        );
        if ($this->getRequest()->isPost()) {
            $obj = new Application_Model_Beneficio();
            $beneficio = $obj->getBeneficioInfoById($params['id']);
            $last = $beneficio;
            if ($beneficio['publicado'] == 1 && $beneficio['activo'] == 1 && $params['vigente'] == 1
            ) {
                $b = new Application_Model_Beneficio();
                $db = $b->getAdapter();
                try {
                    $db->beginTransaction();
                    if (!($params['estado'] == 0)) {
                        $where = $db->quoteInto('id = ?', $beneficio['id']);
                        $b->update(
                            array(
                                'activo' => Application_Form_Beneficio
                                ::$fromEstadoBeneficio[$params['estado']]['activo']
                            ), 
                            $where
                        );
                        $b->update(
                            array(
                                'publicado' => Application_Form_Beneficio
                                ::$fromEstadoBeneficio[$params['estado']]['publicado']
                            ), 
                            $where
                        );
                    } else {
                        //var_dump($params); //exit;
                        
                        $objBenefVer = new Application_Model_BeneficioVersion();
                        if (!empty($params['stock'])) {
                            //aplicamos el calculo del nuevo stock del beneficio
                            $totalConsu = $objBenefVer->getDevolverStockCalConsumido($beneficio['id']);
                            //var_dump($params['stock'], $totalConsu);
                            if ($params['stock'] > $totalConsu) {
                                $nStock = $params['stock'] - $totalConsu;
                                $dataVersion = array(
                                    'fecha_inicio_vigencia'
                                    => $beneficio['version']['fecha_inicio_vigencia'],
                                    'fecha_fin_vigencia' => date_format(
                                        DateTime::createFromFormat(
                                            'd/m/Y', 
                                            $params['fecha_fin_vigencia']
                                        ), 
                                        'Y-m-d'
                                    ),
                                    'stock_actual' => $nStock,
                                    'stock' => $nStock
                                );
                                $b->nuevaVersion($beneficio['id'], $dataVersion);
                            } else {
                                $messageErr = 'El stock ingresado no cumple con las condiciones';
                                throw new Exception($messageErr);
                            }
                        } else {
                            //el beneficio tiene detalle (tipos de descuento)
                            if (!empty($params['d_cantidad']) && !empty($params['h_codigo'])) {
                                $objDeTipProm = new Application_Model_DetalleTipoPromocion();
                                $totNewStk = 0;
                                foreach ($params['d_cantidad'] as $inx => $valCant) {
                                    $code = $params['h_codigo'][$inx];
                                    $totCon = $objDeTipProm->getStockCalConsumidoByTipo(
                                        $beneficio['id'], $code
                                    );
                                    $valCant = !empty($valCant)?$valCant:0;
                                    if ($valCant > $totCon) {
                                        $newStk = $valCant - $totCon;
                                        $totNewStk += $newStk;
                                        $dataDetaTipoP = array(
                                            'stock_actual' => $newStk,
                                            'cantidad' => $newStk,
                                            'beneficio_id' => $beneficio['id'],
                                            'codigo' => $code
                                        );
                                        $objDeTipProm->newVersDetailTip(
                                            $dataDetaTipoP
                                        );
                                    } else {
                                        $messageErr = 'El stock ingresado no cumple con las condiciones';
                                        throw new Exception($messageErr);
                                    }
                                }
                                //Calculamos el nuevo registro version
                                $totalConsu = $objBenefVer->getDevolverStockCalConsumido($beneficio['id']);
                                if ($totNewStk > $totalConsu) {
                                    $nStock = $totNewStk - $totalConsu;
                                    $dataVersion = array(
                                        'fecha_inicio_vigencia'
                                        => $beneficio['version']['fecha_inicio_vigencia'],
                                        'fecha_fin_vigencia' => date_format(
                                            DateTime::createFromFormat(
                                                'd/m/Y', 
                                                $params['fecha_fin_vigencia']
                                            ), 
                                            'Y-m-d'
                                        ),
                                        'stock_actual' => $nStock,
                                        'stock' => $nStock
                                    );
                                    $b->nuevaVersion($beneficio['id'], $dataVersion);
                                } else {
                                    $messageErr = 'El stock ingresado no cumple con las condiciones';
                                    throw new Exception($messageErr);
                                }
                            }
                        }
                        $where = $db->quoteInto('id = ?', $beneficio['id']);
                        $b->update(
                            array(
                                'es_destacado' => Application_Form_Beneficio
                                ::$fromDestacado[$params['destacado']]['es_destacado'],
                                'es_destacado_principal' => Application_Form_Beneficio
                                ::$fromDestacado[$params['destacado']]['es_destacado_principal']
                            ),
                            $where
                        );
                    }
                    $db->commit();
                    Application_Model_Beneficio::updateIndexBeneficios($beneficio['id'], $last);
//                    @$obj->getDestacadosPortada(false);
//                    @$obj->getMainDestacado(false);
                    $reponse = array(
                        'result' => true,
                        'message' => 'El beneficio se actualizó correctamente'
                    );
//                    $this->_response->appendBody(Zend_Json::encode($reponse));
                } catch (Exception $e) {
                    $db->rollBack();
                    $reponse = array(
                        'result' => false,
                        'message' => $e->getMessage()
                    );
//                    $this->_response->appendBody(Zend_Json::encode($reponse));
                }
            } else {
                $reponse = array(
                    'result' => false,
                    'message' => 'El beneficio seleccionado no está vigente'
                );
            }
        } else {
            $reponse = array(
                'result' => false,
                'message' => 'Bad request'
            );
        }
        $this->_response->appendBody(Zend_Json::encode($reponse));
    }
    
    public function vistaPreviaBeneficioAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $obj = new Application_Model_Beneficio();
        $id = $this->_getParam('id', false);
        $beneficio = $obj->getBeneficioInfoById($id);
        if (!$beneficio) {
            $this->getMessenger()->error('Debe seleccionar un beneficio valido');
            $this->_redirect('gestor/beneficios');
        } else {
            $idSorteoDisponible = $this->getConfig()->categoria_id->sorteo->resultado;
            $objcatB = new Application_Model_CategoriaBeneficio();
            $beneficio['sorteo'] = $objcatB->validCategoriaSorteoByBenef($id, $idSorteoDisponible);
            $config = $this->getConfig();
            $this->view->sufixlittle = $config->beneficios->logo->sufix->little;
            $this->view->beneficio = $beneficio;
            if(empty($beneficio['sin_limite_por_suscriptor']) ) 
                $this->view->maximo_permitido = $beneficio['maximo_por_subscriptor'];
            else $this->view->maximo_permitido = $this->getConfig()->beneficios->detalle->nrocupones;
        }
        $this->render();
    }
    
    public function editarBeneficioAction()
    {
        $config = $this->getConfig();
        $js = sprintf(
            'var dt = {logo : "%s"}, nbfields = %s, deleteLogo = "%s", idSorteoResultado = %s
                , idSorteoDisponibles = %s, tipBen_idConcurso = %s;', 
            $this->mediaUrl . '/images/calendar.png', 
            15, 
            $this->mediaUrl . '/images/delete.jpg',
            $this->config->categoria_id->sorteo->resultado,
            $this->config->categoria_id->sorteo->disponible,                
            $this->config->tipo_beneficio_id->concurso
        );
        $this->view->categoria_id_pdf = $this->config->categoria_id->sorteo->resultado;
        $this->view->categoria_id_sorteoDisponible = $this->config->categoria_id->sorteo->disponible;
        $this->view->tipBen_idConcurso = $this->config->tipo_beneficio_id->concurso;
//        $this->view->headScript()->offsetSetFile(1, '');

        $this->view->headScript()->appendScript($js);        
        
        $alto = empty($this->config->catalogo->pagina->alto)? 1120:$this->config->catalogo->pagina->alto;
        $jsA = sprintf('var apmaximo = %s;', $alto - 220);
        $this->view->headScript()->appendScript($jsA);
        $maxfilesize = 
            empty($this->config->beneficios->file->maxsize) ? "1048576" : $this->config->beneficios->file->maxsize;
        $maxfileinfosize = 
            empty($this->config->beneficios->file->maxsize) ? "5242880" : 
            $this->config->beneficios->file->info->maxsize;
        $jsF = sprintf('var maxPDFsize = %s , maxPDFinfosize = %s;', $maxfilesize, $maxfileinfosize);
        $this->view->headScript()->appendScript($jsF);
        $this->view->headLink()
            ->appendStylesheet($this->mediaUrl . '/js/datepicker/themes/ui-lightness/ui.all.css', 'all');
        $this->view->headScript()->appendFile($this->mediaUrl . '/js/datepicker/ui/ui.core.js');
        $this->view->headScript()->appendFile($this->mediaUrl . '/js/datepicker/ui/ui.datepicker.js');
        $this->view->headScript()->appendFile($this->mediaUrl . '/js/datepicker/ui/i18n/ui.datepicker-es.js');
        $this->view->headScript()->appendFile($this->mediaUrl . '/js/gestor/jquery.cleditor.min.js');
        $this->view->headScript()->appendFile($this->mediaUrl . '/js/gestor/beneficio.js');
        $this->view->headScript()->appendFile($this->mediaUrl . '/js/gestor/beneficio.logo.uploader.js');
        $this->view->headScript()->appendFile($this->mediaUrl . '/js/gestor/beneficio.pdf.uploader.js');
        $this->view->headScript()->appendFile($this->mediaUrl . '/js/gestor/beneficio.pdf_info.uploader.js');
        $this->view->imgPhoto = '';
        $this->_helper->viewRenderer('editar_beneficio_no_vigente');
        
        $formBeneficio = new Application_Form_Beneficio();
        $formBeneVersion = new Application_Form_BeneficioVersion();
        $formsError=$formsDetalle = array();
                
        $objCupon = new Application_Model_Cupon();
        $obj = new Application_Model_Beneficio();
        $id = $this->_getParam('id', false);
        $objCupon->setBeneficio_id($id);
        $cantCuponesByBenef=$objCupon->getCantCuponesByBenef();
        $beneficio = $obj->getBeneficioInfoById($id);
        $last = $beneficio;
        $detalles = Application_Model_DetalleTipoPromocion::getDetalleTipoPromocionByBeneficioIdCuponGenerado($id);
        $tipoRedencion=$beneficio['tipo_redencion'];
        foreach ($detalles as $detalle) {
            $values['d_codigo'] = $detalle['codigo'];
            $values['d_descripcion'] = $detalle['descripcion'];            
            if ($tipoRedencion==1) {
                $values['d_porcentaje_descuento']  = $detalle["porcentaje_descuento"];
            } else {
                $values['d_precio_regular']  = 
                    ($detalle["precio_regular"]=='0.00' || empty($detalle["precio_regular"]))?'P Regul'
                        :$detalle["precio_regular"];
                $values['d_precio_suscriptor']  = 
                    ($detalle["precio_suscriptor"]=='0.00' || empty($detalle["precio_suscriptor"]))?'P Susc'
                        :$detalle["precio_suscriptor"];
                $values['d_descuento'] = $detalle['descuento'];
            }
            $values['d_cantidad'] = ($detalle['cantidad']==0)?'Stock':$detalle['cantidad'];
            $values['d_maximo_por_suscriptor'] = ($detalle['maximo_por_suscriptor']==0)?'Max S.':
                $detalle['maximo_por_suscriptor'];
            $values['d_hidden'] = $detalle['id'];
            $values['d_cupon'] = $detalle['cupon'];
            $form = new Application_Form_DetalleBeneficio();
            $form->add_TipoRedencion($tipoRedencion);
            $form->add_hiddenId();
            $form->add_cupon();
            $form->setElementsBelongTo('detTipDesc['.$detalle['id'].']');
            $form->setDefaults($values);
            $formsDetalle[] = $form;
            $formsError[] = $form;
        }
        
        $formBeneficio->fillOpcionEstados(
            Application_Form_Beneficio::$estadosBeneficio[$beneficio['publicado']][$beneficio['activo']]
        );
        $formBeneficio->setDestacado(
            Application_Form_Beneficio::$destacados[$beneficio['es_destacado']][$beneficio['es_destacado_principal']]
        );
        $formBeneficio->setEstado(
            Application_Form_Beneficio::$estadosBeneficio[$beneficio['publicado']][$beneficio['activo']]
        );
        $formBeneficio->setChapitaColor($beneficio['chapita_color']);
        
        $beneficio['version']['fecha_inicio_vigencia'] 
            = date('d/m/Y', strtotime($beneficio['version']['fecha_inicio_vigencia']));
        $beneficio['version']['fecha_fin_vigencia'] 
            = date('d/m/Y', strtotime($beneficio['version']['fecha_fin_vigencia']));
        $beneficio['version']['fecha_inicio_publicacion'] 
            = date('d/m/Y', strtotime($beneficio['version']['fecha_inicio_publicacion']));
        $beneficio['version']['fecha_fin_publicacion'] 
            = date('d/m/Y', strtotime($beneficio['version']['fecha_fin_publicacion']));
        $formBeneficio->setDefaults($beneficio);
        $formBeneVersion->setDefaults($beneficio['version']);
        $formBeneficio->fillCategorias('categorias_seleccionadas', array_keys($beneficio['categorias']), true);
        $formBeneficio->fillCategorias('categorias_disponibles', array_keys($beneficio['categorias']), false);
        
        if ($this->getRequest()->isPost()) {
            $allParams = $this->getRequest()->getParams();
            $formBeneficio->setDefaults($allParams);
            $formBeneVersion->setDefaults($allParams);
            $gMaximoPorSubscriptor=0;
            $gStockTotal=0;
            $tipoRedencionUpd=isset($allParams['tipo_redencion'])?$allParams['tipo_redencion']:$tipoRedencion;
            $allParams['tipo_redencion'] = $tipoRedencionUpd;
            if (!isset($allParams['stock'])) {
                $allParams['stock'] = '';
            }
            
            
            if (isset($allParams['detTipDesc'])) {
                $formsDetalle = array();
                foreach ($allParams['detTipDesc'] as $key=>$val) {
                    $formDetalle = new Application_Form_DetalleBeneficio();
                    $formDetalle->add_hiddenId();
                    $formDetalle->add_cupon();
                    $formDetalle->add_TipoRedencion($tipoRedencionUpd);
                    
                    $detalle[$key]['d_codigo']       = $val["d_codigo"];
//                    $detalle[$key]['d_descuento']    = 0.00 + $val["d_descuento"];
                    
                    if ($tipoRedencionUpd==1) {
                        $detalle[$key]['d_porcentaje_descuento']  = $val["d_porcentaje_descuento"];
                    } else {
                        $detalle[$key]['d_precio_regular']  = 
                            empty($val["d_precio_regular"])?0:($val["d_precio_regular"]+0.00);
                        $detalle[$key]['d_precio_suscriptor']  = 
                            empty($val["d_precio_suscriptor"])?0:($val["d_precio_suscriptor"]+0.00);
                        $detalle[$key]['d_descuento'] = 
                            empty($val["d_descuento"])?-1:($val["d_descuento"]+0.00);
                    }
                    
                    $detalle[$key]['d_descripcion']  = $val["d_descripcion"];
                    if ($allParams['sin_stock']==2) {
                        $detalle[$key]['d_cantidad']  = $val["d_cantidad"];
                        $gStockTotal=$gStockTotal+$val["d_cantidad"];
                    } else {
                        $detalle[$key]['d_cantidad']  = 0;
                    }
                    if ($allParams['sin_limite_por_suscriptor']==2) {
                        $detalle[$key]['d_maximo_por_suscriptor']  = $val["d_maximo_por_suscriptor"];
                        $gMaximoPorSubscriptor=$gMaximoPorSubscriptor+$val["d_maximo_por_suscriptor"];
                    } else {
                        $detalle[$key]['d_maximo_por_suscriptor']  = 0;
                    }
                    $detalle[$key]['d_hidden'] = (empty($val["d_hidden"]))?"":($val["d_hidden"]);
                    $formDetalle->setElementsBelongTo('detTipDesc['.(empty($val["d_hidden"]))?"":($val["d_hidden"]).']');
                    $formDetalle->setDefaults($detalle[$key]);
                    $formsDetalle[] = $formDetalle;
                }
            }
            $formBeneficio->fillCategorias('categorias_disponibles', $allParams['categorias_disponibles']);
            if (isset($allParams['categorias_seleccionadas'])) 
                $formBeneficio->fillCategorias('categorias_seleccionadas', $allParams['categorias_seleccionadas']);
            $formBeneficio->setChapitaColor($allParams['chapita_color']);
            
            $upPDF = false;
            if (!empty($allParams['pdf_name'])) {
                $formBeneficio->uploadPDF();
                $upPDF = true;
            }
            else
                $formBeneficio->uploadPDF(false);
            
            $isValidBeneficio = $formBeneficio->isValid($allParams);
            
            if ($allParams['sin_stock']==1) {
                $validateStock["valor"] = true;
            } else {
                if ($allParams['sin_stock']==2) {
                    $allParams['stock']=$gStockTotal;
                }
                $validateStock = $objCupon->getCantCuponesByBenefAndCantGestorGenAndTipDesc($id, 
                        $allParams['stock'], null);
            }
            
            if(!isset($allParams['maximo_por_subscriptor'])){ $allParams['maximo_por_subscriptor']=0;}
//            if ($allParams['sin_limite_por_suscriptor']==2) {
//                $allParams['maximo_por_subscriptor']=$gMaximoPorSubscriptor;
//            }
            $validateLimitCuponXSuscriptor = $objCupon->getCantCuponesBySucripGen(
                $id, $allParams['maximo_por_subscriptor']
            );
            
            if (!$validateStock["valor"] && $allParams['sin_stock'] != 1) {
                $formsDetalle=$formsError;
                $this->getMessenger()->error(
                    'Error: el Stock no puede ser menor a los cupos
                        generados. Actualmente en: '.$validateStock["cant"]
                );
            } elseif (!$validateLimitCuponXSuscriptor["valor"] && $allParams['sin_limite_por_suscriptor'] != 1) {
                $formsDetalle=$formsError;
                $this->getMessenger()->error(
                    'Error: el Límite Cupos no puede ser menor a los cupos
                        ya generados por suscriptor, Actualmente en: '.$validateLimitCuponXSuscriptor["cant"]
                );
            } elseif (($allParams['stock'] < $allParams['maximo_por_subscriptor']) && 
                    ($allParams['sin_limite_por_suscriptor'] != 1) && ($allParams['sin_stock'] != 1) ) {
                $formsDetalle=$formsError;
                $this->getMessenger()->error(
                    'Error: El Stock no puede ser menor que el Límite cupos.'
                );
            } elseif ($isValidBeneficio) {
                try {
                    $beneficiop = new Application_Model_Beneficio();
                    $cuponp = new Application_Model_Cupon();
                    $valuesBeneficioVersion = $formBeneVersion->getValues();
//                    $beneficioCategorias = new Application_Model_CategoriaBeneficio();
                    $db = $beneficiop->getAdapter();
                    $db->beginTransaction();
                    
                    $utilfile = $this->_helper->getHelper('UtilFiles');
                    $nuevoNombre = $utilfile->_renameFile($formBeneficio, 'path_logo','promo',
                            array("slug"=>$this->view->TextToUrl($allParams['titulo']), "id"=>$id) );
                    $valuesBeneficio = $formBeneficio->getValues();
                    $valuesBeneficio['tipo_redencion']=$tipoRedencionUpd;
                    if (@$nuevoNombre == '') {
                        $valuesBeneficio['path_logo'] = $beneficio['path_logo'];
                    } else {
                        $valuesBeneficio['path_logo'] = $nuevoNombre;
                    }
                    
                    $catSelecionadas = $valuesBeneficio['categorias_seleccionadas'];
                    unset($valuesBeneficio['categorias_seleccionadas']);
                    unset($valuesBeneficio['categorias_disponibles']);
                    
                    $valuesBeneficio['actualizado_por'] = $this->auth['usuario']->id;
                    if ($valuesBeneficio['sin_limite_por_suscriptor'] == 1) {
                        unset($valuesBeneficio['maximo_por_subscriptor']);
                        $valuesBeneficio['maximo_por_subscriptor'] = 0;
                    } elseif ($valuesBeneficio['sin_limite_por_suscriptor'] == 2) {
                        //if (!empty($gMaximoPorSubscriptor)) {
                        //    $valuesBeneficio['maximo_por_subscriptor']=$gMaximoPorSubscriptor;
                        //}
                    } else {
                        $valuesBeneficio['sin_limite_por_suscriptor'] = 0;
                        //if (!empty($gMaximoPorSubscriptor)) {
                        //    $valuesBeneficio['maximo_por_subscriptor']=$gMaximoPorSubscriptor;
                        //}
                    }
                    $date = date('Y-m-d H:i:s');
                    $valuesBeneficio['fecha_actualizacion'] = $date;
                    
//                    //SETEO DE VALORES RESPECTO AL ESTADO DEL VENEFICIO
                    $valuesBeneficio['publicado'] = Application_Form_Beneficio
                        ::$fromEstadoBeneficio[$valuesBeneficio['estado']]['publicado'];
                    $valuesBeneficio['activo'] = Application_Form_Beneficio
                        ::$fromEstadoBeneficio[$valuesBeneficio['estado']]['activo'];
                    if (isset($valuesBeneficio['estado'])) {
                        unset($valuesBeneficio['estado']);
                    }
                    
                    //DESTACADOS
                    $valuesBeneficio['es_destacado'] = Application_Form_Beneficio
                        ::$fromDestacado[$valuesBeneficio['destacado']]['es_destacado'];
                    $valuesBeneficio['es_destacado_principal'] = Application_Form_Beneficio
                        ::$fromDestacado[$valuesBeneficio['destacado']]['es_destacado_principal'];
                    unset($valuesBeneficio['destacado']);
                    
                    
                    $establecimiento =
                        Application_Model_Establecimiento::getEstablecimiento($valuesBeneficio['establecimiento_id']);
                    if ($valuesBeneficio['telefono_info_establecimiento'] == 1) {
                        $valuesBeneficio['telefono_info'] = $establecimiento['telefono_contacto'];
                    }
                    if ($valuesBeneficio['email_info_establecimiento'] == 1) {
                        $valuesBeneficio['email_info'] = $establecimiento['email_contacto'];
                    }
                    $tipoBeneficio = 
                        Application_Model_TipoBeneficio::getTipoBeneficio($valuesBeneficio['tipo_beneficio_id']);
                    
                    unset($valuesBeneficio['email_info_establecimiento']);
                    unset($valuesBeneficio['telefono_info_establecimiento']);
                    $pdfName = $valuesBeneficio['pdf_name'];
                    unset($valuesBeneficio['pdf_name']);
                    unset($valuesBeneficio['pdf_resultado']);
                    $pdfInfoName = $valuesBeneficio['pdf_info_name'];
                    unset($valuesBeneficio['pdf_info']);
                    unset($valuesBeneficio['pdf_info_name']);

//                    $valuesBeneficioVersion = $formBeneVersion->getValues();
                    
                    if ($upPDF && strstr($pdfName, 'temp_')) {
                        $name = "" . time() . "_" . $id . ".pdf";
                        $path = $this->config->paths->elementsSorteoRoot;
                        rename($path . "temp/" . $pdfName, $path . $name);
                        $valuesBeneficio['pdf_file'] = $name;
                    }
                    
                    if ($cantCuponesByBenef>0) {
                        unset($valuesBeneficio['tipo_moneda_id']);
                    }
                    if (strstr($pdfInfoName, 'temp_')) {
                        $name = "" . time() . "i_" . $id . ".pdf";
                        $path = $this->config->paths->elementsSorteoRoot;
                        rename($path . "temp/" . $pdfInfoName, $path . $name);
                        $valuesBeneficio['pdf_info_file'] = $name;
                    } else {
                        unset($valuesBeneficio['pdf_info_descrip']);
                    }
                    
                    $where = $db->quoteInto('id = ?', $id);
                    $beneficiop->update($valuesBeneficio, $where);
                    $beneficiop->actualizarCategorias($id, $catSelecionadas);
                    //BENEFICIOS VERSION
                    $valuesBeneficioVersion['creado_por'] = $valuesBeneficio['actualizado_por'];
                    $valuesBeneficioVersion['beneficio_id'] = $id;
                    
                    $cuponp->setBeneficio_id($id);
//                    if (!$hasDetalle) {
                        if ($valuesBeneficio['sin_stock'] == 1) {
                            unset($valuesBeneficioVersion['stock']);
                            $valuesBeneficioVersion['stock_actual'] = 0;
                        } else {
                            if ($valuesBeneficio['sin_stock']==2) {
                                $valuesBeneficioVersion['stock']=$gStockTotal;
                            }
                            $valuesBeneficioVersion['stock_actual'] 
                                = ($valuesBeneficioVersion['stock']-$cuponp->getNroCuponesGenByBeneficio());
                        }
                    $valuesBeneficioVersion['fecha_inicio_vigencia']
                        = date_format(
                            DateTime::createFromFormat('d/m/Y', $valuesBeneficioVersion['fecha_inicio_vigencia']),
                            'Y-m-d'
                        );
                    $valuesBeneficioVersion['fecha_fin_vigencia']
                        = date_format(
                            DateTime::createFromFormat('d/m/Y', $valuesBeneficioVersion['fecha_fin_vigencia']),
                            'Y-m-d'
                        );
                    $valuesBeneficioVersion['fecha_inicio_publicacion']
                        = date_format(
                            DateTime::createFromFormat('d/m/Y', $valuesBeneficioVersion['fecha_inicio_publicacion']),
                            'Y-m-d'
                        );
                    $valuesBeneficioVersion['fecha_fin_publicacion']
                        = date_format(
                            DateTime::createFromFormat('d/m/Y', $valuesBeneficioVersion['fecha_fin_publicacion']),
                            'Y-m-d'
                        );
                    $valuesBeneficioVersion['fecha_registro'] = $date;
                    $valuesBeneficioVersion['activo'] = 1;
                    $beneficiop->nuevaVersion($id, $valuesBeneficioVersion);

                    //DETALLE BENEFICIO
                    $objDetalle = new Application_Model_DetalleTipoPromocion();
//                    $whereDTP = $objDetalle->getAdapter()->quoteInto('beneficio_id = ?', $id);
//                    $objDetalle->update(array("activo"=>"0"),$whereDTP);
                    $objDetalle->disabledActivosEsceptoTipodescuentoEnCuponGenerados($id);
                    
                    if (isset($allParams['detTipDesc'])) {
                        foreach ($formsDetalle as $form) {
    //                        $formDetalle = new Application_Form_DetalleBeneficio();
                            $values = $form->getValues();
                            if ($form->isValid($values)) {

                                if (empty($values['d_hidden'])) {
                                    if ($valuesBeneficio['sin_limite_por_suscriptor'] == 2) {
                                        $iuMaximoPorSuscriptor=$values['d_maximo_por_suscriptor'];
                                    } else { 
                                        $iuMaximoPorSuscriptor=0; 
                                    }
                                    if ($valuesBeneficio['sin_stock'] == 2) {
                                        $iuStockGeneral=$values['d_cantidad'];
                                    } else { 
                                        $iuStockGeneral=0; 
                                    }
                                    
                                    $datoInsert['beneficio_id']=$id;
                                    $datoInsert['codigo']=$values['d_codigo'];
                                    //$datoInsert['descuento']=$values['d_descuento'];
                                    $datoInsert['descripcion']=$values['d_descripcion'];
                                    $datoInsert['maximo_por_suscriptor']=$iuMaximoPorSuscriptor;
                                    $datoInsert['cantidad']=$iuStockGeneral;
                                    $datoInsert['stock_actual']=$iuStockGeneral;
                                    $datoInsert['activo']='1';
                                    
                                    if ($tipoRedencionUpd==1) {
                                        $datoInsert['porcentaje_descuento']=$values['d_porcentaje_descuento'];
                                    } else {
                                        $datoInsert['precio_regular']=$values['d_precio_regular'];
                                        $datoInsert['precio_suscriptor']=$values['d_precio_suscriptor'];
                                        if($datoInsert['precio_regular'] <= $datoInsert['precio_suscriptor']){
                                            $messageErr = 'Error: Precio suscriptor no puede ser mayor que precio regular.';
                                            throw new Exception($messageErr);                                    
                                        }
                                        $datoInsert['descuento']=($datoInsert['precio_regular']-$datoInsert['precio_suscriptor']);
                                    }
                                    
                                    $objDetalle->insert($datoInsert);
                                    
                                } else {
                                    unset($datUpd);
                                    $datUpd['beneficio_id']=$id;
                                    if ($valuesBeneficio['sin_limite_por_suscriptor'] == 2) {
                                        $iuMaximoPorSuscriptor=$values['d_maximo_por_suscriptor'];
                                    } else { 
                                        $iuMaximoPorSuscriptor=0; 
                                    }
                                    if ($valuesBeneficio['sin_stock'] == 2) {
                                        $iuStockGeneral=$values['d_cantidad'];
                                        
                                        $nCupGenParaStock = Application_Model_Cupon::
                                            getCantCuponesByBenefAndTipDescAndSuscriptor($id, $values['d_hidden'], null);
                                        $stockDisponibleDet['stock_actual'] = $iuStockGeneral - $nCupGenParaStock;
                                        $objDetalle->update($stockDisponibleDet, "id = '".$values['d_hidden']."'");
                                        
                                        if ($stockDisponibleDet['stock_actual'] < 0) {
                                            $formsDetalle=$formsError;
                                            throw new Exception(
                                                    "Error: el Stock por Detalle no puede ser menor a los cupos generados"
                                            );
                                        }
                                        
                                    } else { 
                                        $iuStockGeneral=0; 
                                    }
                                    $whereUDTP = $objDetalle->getAdapter()->quoteInto('id = ?', $values['d_hidden']);
                                    $val = $objCupon->getCantCuponesBySucripGen(
                                                $id, $iuMaximoPorSuscriptor, $values['d_hidden']
                                            );
                                    //$val = Application_Model_Cupon::getCantCuponesByBenefAndTipDesc($id, 
                                    //        $values['d_hidden']);
                                    $datUpd['maximo_por_suscriptor']= $iuMaximoPorSuscriptor;
                                    $datUpd['cantidad']= $iuStockGeneral;
                                    
                                    if ($val['cant']==0) {
                                        if ($tipoRedencionUpd==1) {
                                            $datUpd['porcentaje_descuento']=$values['d_porcentaje_descuento'];
//                                        } else {
//                                            $datUpd['precio_regular']=$values['d_precio_regular'];
//                                            $datUpd['precio_suscriptor']=$values['d_precio_suscriptor'];
//                                            if($datUpd['precio_regular'] <= $datUpd['precio_suscriptor']){
//                                                $messageErr = 'Error: Precio suscriptor no puede ser mayor 
//                                                    que precio regular.';
//                                                $formsDetalle=$formsError;
//                                                throw new Exception($messageErr);                                    
//                                            }
//                                            $datUpd['descuento']=
//                                                ($datUpd['precio_regular']-$datUpd['precio_suscriptor']);
                                        }
                                        $datUpd['codigo']=$values['d_codigo'];
                                        //$datUpd['descuento']=0.00 + $values['d_descuento'];
                                    } else {
                                        if ($valuesBeneficio['sin_limite_por_suscriptor'] == 2 
                                                && $val['cant']>$iuMaximoPorSuscriptor) {
                                            $formsDetalle=$formsError;
                                            throw new Exception(
                                                    "Error: el Límite Cupos no puede ser menor a los cupos generados"
                                            );
                                        }
                                    }
                                    
                                    //---> Ticket #26057
                                    if ($tipoRedencionUpd!=1) {
                                        $datUpd['precio_regular']=$values['d_precio_regular'];
                                        $datUpd['precio_suscriptor']=$values['d_precio_suscriptor'];
                                        if($datUpd['precio_regular'] <= $datUpd['precio_suscriptor']){
                                            $messageErr = 'Error: Precio suscriptor no puede ser mayor 
                                                que precio regular.';
                                            $formsDetalle=$formsError;
                                            throw new Exception($messageErr);                                    
                                        }
                                        $datUpd['descuento']=
                                            ($datUpd['precio_regular']-$datUpd['precio_suscriptor']);
                                    }
                                    //<--- FIN Ticket #26057
                                    
                                    $datUpd['descripcion']=$values['d_descripcion'];
                                    $datUpd['activo']='1';
                                    $objDetalle->update(
                                        $datUpd, $whereUDTP
                                    );
                                }

                            } else {
                                $formsDetalle=$formsError;
                                throw new Exception('Hubo un error en la generación de detalles del tipo promoción');
                            }
                        }
                    }
                    
                    $db->commit();
                    
                    Application_Model_Beneficio::updateIndexBeneficios($beneficio['id'], $last);

                    $this->getMessenger()->success('El beneficios fue actualizado satisfactoriamente');
                    $this->_redirect('gestor/beneficios');
                } catch (Exception $e) {
                    $db->rollBack();
                    $this->getMessenger()->error($e->getMessage());
                    $formsDetalle=$formsError;
                }
            } else {
                $p = new Plugin_CsrfProtect();
                $this->view->csrf = $p->getToken();
                $formsDetalle=$formsError;
                $this->getMessenger()->error(
                    'Hubo un error al intentar actualizar tus datos, verifica tu información'
                );
            }
        } else {
            $beneficio["categorias_seleccionadas"]
            = array_keys(
                Application_Model_Categoria::getCategorias(
                    true, array('activo' => 1), true, array_keys($beneficio['categorias'])
                )
            );
            $beneficio["destacado"]
            = Application_Form_Beneficio::$destacados[$beneficio['es_destacado']][$beneficio['es_destacado_principal']];
            $beneficio["estado"]
            = Application_Form_Beneficio::$estadosBeneficio[$beneficio['publicado']][$beneficio['activo']];
//            var_dump($beneficio["categorias_disponibles"]);exit;
//            $formBeneficio->fillCategorias('categorias_disponibles');
            $formBeneficio->isValid($beneficio);
            $formBeneVersion->isValid($beneficio['version']);
            
//            if (!$isValidBeneficio) {
//                //var_dump($formBeneficio->getElements());
//                foreach ($formBeneficio->getElements() as $key => $e){
//                    //$e   = new Zend_Form_Element();
//                    var_dump($key);
//                    var_dump($e->getErrors());
//                }
//                exit;
//            }
        }
        $this->view->cantCuponesGenerados = $cantCuponesByBenef;
        $this->view->idBen = $id;
        $this->view->formsDetalle = $formsDetalle;
        $this->view->formBeneficio = $formBeneficio;
        $this->view->formBeneVersion = $formBeneVersion;
        $this->view->imgPhoto = $beneficio['path_logo'];
        $this->view->codigo = $beneficio['codigo'];
        $this->view->sufixlittle = $config->beneficios->logo->sufix->little;
    }
    
    public function vencerBeneficioAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $obj = new Application_Model_Beneficio();
        $id = $this->_getParam('id', false);
        $beneficio = $obj->getBeneficioInfoById($id);
        $last = $beneficio;
        $response = array();
        if (!$beneficio) {
            $response['status'] = false;
            $response['message'] = 'Debe seleccionar un beneficio valido.';
        } elseif ($beneficio['publicado'] == 0 
            || ($beneficio['publicado'] == 1 && $beneficio['activo'] == 0)
        ) {
            $response['status'] = false;
            $response['message']
                = 'El beneficio seleccionado no está vigente, no se puede vencer.';
        } else {
            try {
                $obj->getAdapter()->beginTransaction();
                $where = $obj->getAdapter()->quoteInto('id = ?', $id);
                $obj->update(array('publicado' => 1, 'activo' => 0), $where);
                $response['status'] = true;
                $response['message'] = 'Beneficio vencido correctamente.';
                $obj->getAdapter()->commit();
                $zl = new ZendLucene();
                $zl->deleteIndexBeneficio($id);
                Application_Model_Beneficio::refreshCache();
            } catch (Exception $exc) {
                $obj->getAdapter()->rollBack();
                $response['status'] = false;
                $response['message'] = 'Hubo un error al actualizar el beneficio.';
            }
        }
        $this->_response->appendBody(Zend_Json::encode($response));
    }
    
    public function vencerLoteBeneficiosAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $obj = new Application_Model_Beneficio();
        $ids = $this->_getParam('beneficio', false);
        $response = array();
        $count = count($ids);
        if ($count == 0 || $ids == null) {
            $response['status'] = false;
            $response['message'] = 'Debe seleccionar un beneficio valido.';
        } else {
            try {
                $obj->getAdapter()->beginTransaction();
                for ($i = 0; $i < $count; $i++) {
                    $beneficio = $obj->getBeneficioInfoById($ids[$i]);
                    if ($beneficio['publicado'] == 1 && $beneficio['activo'] == 1) {
                        $where = $obj->getAdapter()->quoteInto('id = ?', $beneficio['id']);
                        $obj->update(array('publicado' => 1, 'activo' => 0), $where);
                        $zl = new ZendLucene();
                        $zl->deleteIndexBeneficio($beneficio['id']);
                    }
                }
                $beneficiop = new Application_Model_Beneficio();
                @$beneficiop->getMainDestacado(false);
                @$beneficiop->getDestacadosPortada(false);
                $obj->getAdapter()->commit();
                $response['status'] = true;
                $response['message'] = 'Beneficios vencidos correctamente.';
            } catch (Exception $exc) {
                $obj->getAdapter()->rollBack();
                $response['status'] = false;
                $response['message'] = 'Hubo un error al actualizar los beneficios.';
            }
            $this->_response->appendBody(Zend_Json::encode($response));
        }
    }
    
    public function darBajaBeneficioAction()
    {
        $this->_helper->layout->disableLayout();
        $obj = new Application_Model_Beneficio();
        
        if ($this->getRequest()->isPost()) {
            $this->_helper->viewRenderer->setNoRender();
            
            $messages = array();
            $idBen = $this->getRequest()->getPost('id', '');
            try {
                $db = $this->getAdapter();
                $db->beginTransaction();
                
                $data['elog'] = '1';
                $data['fecha_de_baja'] = date('Y-m-d h:i:s');
                $data['de_baja_por'] = $this->auth['usuario']->id;
                $obj->update($data, "id = '".$idBen."'");
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
            $beneficio = $obj->getStatusBeneficioById($id);
            
            $msj.= 'El beneficio <i><u>'.trim($beneficio['titulo']).'</u></i> esta '.
                ($beneficio['activo']?'activo':'inactivo').' '.
                ($beneficio['publicado']?'y publicado':'y no publicado').
                ', tiene '.(!empty($beneficio['ncuponesgen'])?$beneficio['ncuponesgen']:'0').
                ' cupon(es) generado(s) y '.
                (!empty($beneficio['ncuponesconsumidos'])?$beneficio['ncuponesconsumidos']:'0').
                ' cupon(es) redimido(s)';
            $msj.= ' ¿Estás seguro que deseas darle de baja?';
            $this->view->id = $id;
            $this->view->msj = $msj;
        }
    }
}

