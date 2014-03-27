<?php
/**
 * Libreria para manejo de Zend Search Lucene
 * @author Solman Vaisman Gonzalez
 */
class ZendLucene
{
    //BUSCADOR PARA BENEFICIOS
    protected $_index;
    protected $_ruta;

    //N ZEROS
    protected $_nzeros;

    //N PAGINADOS
    protected $_nPaginadoBeneficio=1;
    protected $_specialSearchChars = '"';

    protected $_encolador;
    protected $_readOnly = false;
    protected $_config;

    public function  __construct($index='ALL')
    {
        $this->init();
        $this->_config = Zend_Registry::get("config");
        
        Zend_Search_Lucene_Analysis_Analyzer::setDefault(
            new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8Num_CaseInsensitive()
        );
        $this->_ruta = APPLICATION_PATH."/../indexes/buscador.beneficios";
        $this->_encolador = new ZendLucene_TempWriter();
        $this->_readOnly = $this->_config->lucene->readOnly;
        
        if ($index=="ALL" || $index=="beneficios") {
            if ($this->index_exists()) {
                if (is_readable($this->_ruta)) {
                    $this->_index = Zend_Search_Lucene::open($this->_ruta);
                } else {
                    throw new Zend_Exception("No se pueden leer los indices de Zend_Lucene");
                }
            }
        }
    }

    function load_Indexes($n)
    {
        echo "Creando Indices ZendLucene en proyecto Club del Subscriptor...\n";
        try {
            switch ($n) {
                case "beneficios":
                    $this->_index = Zend_Search_Lucene::create($this->_ruta);
                    $this->makeIndexesBeneficios2();
                    echo "Indice de Beneficios...................[OK]".PHP_EOL;
                    /*if(chmod($this->_ruta, 0777))
                        echo "Se asignaron permisos 0777 para:".$this->_ruta.PHP_EOL;*/
                    break;
            }
        } catch (Zend_Search_Lucene_Exception $e) {
            echo $e->getMessage().PHP_EOL;
            echo $e->getTraceAsString().PHP_EOL;
        } catch (Zend_Search_Exception $e) {
            echo $e->getMessage().PHP_EOL;
            echo $e->getTraceAsString().PHP_EOL;
        } catch (Zend_Exception $e) {
            echo $e->getMessage().PHP_EOL;
            echo $e->getTraceAsString().PHP_EOL;
        } catch (Exception $e) {
            echo $e->getMessage().PHP_EOL;
            echo $e->getTraceAsString().PHP_EOL;
        }
    }

    private function init()
    {
        $this->_nzeros=10;
    }

    function index_exists()
    {
        if (file_exists($this->_ruta)) {
            return true;
        } else {
            return false;
        }
    }

