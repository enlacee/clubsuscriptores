<?php

class Suscriptor_BeneficioController extends App_Controller_Action_Suscriptor
{

    protected $_beneficio;
    protected $_beneficioVersion;
    protected $_cupon;
    public static $usarLucene = true;

    public function init()
    {
        parent::init();
        /* Initialize action controller here */
        Zend_Layout::getMvcInstance()->active = 
            App_Controller_Action_Suscriptor::MENU_NAME_BENEFICIO;
        $this->_beneficio = new Application_Model_Beneficio();
        $this->_beneficioVersion = new Application_Model_BeneficioVersion();
        $this->_cupon = new Application_Model_Cupon();
        $this->_fecha = new Zend_Date();
        
//        $this->view->sufixmedium = $this->getConfig()->beneficios->logo->sufix->medium;
//        $this->view->sufixlittle = $this->getConfig()->beneficios->logo->sufix->little;
        $this->view->sufixmedium = $this->getConfig()->beneficios->img->medium;
        $this->view->sufixlittle = "";//$this->getConfig()->beneficios->img->small;
    }

    public function indexAction()
    {
        $this->view->headScript()->appendFile(
            $this->mediaUrl.'/js/buscador_beneficios.js'
        );
        $this->view->headScript()->appendFile(
            $this->mediaUrl.'/js/buscador_leftside_beneficios.js'
        );
        $this->view->headMeta()->setName("keywords", "Catálogo de beneficios, promociones de el comercio, 
            beneficios permanentes de el comercio, cierra puertas, tickets.");
        
     /*
      *  Adaptador Ander xD
      */
        
      //--> Para categoria mas de 3
        $hd_categoria=$this->_getParam('hd_categoria','');
        $categoria = $this->_getParam('categoria','');
        $session = $this->getSession();
        $linkCategoria="";
        if ($categoria=="categorias-online") {
            $linkCategoria=$categoria;
            if (!empty($hd_categoria)) {
                $session->__set("tmp_categoria", $hd_categoria);
                $categoria=$hd_categoria;
            } 
            if ($session->__isset("tmp_categoria")) {
                $categoria=$session->__get("tmp_categoria");
            }
        }
        
//        $loultimo   = $this->_getParam('quebuscas');
//        $beneficios = $this->_getParam('beneficios');
//        $categoria  = $this->_getParam('categoria');
//        $np         = $this->_getParam('np');
//        $page       = $this->_getParam('page', 1);
//        $ord        = $this->_getParam('ord');
//        $col        = $this->_getParam('col');
//        $query      = $this->_getParam('query');
//        $all        = $this->_getParam('todos', '');
        
        $esPage = $this->_helper->ValidCatalogo->_adaptadorCatalogoVirtual(array(
            'quebuscas' =>$this->_getParam('quebuscas'),
            'beneficios'=>$this->_getParam('beneficios'),
            'categoria' =>$categoria,
            'np'        =>$this->_getParam('np'),
            'page'      =>$this->_getParam('page',1),
            'ord'       =>$this->_getParam('ord'),
            'col'       =>$this->_getParam('col'),
            'query'     =>$this->_getParam('query'),
            'todos'     =>$this->_getParam('todos')
        ));
        
        //Zend_Debug::dump($esPage);
        //exit;
        $quebuscas    = $esPage["quebuscas"];
        $beneficios   = $esPage["beneficios"];
        $categoria    = $esPage["categoria"];
        $np           = $esPage["np"];
        $page         = $esPage["page"];
        $ord          = $esPage["ord"];
        $col          = $esPage["col"];
        $query        = $esPage["query"];
        $all          = $esPage["todos"];
        $np_slug      = $esPage["np_slug"];
        $quebuscas_id = $esPage["quebuscas_id"];
        $quebuscas_descrip = $esPage["quebuscas_descrip"];
        
        //--->Para la cabezera Ticket #10525
        $headTitle="Catálogo virtual del Club De Suscriptores El Comercio Perú";
        $headMetaDescripcion="Catálogo de beneficios por ser suscriptor de El Comercio, aquí encontrarás las mejores
            promociones, ofertas y puedes participar de todos nuestros beneficios. 
            Club De Suscriptores El Comercio Perú.";
        switch ($quebuscas_id) {
            case 1:
                $headTitle="Lo más visto en el Catálogo virtual del Club De Suscriptores El Comercio Perú";
                $headMetaDescripcion="Lo más visto en el Catálogo de beneficios del Club De Suscriptores de El Comercio,
                   aquí encontrarás las mejores promociones, ofertas y puedes participar de todos nuestros beneficios.";
                break;
            case 2:
                $headTitle="Lo más consumido en el Catálogo virtual del Club De Suscriptores El Comercio Perú";
                $headMetaDescripcion="Lo más consumido en el Catálogo de beneficios del Club De Suscriptores de 
                    El Comercio, aquí encontrarás las mejores promociones, ofertas y puedes participar 
                    de  todos nuestros beneficios.";
                break;
        }
        if($beneficios!='todos' && !empty($beneficios)){
            $headTitle=$beneficios." en el Catálogo virtual del Club De Suscriptores El Comercio Perú";
            $headMetaDescripcion=$beneficios." en el Catálogo de beneficios del Club De Suscriptores de El Comercio, 
                aquí encontrarás las mejores promociones, ofertas y puedes participar de todos nuestros beneficios.";
        }
        if($categoria!='todos' && !empty($categoria)){
            $ars=  explode("--",$categoria);
            if(count($ars)>3){
                $ars2="Categorías Online";
            } else {
                $ars2 = implode($ars,", ");
                $ars2 = str_replace("-", " ",$ars2);
            }            
            $headTitle= $ars2." en el Catálogo virtual del Club De Suscriptores El Comercio Perú";
            $headMetaDescripcion=$ars2." en el Catálogo de beneficios del Club De Suscriptores de El Comercio, 
                aquí encontrarás las mejores promociones, ofertas y puedes participar de todos nuestros beneficios.";
        }
        $this->view->headTitle()->set($headTitle);
        $this->view->headMeta()->setName("description",$headMetaDescripcion);
        
        
        $linkCategoria=empty($linkCategoria)?$categoria:$linkCategoria;
        
        $beneficios = ($beneficios=="todos")?"":$beneficios;
        $nombrebeneficio = "";$beneficios_id="";
        if ($beneficios!="") {
            $modelBeneficio = new Application_Model_TipoBeneficio();
            $rBeneficio = $modelBeneficio->fetchAll($modelBeneficio->select()->where('slug = ?', $beneficios));
            //$rBeneficio = $objBeneficio->find(array("slug"=>$beneficios));
            if($rBeneficio->count()==1) {
                $nombrebeneficio = $rBeneficio[0]->nombre;
                $beneficios_id = $rBeneficio[0]->id;
            } else {
                $beneficios = "";
            }
        }
        
        $idcategories = array();$idcateg="";$slugsCategories=array();$categoriaImplode=$linkCategoria;
        $idCategories_st="";
        if($categoria=='categorias-online'){
            $slugsCategories=$categoria;
            $categoria = "";
        } elseif ($categoria!="") {
            $modelCategoria = new Application_Model_Categoria();
            
            $slugsCategories = explode("--", $categoria);
            $sql=$modelCategoria->select()->where('slug in (?)', $slugsCategories);
            $rCategoria = $modelCategoria->fetchAll($sql);
            if($rCategoria->count()>=1) {
                foreach ($rCategoria as $value) {
                    $idcateg=$value["id"];
                    $idcategories[$value["id"]]=$value["id"];
                }
                $idCategories_st = implode($idcategories, "-");
            } else {
                $slugsCategories="";
                $categoria = "";
            }
        }
     /*
      *  FIN Adaptador xD
      */
        
        $query = htmlentities($query);
        //var_dump($this->_getAllParams()); exit;
        //$idcateg = $idcategories[count($idcategories)-1];
        //$bolSD = array_search('11', $idcategories)!==false;
        //$bolSR = array_search('12', $idcategories)!==false;
        
        //variables de ini que devuelven el id de las categorias 
        //"Sorteos Disponibles" y "Sorteos Resultados"
        $idSorteos = array(
            'idDisponible'=> $this->getConfig()->categoria_id->sorteo->disponible,
            'idResultado' => $this->getConfig()->categoria_id->sorteo->resultado
        ); 
//        $categoria = '';
//        if ($idcateg==$idSorteos['idDisponible'] || $idcateg==$idSorteos['idResultado']) {
//            foreach ($idcategories as $nro => $idc) {
//                if ($idc==$idSorteos['idDisponible'] || $idc==$idSorteos['idResultado']) {
//                    $categoria.=$idc.'-';
//                }
//            }
//            $categoria = substr($categoria, 0, -1);
//            $idcategories = explode("-", $categoria);
//        }
//        if ($all=='1' || ($idcateg!=$idSorteos['idDisponible'] && $idcateg!=$idSorteos['idResultado'])) {
//            $keyOne = array_search($idSorteos['idDisponible'], $idcategories);
//            $keyTwo = array_search($idSorteos['idResultado'], $idcategories);
//            if ($keyOne !== FALSE) unset($idcategories[$keyOne]);
//            if ($keyTwo !== FALSE) unset($idcategories[$keyTwo]);
//            $idcategories = array_values($idcategories);
//            $categoria = implode('-', $idcategories);
//        }
        $this->view->idCategorias = $idSorteos;
        //var_dump($categoria); exit;
        $cadenaconsulta = ($quebuscas!=""?$quebuscas."/":"nuevo/").
                          ($beneficios!=""?$beneficios."/":"todos/").
                          ($categoria!=""?$categoriaImplode."/":"").
                          ($query!=""?"query/".$query."/":"");
        
        $cadenaconsultaAll = $cadenaconsulta.
                            ($np_slug!=""?$np_slug."/":"");

        $this->view->cadenabusqueda = $cadenaconsulta;
        $this->view->cadenabusquedaAll = $cadenaconsultaAll;
        
        $itemsSeleccionados = array(
            "quebuscas" => array(
                "valor"=> $quebuscas_id,
                "slug"=> $quebuscas,
                "quebuscas_descrip"=> $quebuscas_descrip
            ),
            "beneficios" => array(                
                "nombre"=> $nombrebeneficio,
                "valor"=> $beneficios_id,
                "slug"=> $beneficios
            ),
            "categoria" => array(
                "valor"=> $idcategories,
                "slug"=> $slugsCategories
            )
        );

        $sesion = $this->getSession();
        $sesion->__set("opcionesbusquedas", serialize($itemsSeleccionados));
        $sesion->__set("cadenaconsulta", $cadenaconsulta);

        $this->view->itemsSeleccionados = $itemsSeleccionados;

        Zend_Layout::getMvcInstance()->assign(
            "categoria", (!empty($slugsCategories))?(count($slugsCategories)==1?$slugsCategories[0]:0):""
        );
        Zend_Layout::getMvcInstance()->assign(
            "query", $query
        );

        //Si esta activador lucene procedemos a buscar los Ids.
        $filterLucene = "";
        if (self::$usarLucene) {
            if ($query=='') {
                $awIds = array();
            } else {
                $lucene = new ZendLucene();
                $query = $this->view->LuceneCast($query);
                $filterLucene = $this->view->QueryLucene($query);
                $awIds = $lucene->queryBeneficiosH($filterLucene);
            }
        } else {
            $awIds = false;
        }

        if ($filterLucene == "") {
            $awIds = "";
        }

        $paginator = $this->_beneficio->getBusquedaBeneficios(
            $col, $ord, $quebuscas_id, $beneficios_id, $idCategories_st, $query, $np, $awIds, $idSorteos
        );
        $this->view->MostrandoN = $paginator->getItemCount($paginator->getItemsByPage($page));
        $this->view->MostrandoDe = $paginator->getTotalItemCount();
        $this->view->totalitems = $paginator->getItemCount($paginator->getItemsByPage($page));
        $paginator->setCurrentPageNumber($page);
        $this->view->busqueda_beneficios = $paginator;
        $this->view->ord = $ord;
        $this->view->col = $col;

        $objcategoria = new Application_Model_Categoria();
        $acategoria = explode("-", $idCategories_st);
        $nombrecategoria="";

        for ($i = 0; $i < count($acategoria); $i++) {
            if ($i == 3) {
                //$nombrecategoria = substr($nombrecategoria, 0, -2) . ' y más  ';
                $nombrecategoria = 'Categorías Online..';
                break;
            }
            if ($acategoria[$i]!="") {
                $obj = $objcategoria->find($acategoria[$i]);
                $nombrecategoria .= $obj[0]->nombre . " - ";
            }
        }
        $nombrecategoria = substr($nombrecategoria, 0, strlen($nombrecategoria)-2);
        
        $this->view->nombrecategoria = $nombrecategoria;
        $this->view->messageSearch = 
            'Mostrando '.(empty($quebuscas)?'Lo último':
                ($quebuscas==2?'Lo más consumido':"Lo más visto")).
            (!empty($nombrebeneficio)?' de '.$nombrebeneficio:'').
            (!empty($query)?' con contenido "'.$query.'"':'').
            (!empty($nombrecategoria)?' en: '.$nombrecategoria:'');
        
        $this->view->sufixmedium = $this->getConfig()->beneficios->img->medium;
        $this->view->sufixlittle = "";//$this->getConfig()->beneficios->img->small;
    }

    public function verAction()
    {
        $this->view->headScript()->appendFile(
            $this->mediaUrl.'/js/buscador_beneficios.js'
        );
        $this->view->headScript()->appendFile(
            $this->mediaUrl.'/js/buscador_leftside_beneficios.js'
        );
        $this->view->headScript()->appendFile(
            $this->mediaUrl.'/js/catalogo_detalle.js'
        );
        
        // ruta de busqueda
        $page   = $this->_getParam('page', 1);
        $ord    = $this->_getParam('ord', '');
        $col    = $this->_getParam('col', '');
        $id     = $this->_getParam('id', '');
        $slug   = $this->_getParam('slug', '');
        $np     = $this->_getParam('np', '');
        $np_t     = $this->_getParam('np_t', '');
        
        $strsearch = ($col!=''?''.$col:'').
                     ($ord!=''?'/'.$ord:'');
        $cadenabusqueda = ($col!=''?'/'.$col:'').
                          ($ord!=''?'/'.$ord:'').
                          ($np!=''?'/'.$np.'-'.$np_t:'')
                          ."/";
        $this->view->routeSearch = $strsearch;
        $this->view->cadenabusqueda = $cadenabusqueda;
        
        // recogemos el id del beneficio seleccionado
        $id = $this->getRequest()->getParam('id', '');
//        $nombreBeneficio = $this->_getParam('slug');

        $this->_beneficio->setId($id);
        $this->_beneficio->setPublicado(1);
        $dtbeneficio = $this->_beneficio->getOnlyBeneficio();
        
        $this->view->headTitle()->set($dtbeneficio['titulo']." - Club De Suscriptores El Comercio Perú");
        $this->view->headMeta()->setName("description",
                $dtbeneficio['descripcion_corta']." - Club De Suscriptores El Comercio Perú");
        $this->view->headMeta()->setName("keywords",$dtbeneficio['descripcion_corta'].', '.$dtbeneficio['est_nombre']
                .', '.$dtbeneficio['abreviado'].", beneficios de el comercio, Club De Suscriptores El Comercio Perú");
        
        try {
            if (empty($dtbeneficio)) {
                throw new Exception('Beneficio no disponible. Fuera de vigencia.');
            }
        } catch (Exception $e) {
            $this->getMessenger()->error($e->getMessage());
            $this->_redirect($this->getRequest()->getServer('HTTP_REFERER'));
        }
        
        //aumentamos la cantidad de veces_visto 
        $this->_beneficio->getAumentoVecesVisto();
        
//        $this->view->headTitle("Beneficio: " . $dtbeneficio['titulo']);
        $this->view->tipobeneficio = $dtbeneficio['tipo_beneficio_id'];
        $this->view->titulo_detalle = ($dtbeneficio['tipo_beneficio_id'] == Application_Model_Beneficio::TIPO_PROMO ? 
            'Detalle de la Promo' : 'Detalle del Beneficio');
        $this->view->titulo_relacionados = 
            ($dtbeneficio['tipo_beneficio_id'] == Application_Model_Beneficio::TIPO_PROMO ? 
            'Los que pidieron está promo, también pidieron:' : 
            'Otros beneficios de la misma categoria:');
        //Los que vieron este beneficio, también vieron esto:
        //$this->view->assign('nombre', $nombreBeneficio);
        // ==> Validación Categoria Sorteo Disponible
        $objcatB = new Application_Model_CategoriaBeneficio();
        $idSorteoDisponible = $this->getConfig()->categoria_id->sorteo->resultado;
        $dtbeneficio['sorteo'] = $objcatB->validCategoriaSorteoByBenef($id, $idSorteoDisponible);
        $this->view->beneficio = $dtbeneficio;
        
        $this->_cupon->setBeneficio_id($dtbeneficio['id']);
        $this->_cupon->setSuscriptor_id(!empty($this->auth['suscriptor']) ? $this->auth['suscriptor']['id'] : 0);
        $maximo_permitido = $this->getConfig()->beneficios->detalle->nrocupones;
        $this->view->maximo_permitido = $maximo_permitido;
        if ( $dtbeneficio['sin_limite_por_suscriptor'] != 1 ) {
            $nrocuponesgen = $this->_cupon->getNroCuponesGenBySuscrip();
            $maximo_permitido = ($dtbeneficio['maximo_por_subscriptor'] - $nrocuponesgen);
            $this->view->maximo_permitido = $maximo_permitido;
        } 
        if ( $dtbeneficio['sin_stock'] != 1 ) {
            if ($maximo_permitido > $dtbeneficio['stock_actual']) {
                $this->view->maximo_permitido = ($dtbeneficio['stock_actual']);
            }
        }
        
        $page = $this->_getParam('page', 1);
        $nropaginasrelacionados = ($np > 0) ? $np : $this->getConfig()->beneficios_relacionados->promos->nropaginas;

        $nrofil = 0;
        if ($dtbeneficio['tipo_beneficio_id'] == Application_Model_Beneficio::TIPO_PROMO):
            $zp = Zend_Paginator::factory(
                $this->_beneficio->getBeneficiosRelacionados(Application_Model_Beneficio::FILTRO_POR_CUPON, $ord, $col)
            );
            $paginator = $zp->setItemCountPerPage($nropaginasrelacionados);
            $nrofil = $paginator->getAdapter()->count();
        endif;
        if (empty($nrofil) or $dtbeneficio['tipo_beneficio_id'] == 
            Application_Model_Beneficio::TIPO_BENEFICIO):
            $zp = Zend_Paginator::factory(
                $this->_beneficio->getBeneficiosRelacionados(
                    Application_Model_Beneficio::FILTRO_POR_CATEGORIA, $ord, $col
                )
            );
            $paginator = $zp->setItemCountPerPage($nropaginasrelacionados);
        endif;

        $this->view->mostrando = "Mostrando "
            . $paginator->getItemCount($paginator->getItemsByPage($page)) . " de "
            . $paginator->getTotalItemCount();
        $this->view->totalitems = $paginator->getItemCount($paginator->getItemsByPage($page));
        $this->view->MostrandoN = $paginator->getItemCount($paginator->getItemsByPage($page));
        $this->view->MostrandoDe = $paginator->getTotalItemCount();
        $paginator->setCurrentPageNumber($page);
        $this->view->beneficios_relacionados = $paginator;
        $this->view->ord  = $ord;
        $this->view->col  = $col;
        $this->view->id   = $id;
        $this->view->slug = $slug;

        $sesion = $this->getSession();
        $cadenaconsulta = "";
        $items = array(
            "quebuscas" => array(
                "valor"=> 0,
                "slug"=> "nuevo"
            ),
            "beneficios" => array(
                "valor"=> 0,//$dtbeneficio['tbenef_id'],
                "slug"=> 0//$dtbeneficio['tbenef_slug']
            ),
            "categoria" => array(
                "valor"=> array(),//array($dtbeneficio['cat_id']),
                "slug"=> 0//array($dtbeneficio['cat_slug'])
            )
        );
//        if ($sesion->__get("opcionesbusquedas")!="") {
//            $items = (unserialize($sesion->__get("opcionesbusquedas")));
//            $cadenaconsulta = ($sesion->__get("cadenaconsulta"));
//        }
//        $sesion = $this->getSession();
//        $sesion->__set("opcionesbusquedas", serialize($itemsSeleccionados));
//        $sesion->__set("cadenaconsulta", $cadenaconsulta);
        
        $itemsSeleccionados = $items; 
        $this->view->itemsSeleccionados = $itemsSeleccionados;
        $this->view->cadenaconsulta = $cadenaconsulta;
        
        $this->view->sufixmedium = $this->getConfig()->beneficios->img->medium;
        $this->view->sufixlittle = "";//$this->getConfig()->beneficios->img->small;
        
        $objcatbenef = new Application_Model_CategoriaBeneficio();
        $this->view->catbenef = $objcatbenef;        
    }

    public function verListaAction()
    {
        $this->verAction();
    }
    
    public function quieroLaPromoAction()
    {
        //validamos si los cupones generados se encuentran en session
        $session = $this->getSession();
        $cuponsgen = $session->__get("cuponesTemp");
        if (empty($this->auth['suscriptor']) || empty($cuponsgen)) {
            $this->_redirect('/');
        }
        
        $this->view->headScript()->appendFile(
            $this->mediaUrl.'/js/buscador_beneficios.js'
        );
        
        $id = $this->_getParam('id');
        $this->_beneficio->setId($id);

        //datos del beneficio
        //$dtbeneficio = $this->getAdapter()->fetchRow($this->_beneficio->getBeneficio());
        $dtbeneficio = $this->_beneficio->getOnlyBeneficio();

        $this->view->headTitle(
            "Cupon - " . $dtbeneficio['titulo']
        );

        $this->view->beneficio = $dtbeneficio;

        //mostrando los cupones generados
        $this->_cupon->setBeneficio_id($id);
        $this->_cupon->setSuscriptor_id($this->auth['suscriptor']['id']);
        $this->view->cuponesgenerados = $this->_cupon->getCuponesByBenefSuscripGen();
        $this->view->previous = $this->getRequest()->getServer('HTTP_REFERER');
        
        $this->view->sufixlittle = "";//$this->getConfig()->beneficios->img->small;
    }

    public function aplicaPromoAction()
    {
        $this->_helper->viewRenderer->setNoRender();
        $this->view->headScript()->appendFile(
            $this->mediaUrl.'/js/buscador_beneficios.js'
        );
        
        $id = $this->_getParam('hdbeneficio_id');
        $nrosolicitado = $this->_getParam('hdcantidad');
        $slug = $this->_getParam('hdslug');
        if (empty($nrosolicitado)) {
            $id = $this->_getParam('beneficio_id');
            $nrosolicitado = $this->_getParam('ctrlnumeropromos');
            $slug = $this->_getParam('slug');
        }
        $objbeneficioVersion = new Application_Model_BeneficioVersion($id);
        $this->_beneficio->setId($id);
        $this->_beneficio->setPublicado(1);
        $this->_beneficio->setFecha_publicacion();
        $dtbeneficio = $this->_beneficio->getOnlyBeneficio();
        $this->_beneficioVersion->setBeneficio_id($id);
        $stockMaxPorSuscriptor=$this->_beneficioVersion->getDevolverStockActualAndCuponConsumido();
        $this->_cupon->setBeneficio_id($id);
        $this->_cupon->setSuscriptor_id($this->auth['suscriptor']['id']);
        $cuponesConsumidoActualPorSuscriptor=$this->_cupon->getNroCuponesGenBySuscrip();
        
        try {
            if (empty($dtbeneficio)) {
                throw new Exception('Beneficio no disponible. Fuera de vigencia.');
            } else if ($dtbeneficio["sin_limite_por_suscriptor"] != 1) {
                if ($dtbeneficio["sin_limite_por_suscriptor"] == 0 && $dtbeneficio["maximo_por_subscriptor"] <= 0) {
                    throw new Exception('Stock de Descuentos Agotado');
                } elseif ($dtbeneficio["maximo_por_subscriptor"] <= $cuponesConsumidoActualPorSuscriptor) {
                    throw new Exception('No puede Generar Cupón para este suscriptor. Cupones ya fueron generados.');
                } elseif ($stockMaxPorSuscriptor < $this->_beneficio->getNrocupones()) {
                    throw new Exception('Debe seleccionar una cantidad menor o igual a: ' . $stockMaxPorSuscriptor);
                } elseif ($dtbeneficio["maximo_por_subscriptor"] < $nrosolicitado) {
                    throw new Exception(
                        'Debe seleccionar una cantidad menor o igual a: ' . $dtbeneficio["maximo_por_subscriptor"] . '.'
                    );
                } elseif ($dtbeneficio["sin_stock"] != 1 && $dtbeneficio["stock_actual"]<1) {
                    throw new Exception(
                        'Lo sentimos, el stock se ha agotado..'
                    );
                } elseif ($dtbeneficio["sin_stock"] != 1 && $dtbeneficio["stock_actual"] < $this->_beneficio->getNrocupones()) {
                    throw new Exception(
                        'Lo sentimos, no hay suficiente stock para completar tu pedido.'
                    );
                }
            }
        } catch (Exception $e) {
            $this->getMessenger()->error($e->getMessage());
            $this->_redirect($this->getRequest()->getServer('HTTP_REFERER'));
        }
            
        try {
            $db = $this->getAdapter();
            $db->beginTransaction();
            $objsuscriptor = new Application_Model_Suscriptor();
            $objsuscriptorBeneficio = new Application_Model_SuscriptorBeneficio();
            
            $valuescupon['suscriptor_id'] = $this->auth['suscriptor']['id'];
            $valuescupon['beneficio_id'] = $id;
            $valuescupon['fecha_emision'] = $this->_fecha->toString('YYYY-MM-dd HH:mm:ss');
            $valuescupon['fecha_inicio_vigencia'] = 
                         $objbeneficioVersion->getFecha_inicio_vigencia();
            $valuescupon['fecha_fin_vigencia'] = $objbeneficioVersion->getFecha_fin_vigencia();
            $valuescupon['estado'] = 'Generado';
            
            $cupones = array();
            for ($nro = 1; $nro <= $nrosolicitado; $nro++):
                //$valuescupon['codigo'] = date('Ymdhis').$nro.rand(1, 9);
                $valuescupon['codigo'] = $this->_helper->CodigoCupon->generar();
                $this->_cupon->insert($valuescupon);
                $cupones[] = $valuescupon;
            endfor;
            
            $where['suscriptor_id'] = $this->auth['suscriptor']['id'];
            $where['beneficio_id'] = $id;
            $nronow = $objsuscriptorBeneficio->getCuponesGenerado(
                $where['beneficio_id'], $where['suscriptor_id']
            );
            if ( $nronow>=0 ) {
                $registro['cupon_generado'] = $nronow + $nrosolicitado;
                $objsuscriptorBeneficio->update($registro, $where);
            } else {
                $registro['cupon_consumido'] = 0;
                $registro['cupon_generado'] = $nrosolicitado;
                $registro['suscriptor_id'] = $where['suscriptor_id'];
                $registro['beneficio_id'] = $where['beneficio_id'];
                $objsuscriptorBeneficio->insert($registro);
            }
            
            $session = $this->getSession();
            $session->__set("cuponesTemp", serialize($cupones));
            
            
            $rsSuscrip = $objsuscriptor->getSuscriptor($this->auth['suscriptor']['id']);
            
            $dtbeneficio['elementsUrl'] = $this->getConfig()->app->elementsUrl;
            $dtbeneficio['sufixmedium'] = $this->getConfig()->beneficios->img->medium;//->logo->sufix->medium;
            /*enviamos el correo al usuario*/
            $values['to'] = $this->auth['usuario']->email;
            $values['beneficio'] = $dtbeneficio;
            $values['datos_suscriptor'] = 
                $rsSuscrip['nombres'].' '.$rsSuscrip['apellidos'].
                ' '.$rsSuscrip['tipo_documento'].': '.
                $rsSuscrip['numero_documento'];
            $values['cupones'] = $cupones;
            $this->_helper->Mail->agradecimientoPedido($values);
            
//            $db->commit();
            
            //disminuir el stock del beneficio y generar los cupones
            $this->_beneficioVersion->setDisminuyeStockActual($nrosolicitado);
            $db->commit();
            
            $this->_redirect('/quiero-la-promo/' . $id . '/' . $slug);
        } catch (Exception $e) {
            $db->rollBack();
            $this->getMessenger()->error($e->getMessage());
            $this->_redirect($this->getRequest()->getServer('HTTP_REFERER'));
        }
    }

    public function jsonAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $case = $this->getRequest()->getParam('case', '');
        
        $titleBeneficiario = $this->getConfig()->messages->quierolapromo->noaplica->titulo;
        $messageBeneficiario = $this->getConfig()->messages->quierolapromo->noaplica->message;
        
        $titleInactivo = $this->getConfig()->messages->quierolapromo->inactivo->titulo;
        $messageInactivo = $this->getConfig()->messages->quierolapromo->inactivo->message;
        
        $titleSinSotck = $this->getConfig()->messages->quierolapromo->sinstock->titulo;
        $messageSinSotck = $this->getConfig()->messages->quierolapromo->sinstock->message;
        
        $titleNoSuscriptor = $this->getConfig()->messages->quierolapromo->nosuscriptor->titulo;
        $messageNoSuscriptor = $this->getConfig()->messages->quierolapromo->nosuscriptor->message;
        
        $titleCuponesGenerados = $this->getConfig()->messages->quierolapromo->nosuscriptor->titulo;
        $messageCuponesGenerados = "<p class='text1 f18 alignC'>No puedes Generar" . 
            " Cupón. Tus Cupones ya fueron generados";
        switch ($case):
            case 'quierolapromo':
                $envio = array();
                $envio['login'] = ($this->isAuth && !empty($this->auth['suscriptor']));
                $envio['message'] = '';
                $envio['mensaje'] = false;
                $envio['popup'] = false;
                $envio['url'] = '';
                $envio['title'] = '';
                
                if ($this->getRequest()->isPost()) {
                    //validamos si el suscriptor esta activo
                    if( empty($this->auth['suscriptor']['es_suscriptor']) && 
                        empty($this->auth['suscriptor']['es_invitado']) ):
                        
                        $envio['popup'] = true;
                        $envio['mensaje'] = true;
                        $envio['message'] = $messageNoSuscriptor;
                        $envio['title'] = $titleNoSuscriptor;
                    else:
                        
                        $id = $this->_getParam('beneficio_id', '');
                        $nrocupones = $this->_getParam('ctrlnumeropromos', '');
                        $slug = $this->_getParam('slug', '');
                        $this->_beneficio->setId($id);
                        $this->_beneficio->setNrocupones($nrocupones);
                            
                        if(!empty($this->auth['suscriptor']['activo'])):                            
                            //validamos si el numero de cupones que se solicita, 
                            //cumpla con el stock actual.                            
                            $validstock = $this->_beneficio->getValidStock($envio);

                            if ( $validstock['nropedido_enviado'] <= 0 ) :
                                $validstock['mensaje'] = true;
                                $validstock['message'] = $messageSinSotck;
                                $validstock['title'] = $titleSinSotck;
                            endif;
                            /*variables retornadas al ajax en json*/
                            $envio = $validstock;
                            $envio['url'] = $this->_getParam('ruta', '');
                            $envio['id'] = $id;
                            $envio['slug'] = $slug;

                            //es suscriptor
                            if( $this->auth['suscriptor']['es_suscriptor']==
                                Application_Model_Suscriptor::ESTADO_ACTIVO OR 
                                $this->auth['suscriptor']['es_invitado']==
                                Application_Model_Suscriptor::ESTADO_INACTIVO ):

                            //es invitado
                            elseif( $this->auth['suscriptor']['es_suscriptor']==
                                    Application_Model_Suscriptor::ESTADO_INACTIVO OR 
                                    $this->auth['suscriptor']['es_invitado']==
                                    Application_Model_Suscriptor::ESTADO_ACTIVO ):
                                $idSusPad = $this->auth['suscriptor']['suscriptor_padre_id'];
                                $suscripadre = Application_Model_Suscriptor::
                                               getSuscriptorPadrePorBeneficiario(
                                                   (!empty($idSusPad)?$idSusPad:'')
                                               );
                                //padre inactivo
                                if(empty($suscripadre['activo'])):
                                    $envio['popup'] = true;
                                    $envio['mensaje'] = true;
                                    $envio['message'] = $messageBeneficiario;
                                    $envio['title'] = $titleBeneficiario;
                                endif;
                            endif;                          
                        else:
                            $envio['popup'] = true;
                            $envio['mensaje'] = true;
                            $envio['message'] = $messageInactivo;
                            $envio['title'] = $titleInactivo;
                        endif;
                        
                        
                        $dtbeneficio = $this->_beneficio->getOnlyBeneficio();
                        $this->_beneficioVersion->setBeneficio_id($id);
    //                    $stockTotalActual=$this->_beneficioVersion->getDevolverStockActualAndCuponConsumido();
                        $this->_cupon->setBeneficio_id($id);
                        $this->_cupon->setSuscriptor_id($this->auth['suscriptor']['id']);
                        $cuponesConsumidoActualPorSuscriptor=$this->_cupon->getNroCuponesGenBySuscrip();
                        $stockMaxPorSuscriptor
                            = ($dtbeneficio["maximo_por_subscriptor"] - $cuponesConsumidoActualPorSuscriptor);
                        
                        if($dtbeneficio["sin_limite_por_suscriptor"] != 1):
                            if($dtbeneficio["sin_limite_por_suscriptor"] == 0 
                                && $dtbeneficio["maximo_por_subscriptor"]<=0):
                                $envio['popup'] = true;
                                $envio['mensaje'] = true;
                                $envio['message'] = $messageSinSotck;
                                $envio['title'] = $titleSinSotck;
                            elseif( $dtbeneficio["maximo_por_subscriptor"] <= $cuponesConsumidoActualPorSuscriptor):
                                $envio['popup'] = true;
                                $envio['mensaje'] = true;
                                $envio['message'] = $messageCuponesGenerados
                                    . '. Maximo por suscriptor: ' . $dtbeneficio["maximo_por_subscriptor"] . '.';
                                $envio['title'] = $titleCuponesGenerados;
                            elseif($stockMaxPorSuscriptor < $this->_beneficio->getNrocupones()):
                                $envio['popup'] = true;
                                $envio['mensaje'] = false;
                                $envio['nropedido_enviado']=$stockMaxPorSuscriptor;
                            elseif($dtbeneficio["maximo_por_subscriptor"] < $this->_beneficio->getNrocupones()):
                                $envio['popup'] = true;
                                $envio['mensaje'] = false;
                                $envio['nropedido_enviado']=$dtbeneficio["maximo_por_subscriptor"];
                            endif;
                        endif;
                        if($dtbeneficio["sin_stock"] != 1 && $envio['popup'] == false):
                            if($dtbeneficio["stock_actual"]<=0):
                                $envio['popup'] = true;
                                $envio['mensaje'] = true;
                                $envio['message'] = $messageSinSotck;
                                $envio['title'] = $titleSinSotck;                            
                            elseif( $dtbeneficio["stock_actual"] < $this->_beneficio->getNrocupones()):
                                $envio['popup'] = true;
                                $envio['mensaje'] = false;
                                $envio['nropedido_enviado']=$dtbeneficio["stock_actual"];
                            endif;
                        endif;
                    endif;
                }
                $this->_response->appendBody(Zend_Json::encode($envio));
                break;
            default:
                break;
        endswitch;
    }

