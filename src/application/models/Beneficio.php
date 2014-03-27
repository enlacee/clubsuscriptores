<?php

class Application_Model_Beneficio extends App_Db_Table_Abstract
{

    protected $_name = 'beneficio';
    protected $_id;
    //atributos relacionados
    protected $_nrocupones;
    protected $_establecimientoId;
    protected $_activo;
    protected $_publicado;
    protected $_estado;
    protected $_nombre;
    protected $_fechaPublicacion;
    protected $_fechaVigencia;
    
    const TIPO_BENEFICIO = 1;
    const TIPO_PROMO = 2;
    const TIPO_CIERRA_PUERTAS = 3;
    const TIPO_TICKET = 4;
    const TIPO_SORTEO = 5;
    
    const FILTRO_POR_CUPON = 1;
    const FILTRO_POR_CATEGORIA = 2;
    
    public function getNombre()
    {
        return $this->_nombre;
    }

    public function setNombre($_nombre)
    {
        $this->_nombre = $_nombre;
    }
    
    public function getFecha_publicacion()
    {
        return $this->_fechaPublicacion;
    }

    public function setFecha_publicacion($array=null)
    {
        $this->_fechaPublicacion = "CURRENT_DATE()";
    }
    
    public function getFecha_vigencia()
    {
        return $this->_fechaVigencia;
    }

    public function setFecha_vigencia($array=null)
    {
        $this->_fechaVigencia = "CURRENT_DATE()";
    }
    
    public function getActivo()
    {
        return $this->_activo;
    }

    public function setActivo($_activo)
    {
        $this->_activo = $_activo;
    }
    
    public function getEstado()
    {
        return $this->_estado;
    }

    public function setEstado($_estado,$andOr=true) //and true or false
    {
        $value='';
        switch ($_estado) {
            case 'borrador':
                $value = 2;
                break;
            default:
                break;
        }
        $this->_estado = array('valor'=>$value, 'cond'=>$andOr);
    }      
    
    public function getPublicado()
    {
        return $this->_publicado;
    }

    public function setPublicado($_publicado)
    {
        $this->_publicado = $_publicado;
    }
    
    public function getEstablecimiento_id()
    {
        return $this->_establecimientoId;
    }

    public function setEstablecimiento_id($_establecimientoId)
    {
        $this->_establecimientoId = $_establecimientoId;
    }
    
    public function getNrocupones()
    {
        return $this->_nrocupones;
    }