    private function addDocumentBeneficio($value)
    {
        $doc = new Zend_Search_Lucene_Document();
        $doc->addField(
            Zend_Search_Lucene_Field::Keyword('idbeneficio', $value["idbeneficio"])
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Keyword(
                'idestablecimiento', $value["establecimiento_id"]
            )
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Keyword(
                'idtipobeneficio', $value["tipo_beneficio_id"]
            )
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Keyword(
                'idcategoria', $value["idcategoria"], "UTF-8"
            )
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Text('titulo', $value["titulo"], "UTF-8")
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Text('descripcioncorta', $value["descripcion"], "UTF-8")
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Text('valor', $value["valor"], "UTF-8")
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Text('cuando', $value["cuando"], "UTF-8")
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Text('direccion', $value["direccion"], "UTF-8")
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Text('emailinfo', $value["email_info"], "UTF-8")
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Text('telefonoinfo', $value["telefono_info"], "UTF-8")
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Text('pathlogo', $value["path_logo"], "UTF-8")
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Keyword(
                'maximoporsubscriptor', $value["maximo_por_subscriptor"], "UTF-8"
            )
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Keyword('sinstock', $value["sin_stock"], "UTF-8")
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Keyword('esdestacado', $value["es_destacado"], "UTF-8")
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Keyword(
                'esdestacadoprincipal', $value["es_destacado_principal"], "UTF-8"
            )
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Keyword('activo', $value["activo"], "UTF-8")
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Text(
                'vecesvisto', $this->fillZeroField($value["veces_visto"]), "UTF-8"
            )
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Text(
                'fecharegistro', $value["fecha_registro"], "UTF-8"
            )
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Text('chapita', $value["chapita"], "UTF-8")
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Text('chapitacolor', $value["chapita_color"], "UTF-8")
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Text('slug', $value["slug"], "UTF-8")
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Text(
                'fechainiv', $value["fecha_inicio_vigencia"], "UTF-8"
            )
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Text(
                'fechafinv', $value["fecha_fin_vigencia"], "UTF-8"
            )
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Keyword('stockactual', $value["stock_actual"], "UTF-8")
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Keyword('stock', $value["stock"], "UTF-8")
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Text(
                'nombre', $value["nombre"], "UTF-8"
            )
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Text(
                'abreviado', $value["abreviado"], "UTF-8"
            )
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Text(
                'catnombre', $value["cat_nombre"], "UTF-8"
            )
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Text(
                'estnombre', $value["est_nombre"], "UTF-8"
            )
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Text(
                'categorias', $value["categorias"], "UTF-8"
            )
        );
        $doc->addField(
            Zend_Search_Lucene_Field::keyword(
                'publicado', $value["publicado"], "UTF-8"
            )
        );
        $doc->addField(
            Zend_Search_Lucene_Field::keyword(
                'generarcupon', $value["generar_cupon"], "UTF-8"
            )
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Text(
                'ncuponesconsumidos', 
                $value["ncuponesconsumidos"]==null?0:
                $this->fillZeroField($value["ncuponesconsumidos"]),
                "UTF-8"
            )
        );
        $this->_index->addDocument($doc);
        $doc = null;
    }
    
    private function addDocumentBeneficioZL($value)
    {
        $doc = new Zend_Search_Lucene_Document();
        $doc->addField(
            Zend_Search_Lucene_Field::Keyword('idbeneficio', $value->idbeneficio, "UTF-8")
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Keyword(
                'idestablecimiento', $value->idestablecimiento, "UTF-8"
            )
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Keyword(
                'idtipobeneficio', $value->idtipobeneficio, "UTF-8"
            )
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Keyword(
                'idcategoria', $value->idcategoria, "UTF-8"
            )
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Text('titulo', $value->titulo, "UTF-8")
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Text('descripcioncorta', $value->descripcioncorta, "UTF-8")
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Text('valor', $value->valor, "UTF-8")
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Text('cuando', $value->cuando, "UTF-8")
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Text('direccion', $value->direccion, "UTF-8")
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Text('emailinfo', $value->emailinfo, "UTF-8")
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Text('telefonoinfo', $value->telefonoinfo, "UTF-8")
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Text('pathlogo', $value->pathlogo, "UTF-8")
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Keyword(
                'maximoporsubscriptor', $value->maximoporsubscriptor, "UTF-8"
            )
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Keyword('sinstock', $value->sinstock, "UTF-8")
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Keyword('esdestacado', $value->esdestacado, "UTF-8")
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Keyword(
                'esdestacadoprincipal', $value->esdestacadoprincipal, "UTF-8"
            )
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Keyword('activo', $value->activo, "UTF-8")
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Keyword('generarcupon', $value->generarcupon, "UTF-8")
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Keyword('vecesvisto', $value->vecesvisto, "UTF-8")
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Text(
                'fecharegistro', $value->fecharegistro, "UTF-8"
            )
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Text('chapita', $value->chapita, "UTF-8")
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Text('chapitacolor', $value->chapitacolor, "UTF-8")
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Text('slug', $value->slug, "UTF-8")
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Text(
                'fechainiv', $value->fechainiv, "UTF-8"
            )
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Text(
                'fechafinv', $value->fechafinv, "UTF-8"
            )
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Keyword('stockactual', $value->stockactual, "UTF-8")
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Keyword('stock', $value->stock, "UTF-8")
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Text(
                'nombre', $value->nombre, "UTF-8"
            )
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Text(
                'abreviado', $value->abreviado, "UTF-8"
            )
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Text(
                'catnombre', $value->catnombre, "UTF-8"
            )
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Text(
                'estnombre', $value->estnombre, "UTF-8"
            )
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Text(
                'categorias', $value->categorias, "UTF-8"
            )
        );
        $doc->addField(
            Zend_Search_Lucene_Field::Text(
                'ncuponesconsumidos', $value->ncuponesconsumidos, "UTF-8"
            )
        );
        $doc->addField(
            Zend_Search_Lucene_Field::keyword(
                'publicado', $value->publicado, "UTF-8"
            )
        );
        $this->_index->addDocument($doc);
        $doc = null;
    }