    public function listaAction()
    {
        $this->indexAction();
    }
    
    public function listaDetalleAction()
    {
        $this->verAction();
    }

    public function imprimirCuponAction() 
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        
        $objsuscriptor = new Application_Model_Suscriptor();
        $rsSuscrip = $objsuscriptor->getSuscriptor($this->auth['suscriptor']['id']);
        
        $idBeneficio = $this->getRequest()->getPost("beneficio_id");
        $this->_beneficio->setId($idBeneficio);
        
        $session = $this->getSession();
        $arreglo = unserialize($session->__get("cuponesTemp"));
        
        $this->view->cuponesgenerados = $arreglo;
        $this->view->datos_suscriptor = 
                $rsSuscrip['nombres'].' '.$rsSuscrip['apellidos'].', '.$rsSuscrip['tipo_documento'].' '.
                $rsSuscrip['numero_documento'];
        
        $this->view->beneficio = $this->_beneficio->getOnlyBeneficio();
                
        //$domPdf = $this->_helper->getHelper('DomPdf');
        $html = $this->view->render('beneficio/imprimir-cupon-ok.phtml');
        /*$mvc = Zend_Layout::getMvcInstance();
        $layout = $mvc->render('cupon_pdf');
        $layout = str_replace("<!--cupones-->", $html, $layout);
        $layout = str_replace("\"", "'", $layout);
        $domPdf->mostrarPDF($html, 'A4', "portrait", "cupones.pdf");*/
        