    public function setNrocupones($_nrocupones)
    {
        $this->_nrocupones = $_nrocupones;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setId($_id)
    {
        $this->_id = $_id;
    }

    public function getCategorias()
    {
        $sql = $this->select()->from($this->_name, array('id', 'nombre'));
        $rs = $this->getAdapter()->fetchPairs($sql);
        return $rs;
    }

    public function getPromosAcogidas()
    {
        $db = $this->getAdapter();
        //mostramos las 8 primeras filas
        $sql = $db->select()
            ->limit(8, 0)
            ->from(array('benef'=>$this->_name))
            ->where('benef.activo = ?', 1)
            ->where('benef.publicado = ?', 1)
            ->where('benef.elog = ?', 0)
            //->where('benef.sin_stock = ?', 0)
            //->where('tipo_beneficio_id = ?', self::TIPO_PROMO) 
            //Anteriormente se mostraron solo las promos mas vistas
            //->where('benef.veces_visto > ?', 0)
            ->where(
                'CURRENT_DATE() BETWEEN benefv.fecha_inicio_publicacion AND benefv.fecha_fin_publicacion'
            );
        
        $sql->join(
            array('benefv'=>'beneficio_version'), 'benefv.beneficio_id = benef.id',
            array('fecha_inicio_vigencia')
        )
        ->where('benefv.activo = ?', 1)
        ->order('benef.veces_visto DESC')
        ->order('benefv.fecha_inicio_vigencia DESC');

        //echo $sql; exit;
        $rs = $db->fetchAll($sql);
        return $rs;
    }

    public function getMainDestacado($cache=true)
    {
        $cacheEt = $this->_config->cache->{$this->info('name')}->{__FUNCTION__};
        $cacheId = $this->info('name').'_'.__FUNCTION__;
        if ($this->_cache->test($cacheId) && $cache) {
            return $this->_cache->load($cacheId);
        }
        $shownropromos = $this->_config->main_destacados->nropromos;
        $result = $this->getAdapter()->fetchAll($this->getDestacados(1, $shownropromos));

        $this->_cache->save($result, $cacheId, array(), $cacheEt);
        return $result;
    }

    public function getDestacadosPortada($cache=true)
    {
        $cacheEt = $this->_config->cache->{$this->info('name')}->{__FUNCTION__};
        $cacheId = $this->info('name').'_'.__FUNCTION__;
        if ($this->_cache->test($cacheId) && $cache) {
            return $this->_cache->load($cacheId);
        }

        $shownropromos = $this->_config->destacados->nropromos;
        $sql = $this->getDestacados('', $shownropromos);
        $result = $this->getAdapter()->fetchAll($sql);

        $this->_cache->save($result, $cacheId, array(), $cacheEt);
        return $result;
    }

    public function getDestacados($val = 0,$limit = '')
    {   
        //lista de beneficios activos y con stock, son destacados
        $db = $this->getAdapter();
        $subsql = $db->select()
            ->limit(1, 0)
            ->from(array('catbenef'=>'categoria_beneficio'), array())
            ->join(
                array('cat' => 'categoria'), 'cat.id = catbenef.categoria_id ', 
                array('cat_nombre' => 'nombre')
            )->where('catbenef.beneficio_id = benef.id');
            
        $sql = $db->select()
            ->from(
                array('benef' => $this->_name),
                array('*', 'cat_nombre' => new Zend_Db_Expr("($subsql)"))
            );
        if ($val == 1) {
            $sql = $sql->where('benef.es_destacado_principal = ?', $val);
        } else {
            $sql = $sql->where('benef.es_destacado = ?', 1);
        }
        $sql = $sql->where('benef.activo = ?', 1)
            ->where('benef.publicado = ?', 1)
            ->where('benef.elog = ?', 0)
            //->where('benef.sin_stock = ?',0);
            ->where(
                'CURRENT_DATE() BETWEEN benefv.fecha_inicio_publicacion AND benefv.fecha_fin_publicacion'
            );

        $sql->join(
            array('benefv' => 'beneficio_version'), 'benef.id = benefv.beneficio_id', 
            array('fecha_inicio_publicacion', 'fecha_fin_publicacion', 'stock_actual', 'stock')
        )
        ->where('benefv.activo = ?', 1);

        $sql->join(
            array('tbenef' => 'tipo_beneficio'), 'tbenef.id = benef.tipo_beneficio_id', 
            array('abreviado')
        );

        /*$sql->join(
            array('catbenef' => 'categoria_beneficio'), 'catbenef.beneficio_id = benef.id ', 
            array()
        );*/

        /*$sql->join(
            array('cat' => 'categoria'), 'cat.id = catbenef.categoria_id ', 
            array('cat_nombre' => 'nombre')
        );*/

        $sql->order('benefv.fecha_inicio_vigencia DESC');
        //echo $sql; exit;
        if ($val == 1):
        //$rs = $db->fetchRow($sql);
        else:
        //$rs = $db->fetchAll($sql);
        endif;
        if(!empty($limit)):
            $sql->limit($limit, 0);
        endif;
        //echo $sql; exit;
        //return $rs;
        return $sql;
    }

    //Lista todos los beneficios
    public function getBeneficio(
        $col="", $ord="", $loultimo="", $beneficios="", $categoria="", $query="", $awIds="", $idSorteos=array()
    )
    {
        switch ($col) {
            case "nombre":
                $col = "titulo";
                break;
            case "oferta":
                $col = "descripcioncorta";
                break;
            case "tipo":
                $col = "abreviado";
                break;
            case "categoria":
                $col = "c.nombre";
                break;
            case "fecha":
                $col = "bv.fecha_fin_vigencia";
                break;
            default:
                $col = "fecha_inicio_vigencia";
                break;
        }
        //$col = $col==""?"fecha_inicio_vigencia":$col;
        $ord = ($ord=="" || $ord=="ASC")?"ASC":"DESC";
        //$ord = ($ord=="" || $ord=="a-z")?"ASC":"DESC";

        $adapter=$this->getAdapter();
        $sql = $adapter->select()
                ->from(
                    array("b" => $this->_name),
                    array(
                        "id"                     => "b.id",
                        "establecimiento_id"     => "b.establecimiento_id",
                        "tipo_beneficio_id"      => "b.tipo_beneficio_id",
                        "titulo"                 => "b.titulo",
                        "descripcioncorta"       => "b.descripcion_corta",
                        "valor"                  => "b.valor",
                        "cuando"                 => "b.cuando",
                        "direccion"              => "b.direccion",
                        "email_info"             => "b.email_info",
                        "telefono_info"          => "b.telefono_info",
                        "path_logo"              => "b.path_logo",
                        "maximo_por_subscriptor" => "b.maximo_por_subscriptor",
                        "sin_stock"              => "b.sin_stock",
                        "es_destacado"           => "b.es_destacado",
                        "es_destacado_principal" => "b.es_destacado_principal",
                        "activo"                 => "b.activo",
                        "veces_visto"            => "b.veces_visto",
                        "fecha_registro"         => "b.fecha_registro",
                        "chapita"                => "b.chapita",
                        "chapita_color"          => "b.chapita_color",
                        "slug"                   => "b.slug",
                        "fecha_inicio_publicacion"  => "bv.fecha_inicio_publicacion",
                        "fecha_fin_publicacion"     => "bv.fecha_fin_publicacion",
                        "fecha_fin_vigencia"     => "bv.fecha_fin_vigencia",
                        "stock_actual"           => "bv.stock_actual",
                        "stock"                  => "bv.stock",
                        "nombre"                 => "tb.nombre",
                        "cat_nombre"             => "c.nombre",
                        "idcategoria"            => "c.id",
                        "est_nombre"             => "e.nombre",
                        "abreviado"              => "tb.abreviado",
                        "ncuponesconsumidos"     => "b.ncuponesconsumidos",
                        "publicado"              => "b.publicado",
                        "generar_cupon"          => "b.generar_cupon"
                    )
                )
                ->join(
                    array("bv" => "beneficio_version"),
                    "b.id = bv.beneficio_id",
                    array()
                )
                ->join(
                    array("tb" => "tipo_beneficio"),
                    "tb.id = b.tipo_beneficio_id",
                    array()
                )
                ->join(
                    array("cb" => "categoria_beneficio"),
                    "cb.beneficio_id = b.id",
                    array()
                )
                ->join(
                    array("c" => "categoria"),
                    "c.id = cb.categoria_id",
                    array()
                )
                ->join(
                    array("e" => "establecimiento"),
                    "e.id = b.establecimiento_id",
                    array()
                )
                ->where("b.activo = 1")
                ->where("b.publicado = 1")
                ->where("bv.activo = 1")
                ->where(
                    "CURRENT_DATE()
                     BETWEEN bv.fecha_inicio_publicacion AND bv.fecha_fin_publicacion"
                )
                ->where('b.elog = ?', 0)
                ->group("b.id")
                ->order($col." ".$ord);

        //FILTRO PARA EL QUERY
        if (Suscriptor_BeneficioController::$usarLucene) { // usando Lucene
            if ($awIds !== "") {
                if (is_array($awIds) && count($awIds) ) {
                    $sql = $sql->where(
                        $this->getAdapter()->quoteInto("b.id IN (?)", $awIds)
                    );
                } else {
                    $sql = $sql->where("b.id IN (0)");
                }
            }
        } else { // sin usar Lucene
            if ($query!="") {
                $queryLow= strtolower($query);
                $sql=$sql->where(
                    $this->getAdapter()->quoteInto(
                        "LOWER(CONCAT(b.titulo,' ',b.descripcion)) like ?", "%".$queryLow."%"
                    )
                );
            }
        }

        //lo mas usado
        switch ($loultimo) {
            case 0:
                $sql = $sql->order("b.fecha_registro DESC");
                break;
            case 1:
                $sql = $sql->order("b.veces_visto DESC");
                break;
            case 2:
                $sql = $sql->order("b.ncuponesconsumidos DESC");
                break;
        }
        
        //categoria
        if ($categoria != "") {
            $aCategoria = explode("-", $categoria);
            $c = "";
            for ($i = 0; $i < count($aCategoria); $i++) {
                $c.="cb.categoria_id=" . $aCategoria[$i]." OR ";
            }
            $c = substr($c, 0, strlen($c) - 3);
            $sql = $sql->where($c);
        }
        
        //beneficios
        if ($beneficios!="") {
            $sql = $sql->where("b.tipo_beneficio_id = ?", $beneficios);
        } else {
            if ($categoria != "" && !empty($idSorteos)) {
                $aCategoria = explode("-", $categoria);
                $valbolSD = array_search($idSorteos['idDisponible'], $aCategoria)!==false;
                $valbolSR = array_search($idSorteos['idResultado'], $aCategoria)!==false;
                if ((($valbolSD || $valbolSR) && count($aCategoria)==1) || 
                    ($valbolSD && $valbolSR && count($aCategoria)==2)) {
                    
                } else {
//                    $sql = $sql->where("lower(tb.nombre) <> 'sorteos'", $beneficios);
                }
            } else {
//                $sql = $sql->where("lower(tb.nombre) <> 'sorteos'", $beneficios);
            }
        }

        //echo $sql->assemble();exit;
        return $sql;

        /*
        //BUSCADOR SOLAMENTE CON LUCENE
        $col = $col == '' ? '' : $col." string ".$ord;
        $zl = new ZendLucene();
        $order = $col;
        $orderTwo = "";
        $q = "activo:1 AND publicado:1";

        //lomasusado
        if ($loultimo==0) {
            $orderTwo = "fecharegistro string DESC";
        }
        if ($loultimo==1) {
            $orderTwo = "vecesvisto string DESC";
        }
        if ($loultimo==2) {
            $orderTwo = "ncuponesconsumidos string DESC";
        }
        
        //beneficio
        if ($beneficios!="") {
            $q.=' AND idtipobeneficio:'.$beneficios;
        }
        //categoria
        if ($categoria != "") {
            $aCategoria = explode("-", $categoria);
            $c = "";
            for ($i = 0; $i < count($aCategoria); $i++) {
                $c.=" idcategoria:" . $aCategoria[$i] . " OR";
            }
            $c = substr($c, 0, strlen($c) - 3);
            $q.=" AND ($c)";
        }
        //query
        if ($query!="") {
            $q .= ' AND '.$query;
        }
        
        $resultado =  $zl->queryBeneficios(
            $q,
            array($order, $orderTwo)
        );
        return ($resultado=="")?array():$resultado;*/
    }
    
    //obtiene informacion del beneficio por su "id" ($this->getId())
    //, solo retornara los datos de ese registro
    public function getOnlyBeneficio()
    {
        $db = $this->getAdapter();
        $sql = $db->select()
            ->from(array('benef' => $this->_name), array("*",
                'cat_nombre' => new Zend_Db_Expr(
                    "(SELECT GROUP_CONCAT(c.nombre ORDER BY c.id ASC SEPARATOR ' - ')
                    FROM categoria c 
                    INNER JOIN categoria_beneficio cb ON cb.categoria_id = c.id
                    WHERE cb.beneficio_id=benef.id)"
                ),
                'cat_slug' => new Zend_Db_Expr(
                    "(SELECT GROUP_CONCAT(c.slug ORDER BY c.id ASC SEPARATOR '--')
                    FROM categoria c 
                    INNER JOIN categoria_beneficio cb ON cb.categoria_id = c.id
                    WHERE cb.beneficio_id=benef.id)"
                ),
            ))
            ->where('benef.activo = ?', 1);
            //->where('benef.sin_stock = ?',0); 
            //se mostrara los beneficios que no tengan stock, indicado con un estado
            /*->where(
                'CURRENT_DATE() BETWEEN benefv.fecha_inicio_vigencia AND benefv.fecha_fin_vigencia'
            );*/