    private function makeIndexesBeneficios()
    {
        $_beneficio = new Application_Model_Beneficio();
        $n = $_beneficio->getCountBeneficios();
        $n = $n["0"]["n"];
        echo "\nNBeneficios: ".$n.PHP_EOL;
        $valor = $this->_nPaginadoBeneficio;
        if ($n<$valor) {
            $valor = $n;
        }
        
        for ($i=0;$i<$n;$i+=$valor) {
            $result = $_beneficio->getRellenarIndexBeneficios($i, $valor);
            foreach ($result as $key=>$value) {
                try {
                    $this->agregarNuevoDocumentoBeneficio($value["idbeneficio"]);
                } catch(Zend_Search_Lucene_Document_Exception $ex) {
                    echo "Error Linea:(".$ex->getLine().") archivo:(".$ex->getFile().") 
                          Agregando a ZL: ".$ex->getMessage();
                }
            }
            unset($result);

            $time = new DateTime();
            echo "[".($i+$valor)." de $n] Indices de Beneficios..............."
                .((($i+$valor)/$n)*100)." % Completado a las:".$time->format("d/m/Y H:i:s").PHP_EOL;
            $time = null;
        }
        $this->_index->commit();
        $this->_index->optimize();
        $timeFinal = new DateTime("now");
        echo PHP_EOL."Finaliza proceso a las:".$timeFinal->format("d/m/Y H:i:s").PHP_EOL.PHP_EOL;

        $this->_index->commit();
        $this->_index->optimize();
    }




    function makeIndexesBeneficios2()
    {
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $mBeneficio = new Application_Model_Beneficio();
        $sql = $mBeneficio->select()->from(
            $mBeneficio->info('name'), array(
                'id', 'slug'
            )
        )->where('activo=1');
        $anunciosActivos = $db->fetchPairs($sql);
        $cont = 1;
        $total = count($anunciosActivos);
        foreach ($anunciosActivos as $awid => $urlId) {
            $urlHelper = new Zend_View_Helper_Url();
            $url = $urlHelper->url(array('id'=>$awid), 'lucene_ad', true);

            if (1) { // obteniendo el HTML con Zend_Client
                $client = new Zend_Http_Client();
                $client->setConfig(array('timeout'=>60));
                $client->setUri(SITE_URL.$url);
                $html = $client->request(Zend_Http_Client::GET)->getBody();
                $client = null;
            } else { // obteniendo el HTML con Zend_Controller_Front
                $req = new Zend_Controller_Request_Http();
                $req->setRequestUri($url);
                $f = Zend_Controller_Front::getInstance();
                $f->setRequest($req);
                $f->returnResponse(true);
                $html = $f->dispatch()->getBody();
                $req->clearParams();
                $f->clearParams();
            }
            $doc = Zend_Search_Lucene_Document_Html::loadHTML($html, false, "UTF-8");
            $doc->addField(Zend_Search_Lucene_Field::keyword('idbeneficio', $awid));
            $doc->addField(Zend_Search_Lucene_Field::keyword('urlId', $urlId));
            $this->_index->addDocument($doc);
            $doc = null;
            $time = new DateTime();
            echo $cont." de $total Beneficios ..............".$time->format("d/m/Y H:i:s").PHP_EOL;
            $time = null;
            /*file_put_contents(
                APPLICATION_PATH."/../indexes/html/".date('HmdHis').
                "-$urlId.html", $html
            );*/
            $cont++;
        }
        $this->_index->commit();
        $this->_index->optimize();
    }