        $bin = $this->getConfig()->wkhtml->bin->path;

        try {
            $wkhtmltopdf = new Wkhtmltopdf(
                array('path' => ELEMENTS_ROOT . '/pdfs_tmp/','binpath' => $bin)
            );
            $wkhtmltopdf->setTitle("Cupón Generado");
            $wkhtmltopdf->setZoom("1.3");
            //echo $html; exit;            
            $wkhtmltopdf->setHtml($html);
            $wkhtmltopdf->output(Wkhtmltopdf::MODE_EMBEDDED, "src/public/cupones.pdf");
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function imprimirCuponOkAction() 
    {
    }

    public function ladAction()
    {
        $this->_helper->layout->setLayout('lad');
        $this->getRequest()->setParam('esVistaLucene', true);
        $this->_forward('verlucene');
    }

    public function verluceneAction()
    {
        $this->verAction();
    }
    
    public function verHtmlGeneradoAction() 
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ruta = $this->getRequest()->getParam('ruta', '');
        if ($ruta!="") {
            $client = new Zend_Http_Client();
            $client->setConfig(array('timeout'=>60));
            $client->setUri(ELEMENTS_URL."/".$ruta);
            $html = $client->request(Zend_Http_Client::GET)->getBody();
            echo $html;
            exit;
        }
    }