        $sql->join(
            array('benefv' => 'beneficio_version'), 'benef.id = benefv.beneficio_id', 
            array('fecha_inicio_vigencia', 'fecha_fin_vigencia', 'stock_actual', 'stock')
        )->where('benefv.activo = ?', 1);

        $sql->join(
            array('tbenef' => 'tipo_beneficio'), 'tbenef.id = benef.tipo_beneficio_id', 
            array('abreviado',"tbenef_id"=>"id","tbenef_slug"=>"slug")
        );

        $sql->join(
            array('catbenef' => 'categoria_beneficio'), 'catbenef.beneficio_id = benef.id ', 
            array()
        );

//        $sql->join(
//            array('cat' => 'categoria'), 'cat.id = catbenef.categoria_id ', 
//            array('cat_nombre' => 'nombre','cat_id' => 'id','cat_slug' => 'slug')
//        );

        $sql->join(
            array('est' => 'establecimiento'), 'est.id = benef.establecimiento_id', 
            array('est_nombre' => 'nombre')
        );
        
        if(!empty($this->_publicado)):
            $sql->where('benef.publicado = ?', $this->getPublicado());
        endif;
        
        if (!empty($this->_fechaPublicacion)) {
            $sql->where($this->getFecha_publicacion().
                    ' BETWEEN benefv.fecha_inicio_publicacion AND benefv.fecha_fin_publicacion');
        }
        if (!empty($this->_id)) {
            $sql->where('benef.id = ?', $this->getId());
            //$rs = $db->fetchRow($sql);
        } else {
            //$rs = $db->fetchAll($sql);
        }
        $sql->order('benefv.fecha_inicio_publicacion DESC');
        //echo $sql->assemble(); exit;
        $rs = $db->fetchRow($sql);
        return $rs;
    }

    public function getBusquedaBeneficios(
        $col="", $ord="", $loultimo="", $beneficios="", $categoria="", $query="", $np="", $awIds="", $idSorteos=array()
    ) 
    {
        if ($np=="")
            $paginado = $this->_config->beneficios->promos->nropaginas;
        else
            $paginado = $np;
        $p = Zend_Paginator::factory(
            $this->getBeneficio($col, $ord, $loultimo, $beneficios, $categoria, $query, $awIds, $idSorteos)
        );
        return $p->setItemCountPerPage($paginado);
    }

    public function getAumentoVecesVisto()
    {
        $sql = $this->select($this->_name, array('veces_visto', 'id'))
            ->where('id = ?', $this->getId());
        $rs = $this->getAdapter()->fetchRow($sql);
        $newveces = (!empty($rs['veces_visto']) ? $rs['veces_visto'] : 0) + 1;
        $valuesbeneficio['veces_visto'] = $newveces;
        $where = $this->getAdapter()->quoteInto('id = ?', $rs['id']);        
        $this->update($valuesbeneficio, $where);
        
        if (!empty($rs['activo']) && !empty($rs['publicado'])) {
            /*$zl = new ZendLucene();
            $zl->updateIndexBeneficio(
                $rs['id'], array('vecesvisto'=>$zl->fillZeroField($newveces))
            );*/
        }
    }

    public function getDataRendencionBeneficio()
    {
        $db = $this->getAdapter();
        $sql = $db->select()
            ->from(
                array('b' => $this->_name),
                array('id','sin_stock', 'maximo_por_subscriptor', 'sin_limite_por_suscriptor','tipo_redencion')
            );
        $sql->join(
            array('bv' => 'beneficio_version'), 'b.id = bv.beneficio_id', 
            array('stock_actual', 'stock')
        )
        ->where('bv.activo = ?', 1)
        ->where('b.id = ?', $this->getId());
        $sql->where('b.publicado = 1 or b.activo = 1');
        $sql->where('CURRENT_DATE() BETWEEN bv.fecha_inicio_vigencia AND bv.fecha_fin_vigencia');
//        echo $sql;exit;
        $rs = $db->fetchRow($sql);
        return $rs;        
    }

    public function getBeneficiosRelacionados($_filter, $ord="", $col="")
    {
        $db = $this->getAdapter();
        $sql = $db->select();
        
        switch ($col) {
            case "tipo":
                $col="abreviado";
                break;
            case "fecha":
                $col="fechafinv";
                break;
        }
        
        switch ($ord) {
            case "a-z":
                $ord="ASC";
                break;
            case "z-a":
                $ord="DESC";
                break;
        }

        
        if ($_filter == self::FILTRO_POR_CUPON):

            $subsql = $db->select()->distinct()
                                   ->from('cupon', array('suscriptor_id'))
                                   ->where('beneficio_id = ?', $this->getId());
            $sql->distinct()
                ->from(array('cupon' => 'cupon'), array('beneficio_id'))
                ->where('cupon.suscriptor_id in(?)', new Zend_Db_Expr($subsql))
                ->where('cupon.beneficio_id <> ?', $this->getId());

            $sql->join(array('benef' => $this->_name), 'benef.id = cupon.beneficio_id');
            $sql->join(
                array('catbenef' => 'categoria_beneficio'), 'catbenef.beneficio_id = benef.id ', 
                array()
            );

        elseif ($_filter == self::FILTRO_POR_CATEGORIA):

            $subsql = $db->select()
                         ->distinct()
                         ->from('categoria_beneficio', array('categoria_id'))
                         ->where('beneficio_id = ?', $this->getId());

            $sql->distinct()
                ->from(array('catbenef' => 'categoria_beneficio'), array())
                ->where('catbenef.categoria_id in(?)', new Zend_Db_Expr($subsql))
                ->where('catbenef.beneficio_id <> ?', $this->getId());

            $sql->join(array('benef' => $this->_name), 'benef.id = catbenef.beneficio_id');
        endif;

        $sql->join(
            array('benefv' => 'beneficio_version'), 'benef.id = benefv.beneficio_id', 
            array(
                'fecha_inicio_vigencia', 'fecha_fin_vigencia', 'stock_actual', 'stock',
                'fechafinv'=>'benefv.fecha_fin_vigencia'
            )
        )->where('benefv.activo = ?', 1);

        $sql->join(
            array('tbenef' => 'tipo_beneficio'), 'tbenef.id = benef.tipo_beneficio_id', 
            array('abreviado')
        );

        /*$sql->join(
            array('cat' => 'categoria'), 'cat.id = catbenef.categoria_id ', 
            array('cat_nombre' => 'nombre', 'catnombre'=>'nombre')
        );*/

        $sql->join(
            array('est' => 'establecimiento'), 'est.id = benef.establecimiento_id', 
            array('est_nombre' => 'nombre')
        );

        $sql->where(
            'CURRENT_DATE() BETWEEN benefv.fecha_inicio_publicacion AND benefv.fecha_fin_publicacion'
        )
        ->where('benef.activo = ?', 1)
        ->where('benef.publicado = ?', 1)
        ->where('benef.elog = ?', 0)
        ->where("COALESCE(benef.path_logo,'')<>''"); 
            //para que se muestren los beneficios que tienen imagen registrada
        //->where('benef.sin_stock = ?',0);

        $ord = $ord==""?"DESC":$ord;
        $col = $col==""?"fecha_inicio_vigencia":$col;

        $sql->order($col.' '.$ord);
        //$rs = $db->fetchAll($sql);
        //return $rs;
        //echo $sql; exit;
        return $sql;
    }

    public function getPaginator()
    {
        $nropaginasrelacionados = $this->_config->beneficios_relacionados->promos->nropaginas;
        $paginator = Zend_Paginator::factory($this->getBeneficiosRelacionados());
        return $paginator->setItemCountPerPage($nropaginasrelacionados);
    }

    public function getValidStock($values = array())
    {
        $values['nropedido_enviado'] = $this->getNrocupones();
        $values['nrodisponible'] = 0;

        $db = $this->getAdapter();
        $sql = $this->select()->from(array('benef'=>$this->_name), array('sin_stock'))
                    ->where('id = ?', $this->getId());
        $rs = $db->fetchRow($sql);
        if ( !empty($rs['sin_stock']) ) {
            //no se valida, ya que el beneficio no controla stock
        } else {
            $sql = $db->select()->from(
                array('benefv' => 'beneficio_version'), array('stock_actual')
            )
            ->where('activo = ?', 1)
            ->where('beneficio_id = ?', $this->getId());
            $rs = $db->fetchRow($sql);
            $values['nrodisponible'] = $rs['stock_actual'];
            if (($rs['stock_actual'] < $values['nropedido_enviado']) 
                || empty($values['nropedido_enviado'])):
                $values['popup'] = true;
                $values['nropedido_enviado'] = $rs['stock_actual'];
            endif;
        }
        return $values;
    }

    public function getCountBeneficios()
    {
        $adapter=$this->getAdapter();
        $sql = "SELECT COUNT(id) AS n FROM beneficio";
        $stm = $adapter->query($sql);
        return $stm->fetchAll();
    }

    public function getRellenarIndexBeneficios($limit, $n)
    {
        $adapter=$this->getAdapter();
        $sql = "SELECT  b.id AS idbeneficio,
                    b.`establecimiento_id`,
                    b.`tipo_beneficio_id`,
                    b.`titulo`,
                    b.`descripcion_corta` as descripcion,
                    b.`valor`,
                    b.`cuando`,
                    b.`direccion`,
                    b.`email_info`,
                    b.`telefono_info`,
                    b.`path_logo`,
                    b.`maximo_por_subscriptor`,
                    b.`sin_stock`,
                    b.`es_destacado`,
                    b.`es_destacado_principal`,
                    b.`activo`,
                    b.`veces_visto`,
                    b.`fecha_registro`,
                    b.`chapita`,
                    b.`chapita_color`,
                    b.`slug`,
                    bv.fecha_inicio_vigencia,
                    bv.fecha_fin_vigencia,
                    bv.stock_actual,
                    bv.stock,
                    tb.nombre,
                    c.nombre AS `cat_nombre`,
                    c.id AS idcategoria,
                    e.nombre AS `est_nombre`,
                    tb.abreviado,
                    (SELECT GROUP_CONCAT(cba.categoria_id SEPARATOR '-') 
                    FROM categoria_beneficio cba
                    WHERE cba.beneficio_id=b.id) AS categorias,
                    b.`ncuponesconsumidos`,
                    b.publicado,
                    b.generar_cupon
            FROM beneficio AS b
            INNER JOIN beneficio_version AS bv ON b.id = bv.beneficio_id
            INNER JOIN tipo_beneficio AS tb ON tb.id = b.tipo_beneficio_id
            INNER JOIN categoria_beneficio AS cb ON cb.beneficio_id = b.id
            INNER JOIN categoria AS c ON c.id = cb.categoria_id
            INNER JOIN establecimiento AS e ON e.id = b.establecimiento_id
            WHERE
            (b.activo = 1) 	AND (CURRENT_DATE()
            BETWEEN bv.fecha_inicio_vigencia AND bv.fecha_fin_vigencia)
            GROUP BY b.id 
            ORDER BY bv.fecha_inicio_vigencia DESC
            LIMIT $limit, $n
            ";
        $stm = $adapter->query($sql);
        return $stm->fetchAll();
    }
    
    
    public function getBeneficiosPorEstablecimiento($order = '') 
    {
        $db = $this->getAdapter();
        $subsql = $db->select()->from(array('cup'=>'cupon'), array('count(*)'))
                               ->where('cup.beneficio_id = benef.id');
        // Campo artificio momentaneo ===========
        $subsqlconsume = $db->select()
                            ->from(array('cup'=>'cupon'), array('count(*)'))
                            ->where(
                                'cup.beneficio_id = benef.id and cup.estado in('
                                ."'".Application_Model_Cupon::ESTADO_CONSUMIDO."'".','
                                ."'".Application_Model_Cupon::ESTADO_REDIMIDO."'"
                                .')'
                            );
        // ======================================
        $sql = $db->select()->from(
            array('benef'=>$this->_name),
            array(
                'titulo','codigo', 'tipo_beneficio_id', 'descripcion',
                'maximo_por_subscriptor', 'ncuponesconsumidos',
                'nrocuponsemitidos'=> new Zend_Db_Expr("($subsql)")
                ,'nrocuponsconsumidos'=> new Zend_Db_Expr("($subsqlconsume)")
                ,'chapita'
                ,'sin_limite_por_suscriptor'
                ,'sin_stock'
            )
        )
        ->where('benef.elog = ?', 0);
        $sql->join(
            array('benefv'=>'beneficio_version'), 
            'benef.id = benefv.beneficio_id', 
            array(
                'fecha_inicio_vigencia',
                'fecha_fin_vigencia', 
                'stock_actual',
                'stock',
            )
        )
        ->where('benefv.activo = ?', 1);
        $sql->join(
            array('tbenef'=>'tipo_beneficio'),
            'tbenef.id = benef.tipo_beneficio_id',
            array('tipnombre'=>'abreviado')
        );
        
        if(!empty($this->_establecimientoId)):
            $sql->where('establecimiento_id = ?', $this->getEstablecimiento_id());
        endif;
        
        if($this->_activo!=''):
            $sql->where('benef.activo = ?', $this->getActivo());
        endif;
        
        if(!empty($this->_nombre)):
            $this->_nombre = $this->getRemoveApostrophe($this->_nombre);
            $sql->where(
                "CONCAT(benef.titulo,' ',benef.descripcion) LIKE '%".$this->getNombre()."%'"
            );
        endif;
        
        if(!empty($order)):
            $sql->order("benefv.fecha_inicio_vigencia $order");
        else:
            $sql->order('benefv.fecha_inicio_vigencia desc');
        endif;
        //$rs = $db->fetchAll($sql);
        //return $rs;
        //echo $sql; exit;
        return $sql;
    }
    
    
    public function getPaginatorListarPromociones($idSusc, $idEstab, $ord, $col, $estado)
    {
        $nropaginasrelacionados = $this->_config->beneficios->promos->nropaginas;
        $paginator = Zend_Paginator::factory(
            $this->getListarPromociones($idSusc, $idEstab, $col, $ord, $estado)
        );
        return $paginator->setItemCountPerPage($nropaginasrelacionados);
    }
    
    public function getListarPromociones($idSusc, $idEstab, $col, $ord, $estado)
    {
        $col = $col == '' ? 'bv.fecha_inicio_vigencia' : $col;
        $ord = $ord == '' ? 'DESC' : $ord;
        
        $sql = $this->getAdapter()->select()
            ->from(
                array('b'=>$this->_name), 
                array('id_beneficio'=>'b.id',
                    'b.titulo', 
                    'b.descripcion', 
                    'b.maximo_por_subscriptor', 
                    'b.sin_limite_por_suscriptor', 
                    'b.activo', 
                    'b.sin_stock',
                    'b.chapita',
                    'generado' => new Zend_Db_Expr(
                        "(SELECT COUNT(*)
                        FROM cupon 
                        WHERE estado='generado' and beneficio_id=b.id and suscriptor_id=sb.suscriptor_id)"
                    ),
                )
            )->joinInner(
                array('bv'=>'beneficio_version'), 
                'bv.beneficio_id = b.id', 
                array('bv.fecha_inicio_vigencia', 'bv.fecha_fin_vigencia', 'bv.stock_actual')
            )->joinInner(
                array('tp'=>'tipo_beneficio'),
                'tp.id = b.tipo_beneficio_id',
                array('tp.nombre','tp.abreviado')
            )->joinLeft(
                array('sb'=>'suscriptor_beneficio'),
                $this->getAdapter()->quoteInto(
                    'sb.beneficio_id = b.id and sb.suscriptor_id = ?', $idSusc
                ),
                array('sb.cupon_consumido', 'sb.cupon_generado')
            )
            ->where('b.elog = ?', 0)
            ->where('bv.activo = ?', 1); //add
        if (!empty($idEstab)) {
            $sql->where('b.establecimiento_id = ?', $idEstab);
        }
        if (!empty($this->_activo)) {
            $sql->where('b.activo = ?', $this->_activo);
        }
        if (!empty($this->_estado)) {
            $estado=$this->_estado;
            switch ($estado['valor']) {
                case '2':
                    $sql->where('b.publicado = 1 or b.activo = 1');
                    break;
                default:
                    break;
            }
        }
        if (!empty($this->_fechaVigencia)) {
            $sql->where($this->getFecha_vigencia().
                    ' BETWEEN bv.fecha_inicio_vigencia AND bv.fecha_fin_vigencia');
        }
            $sql->group('b.id');
        $sql = $sql->order(sprintf('%s %s', $col, $ord));
        //echo $sql->assemble(); exit;
        return $sql;
    }
    
    public function listaPromocionXCodigo($idCupon)
    {
        $sql = $this->getAdapter()->select()
            ->from(
                array('b'=>$this->_name), 
                array('id_beneficio'=>'b.id','b.titulo','b.descripcion','b.maximo_por_subscriptor')
            )
            ->joinInner(
                array('bv'=>'beneficio_version'), 
                'bv.beneficio_id = b.id', 
                array('bv.fecha_inicio_vigencia', 'bv.fecha_fin_vigencia', 'bv.stock_actual')
            )
            ->joinInner(
                array('tp'=>'tipo_beneficio'),
                'tp.id = b.tipo_beneficio_id',
                array('tp.nombre')
            )
            ->joinInner(
                array('sb'=>'suscriptor_beneficio'),
                'sb.beneficio_id = b.id',
                array('sb.cupon_consumido', 'sb.cupon_generado')
            )
            ->joinInner(
                array('c'=>'cupon'),
                $this->getAdapter()->quoteInto('c.beneficio_id=b.id AND c.codigo= ?', $idCupon),
                array()
            );
            //echo $sql->assemble();
        return $this->getAdapter()->fetchAll($sql);
    }
    
    public function getBeneficios($criteria = array())
    {
        $db = $this->getAdapter();
        $sql = $db->select()
            ->from(
                array('b' => $this->_name), 
                array(
                    'id' => 'b.id',
                    'nombre' => 'b.titulo',
                    'publicado' => 'b.publicado',
                    'activo' => 'b.activo',
                    'fecha_registro' => 'b.fecha_registro',
                    'categorias' => new Zend_Db_Expr(
                        "(SELECT GROUP_CONCAT(c.nombre ORDER BY c.id ASC SEPARATOR '|')
                        FROM categoria c 
                        JOIN categoria_beneficio cb ON cb.categoria_id = c.id
                        WHERE cb.beneficio_id=b.id)"
                    ),
                    'redimido' => new Zend_Db_Expr(
                        "(SELECT COUNT(*)
                        FROM cupon 
                        WHERE estado='redimido'  and beneficio_id=b.id)"
                    ),
                    'generado' => new Zend_Db_Expr(
                        "(SELECT COUNT(*)
                        FROM cupon 
                        WHERE estado='generado'  and beneficio_id=b.id)"
                    ),
                    'sin_stock' => 'b.sin_stock',
                    'sin_limite_por_suscriptor' => 'b.sin_limite_por_suscriptor'
                )
            )
            ->join(
                array('t' => 'tipo_beneficio'), 
                'b.tipo_beneficio_id = t.id',
                array('tb_id' => 't.id', 'tb_nombre' => 't.nombre')
            )
            ->join(
                array('e' => 'establecimiento'),
                'b.establecimiento_id = e.id',
                array('e_id' => 'e.id', 'e_nombre' => 'e.nombre')
            )
            ->join(
                array('bv' => 'beneficio_version'), 
                'bv.beneficio_id = b.id AND bv.activo = 1', 
                array(
                    'bv_stock' => 'bv.stock', 
                    'bv_stock_actual' => 'bv.stock_actual',
//                    'bv_stock_consumido'=>'(bv.stock - bv.stock_actual)', 
                    'bv_fecha_inicio_vigencia' => 'bv.fecha_inicio_vigencia', 
                    'bv_fecha_fin_vigencia' => 'bv.fecha_fin_vigencia'
                )
            )->where('b.elog = ?', 0);
        if (isset($criteria['establecimiento']))
            $sql = $sql->where('b.establecimiento_id = ?', $criteria['establecimiento']);
        if (isset($criteria['activo']))
            $sql = $sql->where('b.activo = ?', $criteria['activo']);
        if (isset($criteria['publicado']))
            $sql = $sql->where('b.publicado = ?', $criteria['publicado']);
        if (isset($criteria['tipo_beneficio']))
            $sql = $sql->where('b.tipo_beneficio_id = ?', $criteria['tipo_beneficio']);
        if (isset($criteria['categoria'])) {
            $sql = $sql->join(
                array('cb' => 'categoria_beneficio'), 'cb.beneficio_id = b.id', array()
            )
            ->where('cb.categoria_id = ?', $criteria['categoria']);
        }
        if (isset($criteria['nombre']))
            $sql = $sql->where("b.titulo LIKE ?", '%'.$criteria['nombre'].'%');
        if (isset($criteria['orden_campo'])) {
            $sql = $sql->order($criteria['orden']. ' ' . $criteria['orden_direccion']);
        } else {
            $sql = $sql->order('b.fecha_registro DESC');
        }