    public function queryBeneficiosH($query)
    {

        Zend_Search_Lucene_Search_QueryParser::setDefaultEncoding('utf-8');
        $userQuery = Zend_Search_Lucene_Search_QueryParser::parse($query);
        $query = new Zend_Search_Lucene_Search_Query_Boolean();
        $query->addSubquery($userQuery, true);

        $result = @$this->_index->find($query);

        $resultBeneficiosIds = array();
        $scores = array();
        foreach ($result as $value) {
            //$d = $value->getDocument();
            //var_dump($d);
            //var_dump($d->getFieldNames());
            //exit;
            //var_dump($d->getField('awid')->value);
            //var_dump($d->getField('urlId')->value);
            //var_dump($d->getField('title')->value);
            $awid = (int) $value->getDocument()->getField('idbeneficio')->value;
            $resultBeneficiosIds[] = $awid;
            $scores[$awid] = $value->score;
        }
        $sessionlucene = new Zend_Session_Namespace('lucene');
        $sessionlucene->scores = $scores;
        $sessionlucene->setExpirationHops(1, 'scores');
        return $resultBeneficiosIds;
    }
    
    function queryBeneficios($query, $order)
    {
        $aSort = explode(" ", $order[0]);
        $bSort = explode(" ", $order[1]);
        $q = Zend_Search_Lucene_Search_QueryParser::parse($query);
        //con 2 order
        if ($order[0]!="" && $order[1]!="") {
            echo $bSort[0];
            $result = $this->_index->find(
                $q,
                $aSort[0],
                ($aSort[1]=="int"?SORT_NUMERIC:($aSort[1]=="string")?SORT_STRING:""),
                ($aSort[2]=="ASC"?SORT_ASC:SORT_DESC),
                $bSort[0],
                ($bSort[1]=="int"?SORT_NUMERIC:($bSort[1]=="string")?SORT_STRING:""),
                ($bSort[2]=="ASC"?SORT_ASC:SORT_DESC)
            );
        } else {
            //con order1
            if ($order[0] != "") {
                $result = $this->_index->find(
                    $q,
                    $aSort[0],
                    ($aSort[1]=="int"?SORT_NUMERIC:($aSort[1]=="string")?SORT_STRING:""),
                    ($aSort[2]=="ASC"?SORT_ASC:SORT_DESC)
                );
            }
            //con order2
            if ($order[1] != "") {
                $result = $this->_index->find(
                    $q,
                    $bSort[0],
                    ($bSort[1]=="int"?SORT_NUMERIC:($bSort[1]=="string")?SORT_STRING:""),
                    ($bSort[2]=="ASC"?SORT_ASC:SORT_DESC)
                );
            }
            //busqueda sin order
            if ($order[0] == "" && $order[1] == "") {
                $result = $this->_index->find(
                    $q
                );
            }
        }
        
        $data = "";
        if ($this->_index->count()) {
            $c = 0;
            foreach ($result as $item) {
                $data[$c]["id"]                     = $item->idbeneficio;
                $data[$c]["establecimiento_id"]     = $item->idestablecimiento;
                $data[$c]["tipo_beneficio_id"]      = $item->idtipobeneficio;
                $data[$c]["titulo"]                 = $item->titulo;
                $data[$c]["descripcioncorta"]       = $item->descripcioncorta;
                $data[$c]["valor"]                  = $item->valor;
                $data[$c]["cuando"]                 = $item->cuando;
                $data[$c]["direccion"]              = $item->direccion;
                $data[$c]["email_info"]             = $item->emailinfo;
                $data[$c]["telefono_info"]          = $item->telefonoinfo;
                $data[$c]["path_logo"]              = $item->pathlogo;
                $data[$c]["maximo_por_subscriptor"] = $item->maximoporsubscriptor;
                $data[$c]["sin_stock"]              = $item->sinstock;
                $data[$c]["es_destacado"]           = $item->esdestacado;
                $data[$c]["es_destacado_principal"] = $item->esdestacadoprincipal;
                $data[$c]["activo"]                 = $item->activo;
                $data[$c]["generar_cupon"]          = $item->generarcupon;
                $data[$c]["publicado"]              = $item->publicado;
                $data[$c]["vecesvisto"]             = $item->vecesvisto;
                $data[$c]["fecha_registro"]         = $item->fecharegistro;
                $data[$c]["chapita"]                = $item->chapita;
                $data[$c]["chapita_color"]          = $item->chapitacolor;
                $data[$c]["slug"]                   = $item->slug;
                $data[$c]["fecha_inicio_vigencia"]  = $item->fechainiv;
                $data[$c]["fecha_fin_vigencia"]     = $item->fechafinv;
                $data[$c]["stock_actual"]           = $item->stockactual;
                $data[$c]["stock"]                  = $item->stock;
                $data[$c]["nombre"]                 = $item->nombre;
                $data[$c]["cat_nombre"]             = $item->catnombre;
                $data[$c]["est_nombre"]             = $item->estnombre;
                $data[$c]["categorias"]             = $item->categorias;
                $data[$c]["abreviado"]              = $item->abreviado;
                $c++;
            }
        }
        return $data;
    }

