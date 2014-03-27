<?php

class Gestor_VidaSocialController extends App_Controller_Action_Gestor
{

    public function init()
    {
        parent::init();
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        Zend_Layout::getMvcInstance()->active
            = App_Controller_Action_Gestor::MENU_NAME_VIDA_SOCIAL;

        $this->view->headScript()->appendFile(
            $this->config->app->mediaUrl . '/js/gestor/articulo.js'
        );
        $this->view->headScript()->appendFile(
            $this->config->app->mediaUrl . '/js/vida_social.js'
        );

        $frm = new Application_Form_FiltroArticulos();

        $page = $this->_getParam('page', 1);
        $ord  = $this->_getParam('ord');
        $col  = $this->_getParam('col');
        $estado     = $this->_getParam('estado');
        $query      = $this->_getParam('query');

        $objArticulo = new Application_Model_Articulo();
        $paginator = $objArticulo->getListaArticulos($col, $ord, $estado, $query);

        $this->view->MostrandoN  = $paginator->getItemCount($paginator->getItemsByPage($page));
        $this->view->MostrandoDe = $paginator->getTotalItemCount();
        $this->view->totalitems  = $paginator->getItemCount($paginator->getItemsByPage($page));
        $paginator->setCurrentPageNumber($page);
        $this->view->listaArticulos = $paginator;
        $this->view->ord = $ord;
        $this->view->col = $col;

        $frm->setField("estado", $estado);
        $frm->setField("query", $query);
        $this->view->frm = $frm;

        $this->view->sufijo = $this->config->galeria->sufix->little;
        
        $nroPorPage = $paginator->getItemCountPerPage();
        $nroPage = $paginator->getCurrentPageNumber();
        $nroReg = $paginator->getCurrentItemCount();
        
        $this->view->mostrando = "Mostrando ".
            ' '.(($nroPage-1)*$nroPorPage + 1).
            ' - '.((($nroPage-1)*$nroPorPage) + $nroReg).
            ' de '.$paginator->getTotalItemCount();
        $this->view->nroregistros = "Registros listados : ".$nroReg;
    }