//        echo $sql;exit;
        $rs = $db->fetchAll($sql);
        return $rs;
    }
    
    public static function getBeneficiosSorteosDisponibles($idSorteosDisponibles)
    {
        $obj = new Application_Model_Beneficio();
        $db = $obj->getAdapter();
        $sql = $db->select()
            ->limit(5, 0)
            ->from(array('benef' => $obj->_name), array('id', 'titulo', 'slug', 'path_logo'))
            ->where('benef.publicado = ?', 1)
            ->where('benef.activo = ?', 1)
            ->where('benef.elog = ?', 0)
            ->where('benef.es_destacado_banner = ?', 1);
        $sql->join(
            array('benefv' => 'beneficio_version'), 
            'benefv.beneficio_id = benef.id', 
            array('fecha_inicio_vigencia', 'beneficio_id')
        )
        ->where("CURRENT_DATE() BETWEEN benefv.fecha_inicio_publicacion AND benefv.fecha_fin_publicacion")
        ->where('benefv.activo = ?', 1)
        ->order('benefv.fecha_inicio_vigencia DESC');
        $sql->join(array('catBen' => 'categoria_beneficio'), 'catBen.beneficio_id=benef.id', false)
            ->where('catBen.categoria_id = ?', $idSorteosDisponibles);
        //echo $sql; exit;
        $rs = $db->fetchAll($sql);
        return $rs;
    }
    
    public static function getBeneficiosTipoConcursos($idConcurso)
    {
        $obj = new Application_Model_Beneficio();
        $db = $obj->getAdapter();
        $sql = $db->select()
            ->limit(5, 0)
            ->from(array('benef' => $obj->_name), array('id', 'titulo', 'slug', 'path_logo'))
            ->where('benef.publicado = ?', 1)
            ->where('benef.activo = ?', 1)
            ->where('benef.elog = ?', 0)
            ->where('benef.es_destacado_banner = ?', 1);
        $sql->join(
            array('benefv' => 'beneficio_version'), 
            'benefv.beneficio_id = benef.id', 
            array('fecha_inicio_vigencia', 'beneficio_id')
        )
        ->where("CURRENT_DATE() BETWEEN benefv.fecha_inicio_publicacion AND benefv.fecha_fin_publicacion")
        ->where('benefv.activo = ?', 1)
        ->order('benefv.fecha_inicio_vigencia DESC');
        $sql->join(array('tipBen' => 'tipo_beneficio'), 'tipBen.id=benef.tipo_beneficio_id', false)
            ->where('tipBen.id = ?', $idConcurso);
        //echo $sql; exit;
        $rs = $db->fetchAll($sql);
        return $rs;
    }
    
    public static function getBeneficiosActivosByTipoConcursos()
    {
        $obj = new Application_Model_Beneficio();
        $db = $obj->getAdapter();
        $sql = $db->select()
            ->from(array('benef' => $obj->_name), false)
            ->where('benef.publicado = ?', 1)
            ->where('benef.activo = ?', 1)
            ->where('benef.elog = ?', 0);
        $sql->join(
            array('benefv' => 'beneficio_version'), 
            'benefv.beneficio_id = benef.id', 
            false
        )
        ->where("CURRENT_DATE() BETWEEN benefv.fecha_inicio_publicacion AND benefv.fecha_fin_publicacion")
        ->where('benefv.activo = ?', 1);
        $sql->join(
                array('tipBen' => 'tipo_beneficio'), 
                'tipBen.id=benef.tipo_beneficio_id', 
                array('id', 'nombre')
            )
            ->group('tipBen.id');
        //echo $sql; exit;
        $rs = $db->fetchPairs($sql);
        return $rs;
    }
    
    public function getBeneficiosPaginator($criteria = array())
    {
        $nropag = $this->_config->gestor->promos->nropaginas;
        $paginator = Zend_Paginator::factory(
            $this->getBeneficios($criteria)
        );
        return $paginator->setItemCountPerPage($nropag);
    }

    public function insertZendLucene($id)
    {
        try {
            $db = $this->getAdapter();
            $sql = $db->select()
                ->from(
                    array('b' => $this->_name), 
                    array(
                        'idbeneficio' => 'b.id',
                        'establecimiento_id' => 'b.establecimiento_id',
                        'tipo_beneficio_id' => 'b.tipo_beneficio_id',
                        'titulo' => 'b.titulo',
                        'descripcion' => 'b.descripcion_corta',
                        'valor' => 'b.valor',
                        'cuando' => 'b.cuando',
                        'direccion' => 'b.direccion',
                        'email_info' => 'b.email_info',
                        'telefono_info' => 'b.telefono_info',
                        'chapita_color' => 'b.chapita_color',
                        'path_logo' => 'b.path_logo',
                        'maximo_por_subscriptor' => 'b.maximo_por_subscriptor',
                        'sin_stock' => 'b.sin_stock',
                        'es_destacado' => 'b.es_destacado',
                        'es_destacado_principal' => 'b.es_destacado_principal',
                        'activo' => 'b.activo',
                        'generar_cupon' => 'b.generar_cupon',
                        'veces_visto' => 'b.veces_visto',
                        'fecha_registro' => 'b.fecha_registro',
                        'chapita' => 'b.chapita',
                        'slug' => 'b.slug',
                        'publicado' => 'b.publicado',
                        'ncuponesconsumidos' => 'b.ncuponesconsumidos',
                        'categorias' => new Zend_Db_Expr(
                            "(SELECT GROUP_CONCAT(cb.categoria_id 
                            ORDER BY categoria_id ASC SEPARATOR '-')
                            FROM categoria_beneficio cb
                            WHERE cb.beneficio_id=b.id)"
                        ),
                        'cat_nombre' => new Zend_Db_Expr(
                            "(SELECT GROUP_CONCAT(c.nombre ORDER BY c.id ASC SEPARATOR '-')
                            FROM categoria c 
                            INNER JOIN categoria_beneficio cb ON cb.categoria_id = c.id
                            WHERE cb.beneficio_id=b.id)"
                        ),
                        'idcategoria' => new Zend_Db_Expr(
                            "(SELECT GROUP_CONCAT(c.id ORDER BY c.id ASC SEPARATOR '-')
                            FROM categoria c 
                            INNER JOIN categoria_beneficio cb ON cb.categoria_id = c.id
                            WHERE cb.beneficio_id=b.id)"
                        )
                    )
                )
                ->join(
                    array('bv' => 'beneficio_version'), 'b.id = bv.beneficio_id', 
                    array(
                        'fecha_inicio_vigencia' => 'bv.fecha_inicio_vigencia',
                        'fecha_fin_vigencia' => 'bv.fecha_fin_vigencia',
                        'stock_actual' => 'bv.stock_actual',
                        'stock' => 'bv.stock'
                    )
                )
                ->join(
                    array('tb' => 'tipo_beneficio'), 'b.tipo_beneficio_id = tb.id', 
                    array(
                        'nombre' => 'tb.nombre',
                        'abreviado' => 'tb.abreviado'
                    )
                )
                ->join(
                    array('e' => 'establecimiento'), 
                    'b.establecimiento_id = e.id', 
                    array('est_nombre' => 'e.nombre')
                )
                ->where('b.id = ?', $id);
            $record = $db->fetchRow($sql);