    public function resultadoAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $codBeneficio = $this->_getParam('cod_beneficio', null);
        if (!empty($codBeneficio)) {
            $beneficio = Application_Model_Beneficio::getBeneficioByCodigo($codBeneficio);
//                var_dump($beneficio);exit;
            if (!empty($beneficio['pdf_file'])) {
                $enlace = $this->config->paths->elementsSorteoRoot . $beneficio['pdf_file'];
//                var_dump($enlace);exit;
                if (file_exists($enlace)) {
//                    $pdf = Zend_Pdf::load($enlace);
                    $this->getResponse()->setHeader('Content-type', 'application/x-pdf', true);
                    $this->getResponse()->setHeader('Content-disposition', 'inline; filename=resultados.pdf', true);
                    $this->getResponse()->setBody(file_get_contents($enlace));
                } else {
                    $this->getMessenger()->error('El archivo seleccionado no existe.');
                    $this->_redirect('/');
                }
            } else {
                $this->getMessenger()->error('El url seleccionado no es válido.');
                $this->_redirect('/');
            }
        } else {
            $this->getMessenger()->error('El url seleccionado no es válido.');
            $this->_redirect('/');
        }
    }
    
    public function pdfinformacionAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $codBeneficio = $this->_getParam('cod_beneficio', null);
        if (!empty($codBeneficio)) {
            $beneficio = Application_Model_Beneficio::getBeneficioByCodigo($codBeneficio);
//                var_dump($beneficio);exit;
            if (!empty($beneficio['pdf_info_file'])) {
                $enlace = $this->config->paths->elementsSorteoRoot . $beneficio['pdf_info_file'];
//                var_dump($enlace);exit;
                if (file_exists($enlace)) {
//                    $pdf = Zend_Pdf::load($enlace);
                    $this->getResponse()->setHeader('Content-type', 'application/x-pdf', true);
                    $this->getResponse()->setHeader('Content-disposition', 'inline; filename=informacion.pdf', true);
                    $this->getResponse()->setBody(file_get_contents($enlace));
                } else {
                    $this->getMessenger()->error('El archivo seleccionado no existe.');
                    $this->_redirect('/');
                }
            } else {
                $this->getMessenger()->error('El url seleccionado no es válido.');
                $this->_redirect('/');
            }
        } else {
            $this->getMessenger()->error('El url seleccionado no es válido.');
            $this->_redirect('/');
        }
    }

}