    public function nuevoAction()
    {
        Zend_Layout::getMvcInstance()->active = App_Controller_Action_Gestor::MENU_NAME_VIDA_SOCIAL;

        $vsFechaMin= date("Y-m-d", strtotime("-1 month")) ;
        $arrayNuevaFecha= explode("-",$vsFechaMin);
       
        $js = sprintf(
            'var dt = {logo : %s}, nbfields = %s,
                 vsFechaMin = {anio : %s,mes : %s,dia : %s};',
            $this->mediaUrl + '/images/calendar.png',
            14,
            $arrayNuevaFecha[0],$arrayNuevaFecha[1],$arrayNuevaFecha[2]
        );
        $this->view->headScript()->appendScript($js);
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

        $this->view->headScript()->appendFile(
            $this->config->app->mediaUrl . '/js/gestor/vida-social-validator.js'
        );
        
        $this->view->headScript()->appendFile(
            $this->config->app->mediaUrl . '/js/gestor/uploader_preview_extend.js'
        );
        
        $frm = new Application_Form_NuevoArticulo();
        
        if ($this->_request->isPost()) {
            $params = $this->_getAllParams();
            if ($frm->isValid($params)) {
                //var_dump($params);
                $titulo = $params["titulo"];
                $contenido = $params["contenido"];
                $fechaini  = $params["fechaini"];
//                $portada   = $params["principal"];
                $activo    = $params["publicar"];
                
                $fie = explode("/", $params["fechainievent"]);
                $fechainievent  = $fie[2]."-".$fie[1]."-".$fie[0];
                
                $fi = explode("/", $params["fechaini"]);
                $fechaini  = $fi[2]."-".$fi[1]."-".$fi[0];

                $ff = explode("/", $params["fechafin"]);
                $fechafin  = $ff[2]."-".$ff[1]."-".$ff[0];
                
                $publicar  = $params["publicar"];
                $listaimg  = isset($params["listaimagenes"])?
                    explode("|", $params["listaimagenes"]):array();
                $listaimgTitulo  = isset($params["descimagenes"])?
                    explode("|", $params["descimagenes"]):array();

                $s = new App_Filter_Slug();

                $objArticulo = new Application_Model_Articulo();
//                if ($portada==1) $objArticulo->update(array("portada"=>"0"), "activo=1");

                $arreglo["creado_por"] = $this->auth["usuario"]->id;
                $arreglo["actualizado_por"] = $this->auth["usuario"]->id;
                $arreglo["slug"] = $s->filter($titulo);
                $arreglo["titulo"] = $titulo;
                $arreglo["contenido"] = $contenido;
//                $arreglo["portada"] = $portada;
                $arreglo["veces_visto"] = 0;
                $arreglo["activo"] = $activo;
                $arreglo["fecha_inicio_vigencia"] = $fechaini;
                $arreglo["fecha_inicio_evento"] = $fechainievent;                
                $arreglo["fecha_fin_vigencia"] = $fechafin;
                $arreglo["fecha_registro"] = date("Y-m-d");
                $arreglo["fecha_actualizacion"] = date("Y-m-d");

                
                $idArticulo = $objArticulo->insert($arreglo);
//                $ruta = ELEMENTS_ROOT."/articulos/".$idArticulo;
                $ruta = ELEMENTS_ROOT."/images/sociales";
                //var_dump($ruta);
                mkdir($ruta, 0777);

                $arregloG["articulo_id"] = $idArticulo;
                $slug = $this->view->TextToUrl($arreglo["titulo"]);
                                
                $objGaleria = new Application_Model_GaleriaImagenes();
                for ($i=0;$i<count($listaimg);$i++) {
                    $path = explode("/", $listaimg[$i]);
                    $arregloG["titulo"] = $listaimgTitulo[$i];
                    $arregloG["path_imagen"] = $path[count($path)-1];
                    $arregloG["orden"] = $i+1;
                    $arregloG["es_principal"] = ($i+1)==1?1:0;

                    $ext = explode(".", $arregloG["path_imagen"]);
                    $parte = $idArticulo."S".date("YmdHis").rand(0, 99).($i+1);
//                    $nombreA = $parte.".".$ext[1];
//                    $nombreB = $parte."Small.".$ext[1];
                    $nombreA = $slug."-".$arregloG["orden"]."-".$idArticulo.".".$ext[1];
                    $nombreB = $nombreA;

                    $arregloG["path_imagen"] = $nombreA;
                    
                    $objGaleria->insert($arregloG);
                    

                    $origen = ELEMENTS_ROOT."/images/sociales/temp/".$path[count($path)-1];;
                    $destino = $ruta."/".$nombreA;
//                    $destinoSmall = $ruta."/".$nombreB;
                    $destinoSmall = $ruta."/thums/".$nombreB;
                    //var_dump($origen);
                    //var_dump($destino);

                    //LaGrande
                    $width = $this->config->vidasocial->tamano->img->big->w;
                    $height = $this->config->vidasocial->tamano->img->big->h;
                    $calidad = $this->config->vidasocial->tamano->img->big->calidad;
                    $this->_helper->UtilFiles->_redimensionar(
                        $origen, $destino, $calidad, $width, $height
                    );

                    //La Small
                    $width = $this->config->vidasocial->tamano->img->small->w;
                    $height = $this->config->vidasocial->tamano->img->small->h;
                    $calidad = $this->config->vidasocial->tamano->img->small->calidad;
                    $this->_helper->UtilFiles->_redimensionar(
                        $origen, $destinoSmall, $calidad, $width, $height
                    );
                    
                    unlink(ELEMENTS_ROOT."/images/sociales/temp/".$path[count($path)-1]);
                }
                //var_dump($listaimg);
                
                $this->getMessenger()->success("Artículo guardado correctamente.");
                $this->_redirect("/gestor/vida-social");
            }
        }
        
        $this->view->frm = $frm;
    }