//            Zend_Debug::dump($record);
            $zl = new ZendLucene();
            $zl->insertarIndexBeneficio($record);
            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }
    
    public function getBeneficioInfoById($id)
    {
        $db = $this->getAdapter();
        $sql = $db->select()
            ->from(array('b' => $this->_name))
            ->joinLeft(
                array('bv' => 'beneficio_version'), 
                'b.id = bv.beneficio_id AND bv.activo = 1', 
                array("stock_actual" => "bv.stock_actual")
            )
            ->join(
                array('e' => 'establecimiento'), 
                'e.id = b.establecimiento_id', 
                array('nombre' => 'e.nombre')
            )
            ->join(
                array('tb' => 'tipo_beneficio'), 
                'b.tipo_beneficio_id = tb.id', 
                array('tipo' => 'tb.abreviado', 'tbnombre' => 'tb.nombre')
            )
            ->where('b.id = ?', $id);
        $rsb = $db->fetchRow($sql);
        
        if(!(bool)$rsb)
            return false;
        
        $sqlv = $db->select()
            ->from('beneficio_version')
            ->where('beneficio_id = ? AND activo = 1', $id);
        $rsv = $db->fetchRow($sqlv);
        
        $rsb['version'] = $rsv;
        
        $sqlc = $db->select()
            ->from(array('c' => 'categoria'), array('id' => 'c.id', 'nombre' => 'c.nombre'))
            ->join(array('cb' => 'categoria_beneficio'), 'c.id = cb.categoria_id', array())
            ->join(array('b' => $this->_name), 'b.id = cb.beneficio_id', array())
            ->where('b.id = ?', $id);
        $rsc = $db->fetchPairs($sqlc);
        $rsb['categorias'] = $rsc;
        return $rsb;
    }
    
    public function nuevaVersion($id, $dataVersion)
    {
        $bv = new Application_Model_BeneficioVersion();
        $dbbv = $bv->getAdapter();
        $where = $dbbv->quoteInto('beneficio_id = ?', $id);
        $bv->update(array('activo' => 0), $where);
        $dataVersion['beneficio_id'] = $id;
        $dataVersion['fecha_registro'] = date('Y-m-d H:i:s');
        $dataVersion['activo'] = 1;
        $bv->insert($dataVersion);
    }


    public function getBeneficiosAPublicar()
    {
        $db = $this->getAdapter();
        $sql = $db->select()
            ->from(
                array('b' => $this->_name),
                array("id"=>"b.id")
            )
            ->join(
                array("bv"=>"beneficio_version"),
                "bv.beneficio_id=b.id",
                array()
            )
            ->where("bv.activo=1")
            ->where("bv.fecha_inicio_publicacion=?", new Zend_Db_Expr("CURRENT_DATE()"))
            ->where("b.publicado=0")
            ->order("bv.id DESC");
        return  $db->fetchAll($sql);
    }
    
    public function getPromosMasConsumidas()
    {
        $db = $this->getAdapter();
        //mostramos las 8 primeras filas
        $sql = $db->select()
            ->limit(8, 0)
            ->from(array('benef'=>$this->_name), array('titulo', 'slug', 'id'))
            ->where('benef.publicado = ?', 1)
            ->where('benef.elog = ?', 0)
            ->order('ncuponesconsumidos DESC');
        
        $sql->join(
            array('benefv'=>'beneficio_version'), 'benefv.beneficio_id = benef.id',
            array('fecha_inicio_vigencia','beneficio_id')
        )
        ->where("CURRENT_DATE() BETWEEN benefv.fecha_inicio_publicacion AND benefv.fecha_fin_publicacion")
        ->where('benefv.activo = ?', 1)
        ->order('benefv.fecha_inicio_vigencia DESC');
        
        //echo $sql; exit;
        $rs = $db->fetchAll($sql);
        return $rs;
    }
    
    public function actualizarCategorias($id, $categorias) 
    {
        $c = new Application_Model_CategoriaBeneficio();
        $dbc = $c->getAdapter();
        $where = $dbc->quoteInto('beneficio_id = ?', $id);
        $c->delete($where);
        foreach ($categorias as $categoria) {
            $c->insert(array('beneficio_id' => $id, 'categoria_id' => $categoria));
        }
        return true;
    }
    
    private function cambiarEstado($id, $estado = null)
    {
        try {
            $db = $this->getAdapter();
            $db->beginTransaction();
            if (is_null($estado))
                return false;
            $where = $db->quoteInto('id = ?', $id);
            $this->update($estado, $where);
            $db->commit();
            return true;
        } catch (Exception $e) {
            $this->rollback();
            return false;
        }
    }
        
    public static function updateIndexBeneficios($id, $last = null)
    {
        $obj = new Application_Model_Beneficio();
        $beneficio = $obj->getBeneficioInfoById($id);
        if (is_null($last)) {
            if ($beneficio['publicado'] == 1 && $beneficio['activo'] == 1) {
                $zl = new ZendLucene();
                $zl->agregarNuevoDocumentoBeneficio($id);
//                if ($beneficio['es_destacado_principal'] == 1)
//                    self::setDestacadoPrincipal($id);
            }
        } else {
            $zl = new ZendLucene();
            if ($last['activo'] == 1 && $last['publicado'] == 1 && $beneficio['activo'] == 0) {
                $zl->deleteIndexBeneficio($beneficio['id']);
            } elseif ($last['activo'] == 1 && $last['publicado'] == 1
                && $beneficio['activo'] == 1 && $beneficio['publicado'] == 1) {
                //$valores = $obj->getBeneficioForLucene($id);
                //$zl->updateIndexBeneficio($id);
//                if ($beneficio['es_destacado_principal'] == 1)
//                    self::setDestacadoPrincipal($id);
            }
        }
        self::refreshCache();
    }
    
    public function getBeneficioForLucene($id)
    {
        $db = $this->getAdapter();
        $sql = $db->select()
            ->from(
                array('b' => $this->_name), array(
                'idbeneficio' => 'b.id',
                'establecimiento_id' => 'b.establecimiento_id',
                'tipo_beneficio_id' => 'b.tipo_beneficio_id',
                'titulo' => 'b.titulo',
                'descripcion' => 'b.descripcion_corta',
                'valor' => 'b.valor',
                'cuando' => 'b.cuando',
                'direccion' => 'b.direccion',
                'email_info' => 'b.email_info',
                'telefono_info' => 'b.telefono_info',
                'chapita_color' => 'b.chapita_color',
                'path_logo' => 'b.path_logo',
                'maximo_por_subscriptor' => 'b.maximo_por_subscriptor',
                'sin_stock' => 'b.sin_stock',
                'es_destacado' => 'b.es_destacado',
                'es_destacado_principal' => 'b.es_destacado_principal',
                'activo' => 'b.activo',
                'generar_cupon' => 'b.generar_cupon',
                'veces_visto' => 'b.veces_visto',
                'fecha_registro' => 'b.fecha_registro',
                'chapita' => 'b.chapita',
                'slug' => 'b.slug',
                'publicado' => 'b.publicado',
                'ncuponesconsumidos' => 'b.ncuponesconsumidos',
                'categorias' => new Zend_Db_Expr(
                    "(SELECT GROUP_CONCAT(cb.categoria_id 
                            ORDER BY categoria_id ASC SEPARATOR '-')
                            FROM categoria_beneficio cb
                            WHERE cb.beneficio_id=b.id)"
                ),
                'cat_nombre' => new Zend_Db_Expr(
                    "(SELECT GROUP_CONCAT(c.nombre ORDER BY c.id ASC SEPARATOR '-')
                            FROM categoria c 
                            INNER JOIN categoria_beneficio cb ON cb.categoria_id = c.id
                            WHERE cb.beneficio_id=b.id)"
                ),
                'idcategoria' => new Zend_Db_Expr(
                    "(SELECT GROUP_CONCAT(c.id ORDER BY c.id ASC SEPARATOR '-')
                            FROM categoria c 
                            INNER JOIN categoria_beneficio cb ON cb.categoria_id = c.id
                            WHERE cb.beneficio_id=b.id)"
                )
                )
            )
            ->join(
                array('bv' => 'beneficio_version'), 'b.id = bv.beneficio_id', array(
                'fecha_inicio_vigencia' => 'bv.fecha_inicio_vigencia',
                'fecha_fin_vigencia' => 'bv.fecha_fin_vigencia',
                'stock_actual' => 'bv.stock_actual',
                'stock' => 'bv.stock'
                )
            )
            ->join(
                array('tb' => 'tipo_beneficio'), 'b.tipo_beneficio_id = tb.id', array(
                'nombre' => 'tb.nombre',
                'abreviado' => 'tb.abreviado'
                )
            )
            ->join(
                array('e' => 'establecimiento'), 
                'b.establecimiento_id = e.id', 
                array('est_nombre' => 'e.nombre')
            )
            ->where('b.id = ?', $id);
        $record = $db->fetchRow($sql);
        return $record;
    }
    
    public static function refreshCache()
    {
        $beneficio = new Application_Model_Beneficio();
        @$beneficio->getMainDestacado(false);
        @$beneficio->getDestacadosPortada(false);
    }
    
    public static function getBeneficiosInfoCatalogo()
    {
        $categorias = Application_Model_Categoria::getCategorias(false, array('activo' => 1));

        $obj = new Application_Model_Beneficio();
        $beneficios = array();
//        var_dump($categorias);
        foreach ($categorias as $key => $categoria) {
            $db = $obj->getAdapter();
            $sql = $db->select()
                ->from(
                    array('b' => $obj->_name), array(
                    'nombre' => 'b.titulo',
                    'descripcion' => 'b.descripcion',
                    'donde' => 'b.direccion',
                    'cuando' => 'b.cuando',
                    'valor' => 'b.valor',
                    'telefono' => 'b.telefono_info',
                    'email' => 'b.email_info',
                    'foto' => 'b.path_logo',
                    'chapita' => 'b.chapita',
                    'informacion' => 'b.informacion_adicional'
                    )
                )
                ->join(
                    array('e' => 'establecimiento'), 
                    'b.establecimiento_id = e.id AND e.activo = 1', 
                    array('e_nombre' => 'e.nombre', 'e_logo' => 'e.path_imagen')
                )
                ->join(array('cb' => 'categoria_beneficio'), 'cb.beneficio_id = b.id', array())
                ->where('cb.categoria_id = ?', $categoria['id'])
                ->where('b.publicado = 1 AND b.activo = 1');
            $rs = $db->fetchAll($sql);
            if(count($rs) > 0)
                $beneficios[$categoria['nombre']] = $rs;
        }
        return $beneficios;
    }
    
    public static function setDestacadoPrincipal($id) 
    {
        $obj = new Application_Model_Beneficio();
        $db = $obj->getAdapter();
        $where = $db->quoteInto('es_destacado_principal = ?', 1);
        $obj->update(array('es_destacado_principal' => 0), $where);
        $where = $db->quoteInto('id = ?', $id);
        $obj->update(array('es_destacado_principal' => 1), $where);
    }

    public static function getBeneficiosxEstablecimiento($idE="")
    {
        $obj = new Application_Model_Beneficio();
        $db = $obj->getAdapter();
            $sql = $db->select()
                ->from(
                    array('b' => $obj->_name),
                    array("id" => "id", "titulo" => "titulo")
                )
                ->join(
                    array("e" => "establecimiento"),
                    "b.establecimiento_id = e.id",
                    array()
                )
                ->where("b.publicado=1");

        if ($idE!="") {
            $sql = $sql->where("b.establecimiento_id=?", $idE);
        }
        $result = $db->fetchPairs($sql);
        return $result;
    }
    
    
    public static function getBeneficiosActivosByEstaIdAndSusId($establecimientoId, $suscriptorId)
    {
        $obj = new Application_Model_Beneficio();
        $db = $obj->getAdapter();
        $sql = $db->select()
                ->from(
                    array("b" => $obj->_name),
                    array(
                        "id"                     => "b.id",
                        "titulo"                 => "b.titulo",
                        "codigo"                 => "b.codigo",
                        "stock_actual"           => "bv.stock_actual",
                        "stock"                  => "bv.stock"
                    )
                )
                ->join(
                    array("bv" => "beneficio_version"),
                    "b.id = bv.beneficio_id",
                    array()
                )
                ->where("b.publicado = 1")
                ->where("b.activo = 1")
                ->where(
                    "b.sin_limite_por_suscriptor = 1 OR 
                        (b.maximo_por_subscriptor > (SELECT COUNT(*) 
                        FROM cupon WHERE beneficio_id = b.id AND suscriptor_id = ?))",
                    $suscriptorId
                )
                ->where("bv.activo = 1")
                ->where("b.sin_stock = 1 OR bv.stock_actual > 0")
                ->where(
                    "CURRENT_DATE()
                     BETWEEN bv.fecha_inicio_vigencia AND bv.fecha_fin_vigencia"
                )
                ->where("b.establecimiento_id = ?", $establecimientoId)
                ->group("b.id");
//        return $sql->assemble();
        return $db->fetchAll($sql);
    }
    
    public static function getBeneficiosByCodigo($codigo)
    {
        $obj = new Application_Model_Beneficio();
        $db = $obj->getAdapter();
        $sql = $db->select()
                ->from(
                    array("b" => $obj->_name),
                    array(
                        "id"                        => "b.id",
                        "titulo"                    => "b.titulo",
                        "maximo_por_subscriptor"    => "b.maximo_por_subscriptor",
                        "sin_limite_por_suscriptor" => "b.sin_limite_por_suscriptor",
                        "est_id"                    => "e.id",
                        "est_activo"                => "e.activo",
                        "stock_actual"              => "bv.stock_actual",
                        "sin_stock"                 => "b.sin_stock",
                        "stock"                     => "bv.stock"
                    )
                )
                ->join(array('e' => 'establecimiento'), 'e.id = b.establecimiento_id', array())
                ->join(
                    array("bv" => "beneficio_version"),
                    "b.id = bv.beneficio_id",
                    array()
                )
                ->where("b.publicado = 1")
                ->where("b.activo = 1")
                ->where("bv.activo = 1")
//                ->where("b.sin_stock = 1 OR bv.stock_actual > 0")
                ->where(
                    "CURRENT_DATE()
                     BETWEEN bv.fecha_inicio_vigencia AND bv.fecha_fin_vigencia"
                )
                ->where("b.codigo = ?", $codigo)
                ->group("b.id");
        return $db->fetchRow($sql);
    }
    
    public static function getBeneficioByCodigo($codigo)
    {
        $obj = new Application_Model_Beneficio();
        $db = $obj->getAdapter();
        $sql = $db->select()->from(array("b" => $obj->_name))->where("b.codigo = ?", $codigo);
        return $db->fetchRow($sql);
    }
    
    public function getTipoMonedaById()
    {
        $db = $this->getAdapter();
        $sql = $db->select("")
            ->from(array("b" => $this->_name), "b.tipo_moneda_id")
            ->join(array('tm' => 'tipo_moneda'), 'tm.id = b.tipo_moneda_id', array("tipo_moneda"=>"tm.abreviatura"))
            ->where("b.id = ?", $this->getId());
//        echo $sql;exit;
        return $db->fetchRow($sql);
    }
    
    public function getStatusBeneficioById($id)
    {
        $db = $this->getAdapter();
        $sql = $db->select()
            ->from(array('b' => $this->_name), array('b.titulo', 'b.ncuponesconsumidos', 'b.activo', 'b.publicado'))
            /*->join(
                array('e' => 'establecimiento'), 
                'e.id = b.establecimiento_id', 
                array('nombre' => 'e.nombre')
            )*/
            /*->join(
                array('tb' => 'tipo_beneficio'), 
                'b.tipo_beneficio_id = tb.id', 
                array('tipo' => 'tb.abreviado', 'tbnombre' => 'tb.nombre')
            )*/
            ->where('b.id = ?', $id);
        $rsb = $db->fetchRow($sql);
        
        if(!(bool)$rsb)
            return false;
        
        $sqlv = $db->select()
            ->from('beneficio_version')
            ->where('beneficio_id = ? AND activo = 1', $id);
        $rsv = $db->fetchRow($sqlv);
        
        $rsb['version'] = $rsv;
        
        /*$sqlc = $db->select()
            ->from(array('c' => 'categoria'), array('id' => 'c.id', 'nombre' => 'c.nombre'))
            ->join(array('cb' => 'categoria_beneficio'), 'c.id = cb.categoria_id', array())
            ->join(array('b' => $this->_name), 'b.id = cb.beneficio_id', array())
            ->where('b.id = ?', $id);
        $rsc = $db->fetchPairs($sqlc);
        $rsb['categorias'] = $rsc;*/
        
        $sqlcp = $db->select()
            ->from(array('cp'=>'cupon'), array('cant' => 'count(id)'))
            ->where("beneficio_id = ? AND estado = 'generado'", $id);
        $rscp = $db->fetchRow($sqlcp);
        $rsb['ncuponesgen'] = $rscp['cant'];
        
        return $rsb;
    }
    
    /*para SEO Ticket 10525 URLIMG migracion*/
    public static function getBeneficioByPath($path_logo)
    {
        $obj = new Application_Model_Beneficio();
        $db = $obj->getAdapter();
        $sql = $db->select()
                  ->from(array("b" => $obj->_name),array("id","titulo","slug"))
                  ->where("b.path_logo_bkp = ?", $path_logo);
//        echo $sql->assemble();exit;
        return $db->fetchRow($sql);
    }
    
    public static function getBeneficioSitemap()
    {
        
        $obj = new Application_Model_Beneficio();
        $db = $obj->getAdapter();
        $sql = $db->select()
                ->from(
                    array("b" => $obj->_name),
                    array(
                        "id"                       => "b.id",
                        "titulo"                   => "b.titulo",
                        "slug"                     => "b.slug",
                        "path_logo"                => "b.path_logo",
                        "fecha_inicio_publicacion" => "bv.fecha_inicio_publicacion"
                    )
                )
                ->join(
                    array("bv" => "beneficio_version"),
                    "b.id = bv.beneficio_id",
                    array()
                )
                ->where("b.activo = 1")
                ->where("b.publicado = 1")
                ->where("bv.activo = 1")
                ->where(
                    "CURRENT_DATE()
                     BETWEEN bv.fecha_inicio_publicacion AND bv.fecha_fin_publicacion"
                )
                ->where('b.elog = ?', 0)
                ->group("b.id")
                ->order("bv.fecha_inicio_publicacion desc");
//        echo $sql->assemble();exit;
        return $db->fetchAll($sql);

    }
    
    public function getMaxBeneficio()
    {
        $db = $this->getAdapter();
        $sql = $db->select()
            ->from(array('benef'=>$this->_name),
                         array('id' => new Zend_Db_Expr("MAX(id)")));
        //echo $sql->assemble();exit;
        $rs = $db->fetchRow($sql);
        return $rs;
    }
}