    function getPath()
    {
        return $this->_ruta;
    }

    function getIndex()
    {
        return $this->_index;
    }

    function fillZeroField($field, $nzeros='')
    {
        $n = strlen($field);
        $newfield=$field;
        for($i=0;$i<(($nzeros==''?$this->_nzeros:$nzeros)-$n);$i++) $newfield="0".$newfield;
        return $newfield;
    }
    function SumaCadena($cadena, $separador)
    {
        $suma=0;
        $arreglo = explode($separador, $cadena);
        for ($i=0;$i<count($arreglo);$i++) {
            $suma+=$arreglo[$i];
        }
        return $suma;
    }
    function sinAcento($campo)
    {
        $campo = str_replace("á", "a", $campo);
        $campo = str_replace("é", "e", $campo);
        $campo = str_replace("í", "i", $campo);
        $campo = str_replace("ó", "o", $campo);
        $campo = str_replace("ú", "u", $campo);
        return $campo;
    }
    function ifnull($campo, $null="")
    {
        return ($campo==null)?$null: str_replace("'", "", str_replace("`", "", $campo));
    }

    //Funciones para el Buscador de Beneficios ------------------------------
    function insertarIndexBeneficio($objBeneficio, $encolar = true)
    {
        if ($encolar && $this->_readOnly) {
            $this->_encolador->encolarElemento(
                'beneficios', array($objBeneficio, false, false), __FUNCTION__
            );
            return true;
        } else {
            $this->addDocumentBeneficio($objBeneficio);
            $this->_index->commit();
           
            $start = $this->getMicroTime();
            $delay = $this->_config->lucene->timeout;
            $fila = null;
            while ($fila == null || count($fila) <= 0) {
                $fila = $this->_index->find("idbeneficio:".$objBeneficio["idbeneficio"]);
                $end = $this->getMicroTime();

                if ($end > ($start + $delay)) {
                    $this->_logCron->log(
                        "Error al Insertar Beneficio, idbeneficio: ".
                        $objBeneficio["idbeneficio"], Zend_Log::CRIT
                    );
                    break;
                }
            }
            return true;
        }
    }
    function updateIndexBeneficio($idBeneficio, $encolar = true)
    {
        if ($encolar && $this->_readOnly) {
            $this->_encolador->encolarElemento(
                'beneficios', array($idBeneficio, false, false), __FUNCTION__
            );
            return true;
        } else {
            $fila = $this->_index->find("idbeneficio:".$idBeneficio);
            foreach ($fila as $hit) {
                $this->_index->delete($hit->id);
            }
            if ($fila != null && count($fila) > 0) {
                $this->agregarNuevoDocumentoBeneficio($idBeneficio);
                $this->_index->commit();
                
                $start = $this->getMicroTime();
                $delay = $this->_config->lucene->timeout;
                $fila = null;
                while ($fila == null || count($fila) <= 0) {
                    $fila = $this->_index->find("idbeneficio:".$idBeneficio);
                    $end = $this->getMicroTime();

                    if ($end > ($start + $delay)) {
                        $this->_logCron->log(
                            "Error al Actualizar Beneficio, idbeneficio: ".
                            $idBeneficio, Zend_Log::CRIT
                        );
                        break;
                    }
                }
                return true;
            }
            return false;
        }
    }
    
    function deleteIndexBeneficio($idBeneficio, $encolar=true)
    {
        if ($encolar && $this->_readOnly) {
            $this->_encolador->encolarElemento(
                'beneficios', array($idBeneficio, array(), false, false), __FUNCTION__
            );
            return true;
        } else {
            $fila = $this->_index->find("idbeneficio:".$idBeneficio);
            foreach ($fila as $hit) {
                $this->_index->delete($hit->id);
            }

            if ($fila != null && count($fila) > 0) {
                $this->_index->commit();
                $start = $this->getMicroTime();
                $delay = $this->_config->lucene->timeout;
                while ($fila != null) {
                    $fila = $this->_index->find("idbeneficio:".$idBeneficio);
                    $end = $this->getMicroTime();

                    if ($end > ($start + $delay)) {
                        $this->_logCron->log(
                            "Error al eliminar Beneficio, idbeneficio: ".
                            $idBeneficio, Zend_Log::CRIT
                        );
                        break;
                    }
                }
                return true;
            }
            return false;
        }
    }