    public function editarAction()
    {
        $params = $this->_getAllParams();
        
        $idArticulo = isset($params["id"])?$params["id"]:-1;
        if($idArticulo==-1) $this->_redirect("/gestor/vida-social/");

        $objArticulo = new Application_Model_Articulo();
        $resultArt = $objArticulo->find($idArticulo);

        if ($resultArt->count()==0) {
            $this->getMessenger()->error("ERROR, No se puede editar, articulo no existe.");
            $this->_redirect("/gestor/vida-social/");
        }

        $this->view->articulo = $resultArt;

        Zend_Layout::getMvcInstance()->active
          = App_Controller_Action_Gestor::MENU_NAME_VIDA_SOCIAL;

        $vsFechaMin= date("Y-m-d", strtotime("-1 month")) ;
        $arrayNuevaFecha= explode("-",$vsFechaMin);
       
        $js = sprintf(
            'var dt = {logo : %s}, nbfields = %s,
                 vsFechaMin = {anio : %s,mes : %s,dia : %s};',
            $this->mediaUrl + '/images/calendar.png',
            14,
            $arrayNuevaFecha[0],$arrayNuevaFecha[1],$arrayNuevaFecha[2]
        );
        
        $this->view->headScript()->appendScript($js);
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

        $this->view->headScript()->appendFile(
            $this->config->app->mediaUrl . '/js/gestor/vida-social-validator.js'
        );

        $this->view->headScript()->appendFile(
            $this->config->app->mediaUrl . '/js/gestor/uploader_preview_extend.js'
        );

        $frm = new Application_Form_NuevoArticulo();

        $frm->setField("titulo", $resultArt[0]->titulo);
        $frm->setField("contenido", $resultArt[0]->contenido);
        $fi = new DateTime($resultArt[0]->fecha_inicio_vigencia);
        $frm->setField("fechaini", $fi->format("d/m/Y"));
        $ff = new DateTime($resultArt[0]->fecha_fin_vigencia);
        $frm->setField("fechafin", $ff->format("d/m/Y"));
        $fie = new DateTime($resultArt[0]->fecha_inicio_evento);
        $frm->setField("fechainievent", $fie->format("d/m/Y"));
        $frm->setField("publicar", $resultArt[0]->activo);
        $frm->setField("principal", $resultArt[0]->portada);

        $objGaleria = new Application_Model_GaleriaImagenes();
        $this->view->galeria = $galeria = $objGaleria->getImagenesArticulo($resultArt[0]->id);

        

        if ($this->_request->isPost()) {
            $params = $this->_getAllParams();
            if ($frm->isValid($params) ) {
                $titulo    = $params["titulo"];
                $contenido = $params["contenido"];
                $fechaini  = $params["fechaini"];
//                $portada   = $params["principal"];
                $activo    = $params["publicar"];
                
                $fie = explode("/", $params["fechainievent"]);
                $fechainievent  = $fie[2]."-".$fie[1]."-".$fie[0];
                
                $fi = explode("/", $params["fechaini"]);
                $fechaini  = $fi[2]."-".$fi[1]."-".$fi[0];

                $ff = explode("/", $params["fechafin"]);
                $fechafin  = $ff[2]."-".$ff[1]."-".$ff[0];

                $publicar  = $params["publicar"];
                $listaimg  = isset($params["listaimagenes"])?
                    explode("|", $params["listaimagenes"]):array();
                $listaimgTitulo  = isset($params["descimagenes"])?
                    explode("|", $params["descimagenes"]):array();

                $s = new App_Filter_Slug();

                $arreglo["actualizado_por"] = $this->auth["usuario"]->id;
                $arreglo["slug"] = $s->filter($titulo);
                $arreglo["titulo"] = $titulo;
                $arreglo["contenido"] = $contenido;
                //$arreglo["portada"] = $portada;
                $arreglo["activo"] = $activo;
                $arreglo["fecha_inicio_vigencia"] = $fechaini;
                $arreglo["fecha_inicio_evento"] = $fechainievent;
                $arreglo["fecha_fin_vigencia"] = $fechafin;
                $arreglo["fecha_actualizacion"] = date("Y-m-d");

                $where = $objArticulo->getAdapter()->quoteInto("id=?", $idArticulo);
                $objArticulo->update($arreglo, $where);

                $where = $objGaleria->getAdapter()->quoteInto("articulo_id=?", $idArticulo);
                $objGaleria->delete($where);
                
                //borramos todas las imagenes del directorio
                $dir = ELEMENTS_ROOT."/articulos/".$idArticulo."/";
                $handle = opendir($dir);
                while ($file = readdir($handle)) {
                    if (is_file($dir.$file)) {
                        @unlink($dir.$file);
                    }
                }
                $ruta = ELEMENTS_ROOT."/images/sociales";
                $arregloG["articulo_id"] = $idArticulo;
//                $arregloG["titulo"] = "";
                $slug = $this->view->TextToUrl($arreglo["titulo"]);
                
                for ($i=0;$i<count($listaimg);$i++) {
                    $path = explode("/", $listaimg[$i]);
                    $arregloG["titulo"] = $listaimgTitulo[$i];
//                    $arregloG["path_imagen"] = $path[count($path)-1];
                    $arregloG["path_imagen"] = $path[count($path)-1];                    
                    $arregloG["orden"] = $i+1;
                    $arregloG["es_principal"] = ($i+1)==1?1:0;

                    $ext = explode(".", $arregloG["path_imagen"]);
//                    $parte = $idArticulo."S".date("YmdHis").rand(0, 99).($i+1);
                    $parte = $slug."-".$arregloG["orden"]."-".$idArticulo;
                    $nombreA = $parte.".".$ext[1];
//                    $nombreB = $parte."Small.".$ext[1];

                    $arregloG["path_imagen"] = $nombreA;

                    $objGaleria->insert($arregloG);

//                    $origen = ELEMENTS_ROOT."/beneficios/temp/".$path[count($path)-1];;
                    $origen = $ruta."/temp/".$path[count($path)-1];;
                    $destino = $ruta."/".$nombreA;
//                    $destinoSmall = $ruta."/".$nombreB;
                    $destinoSmall = $ruta."/thums/".$nombreA;
                    //var_dump($origen);
                    //var_dump($destino);
                    //LaGrande
                    $width = $this->config->vidasocial->tamano->img->big->w;
                    $height = $this->config->vidasocial->tamano->img->big->h;
                    $calidad = $this->config->vidasocial->tamano->img->big->calidad;
                    $this->_helper->UtilFiles->_redimensionar(
                        $origen, $destino, $calidad, $width, $height
                    );

                    //La Small
                    $width = $this->config->vidasocial->tamano->img->small->w;
                    $height = $this->config->vidasocial->tamano->img->small->h;
                    $calidad = $this->config->vidasocial->tamano->img->small->calidad;
                    $this->_helper->UtilFiles->_redimensionar(
                        $origen, $destinoSmall, $calidad, $width, $height
                    );

                    @unlink(ELEMENTS_ROOT."/images/sociales/temp/".$path[count($path)-1]);
                }
                //var_dump($listaimg);

                $this->getMessenger()->success("Artículo modificado correctamente.");
                $this->_redirect("/gestor/vida-social");
            }
        } else {
            foreach ($galeria as $item) {
                @copy(
                    ELEMENTS_ROOT."/images/sociales/".$item["path_imagen"],
                    ELEMENTS_ROOT."/images/sociales/temp/".$item["path_imagen"]
                );
            }
        }

        $this->view->frm = $frm;
    }

    public function darBajaArticuloAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $messages = array();
        $idArticulo = $this->_getParam('idArticulo', '');
        if (!empty($idArticulo)) {
            
            $objArt = new Application_Model_Articulo();
            $data['elog'] = '1';
            $data['fecha_de_baja'] = date('Y-m-d h:i:s');
            $data['de_baja_por'] = $this->auth['usuario']->id;
            $objArt->update($data, "id = '".$idArticulo."'");
            $messages['success'] = true;
            $messages['mensaje'] = 'Se ejecuto correctamente';
        } else {
            $messages['success'] = false;
            $messages['mensaje'] = 'Error';
        }
        $this->_response->appendBody(Zend_Json::encode($messages));
    }
}