    public function agregarNuevoDocumentoBeneficio($idBeneficio, $encolar = true)
    {
        if ($encolar && $this->_readOnly) {
            $this->_encolador->encolarElemento(
                'beneficios', array($idBeneficio, false, false), __FUNCTION__
            );
            return true;
        } else {
            $aw = new Application_Model_Beneficio();
            if (file_exists($this->_ruta) && is_readable($this->_ruta)) {
                $indexavisos = Zend_Search_Lucene::open($this->_ruta);
                $awid = $idBeneficio;

                $urlId = $aw->find($awid);
                $urlId = $urlId[0]->slug;

                $urlHelper = new Zend_View_Helper_Url();
                $url = $urlHelper->url(array('id'=>$awid), 'lucene_ad', true);

                // obteniendo el HTML con Zend_Client
                $client = new Zend_Http_Client();
                $urlSite = SITE_URL.$url;
                $client->setUri(SITE_URL.$url);
                $html = $client->request(Zend_Http_Client::GET)->getBody();
                $client = null;

                $doc = Zend_Search_Lucene_Document_Html::loadHTML($html, false, "UTF-8");
                $doc->addField(Zend_Search_Lucene_Field::keyword('idbeneficio', $awid));
                $doc->addField(Zend_Search_Lucene_Field::keyword('urlId', $urlId));

                $indexavisos->addDocument($doc);
                $indexavisos->commit();

                $start = $this->getMicroTime();
                $delay = $this->_config->lucene->timeout;
                $fila = null;
                while ($fila == null || count($fila) <= 0) {
                    $fila = $indexavisos->find("idbeneficio:".$awid);
                    $end = $this->getMicroTime();

                    if ($end > ($start + $delay)) {
                        $this->_logCron->log(
                            "Error al agregar beneficio web, idbeneficio: ".
                            $awid, Zend_Log::CRIT
                        );
                        return false;
                        break;
                    }
                }
                return true;
            }
            return false;
        }
    }

    function optimizarIndex()
    {
        echo "Optimizando Indice de Beneficios....".PHP_EOL;
        $this->_index->optimize();
        echo "Termino optimize".PHP_EOL;
    }

    //Funciones para ZendLucene ------------------------------------------------------
    function hasIndexBeneficio($idBeneficio)
    {
        $fila = $this->_index->find("idbeneficio:".$idBeneficio);
        if (count($fila) > 0) {
            return true;
        }
        return false;
    }

    function commitIndex()
    {
        echo "Commit a indice de Beneficios ...".PHP_EOL;
        try {
            $this->_index->commit();
        } catch (Exception $e) {
            echo $e->getMessage().PHP_EOL;
            echo $e->getTraceAsString().PHP_EOL;
        }
        echo "Se hizo commit al indice de Beneficios........... [OK]".PHP_EOL;
    }

    function commitAndOptimize_Indexes($lastId)
    {
        $tiempo = time() + 0;
        echo "Commiteando Indice de Beneficios ...".PHP_EOL;
        try {
            echo microtime();
            $this->_index->commit();
            $i = 0;
            while ($this->hasIndexBeneficio($lastId) == false) {
                $i++;
            }
            echo $i.PHP_EOL;
            echo microtime();
            echo (time() - $tiempo).PHP_EOL;
            echo "Optimizando Indice de Beneficios....".PHP_EOL;
            $this->_index->optimize();
            echo "Termino optimize".PHP_EOL;
        } catch (Exception $e) {
            echo $e->getMessage().PHP_EOL;
            echo $e->getTraceAsString().PHP_EOL;
        }
        echo "Indice de ".$n." ha sido Commiteado y Optimizado ........... [OK]".PHP_EOL;
    }

    function getMicroTime()
    {
        $mt = explode(' ', microtime());
        return $mt[0] + $mt[1];
    }

    

}