<?php

class Application_Model_Cupon extends App_Db_Table_Abstract
{
    protected $_name = 'cupon';
    protected $_id;
    protected $_usuarioId;
    protected $_suscriptorId;
    protected $_beneficioId;
    protected $_codigo;
    protected $_fechaEmision;
    protected $_tipoMonedaId = '1';
    protected $_mesId;
    protected $_anio;
    protected $_fechaVigencia;
    protected $_estado;
    protected $_establecimientoId;

    const ESTADO_GENERADO = 'generado';
    const ESTADO_CONSUMIDO = 'consumido';
    const ESTADO_REDIMIDO = 'redimido';
    const ESTADO_CONCILIADO = 'conciliado';
    const ESTADO_ELIMINADO = 'eliminado';
    
    const MEDIO_PUBLICACION_WEB = 'portal';
    const MEDIO_REDENCION_SMS = 'sms';
    
    protected $_fechaEmisionFrom;
    protected $_fechaEmisionAt;
    protected $_fechaConsumoFrom;
    protected $_fechaConsumoAt;
    protected $_fechaEliminadoFrom;
    protected $_fechaEliminadoAt;
    protected $_contentFiltro;
    protected $_namePromo;
    
    public function getEstablecimiento_id()
    {
        return $this->_establecimientoId;
    }

    public function setEstablecimiento_id($_establecimientoId)
    {
        $this->_establecimientoId = $_establecimientoId;
    }
    
    public function setMesId($_contentFiltro)
    {
        $this->_mesId = $_contentFiltro;
    }
    
    public function getMesId()
    {
        return $this->_mesId;
    }
    
    public function setAnio($_contentFiltro)
    {
        $this->_Anio = $_contentFiltro;
    }
    
    public function getAnio()
    {
        return $this->_Anio;
    }
    
    public function setTipoMonedaId($_contentFiltro)
    {
        $this->_tipoMonedaId = $_contentFiltro;
    }
    
    public function getTipoMonedaId()
    {
        return $this->_tipoMonedaId;
    }
    
    public function setContent_filtro($_contentFiltro)
    {
        $this->_contentFiltro = $_contentFiltro;
    }
    
    public function getContent_filtro()
    {
        return $this->_contentFiltro;
    }
    public function setName_promo($_namePromo)
    {
        $this->_namePromo = $_namePromo;
    }
    
    public function getName_promo()
    {
        return $this->_namePromo;
    }
    
    public function setFecha_emision_from($_fechaEmisionFrom)
    {
        $this->_fechaEmisionFrom = $_fechaEmisionFrom;
    }
    
    public function getFecha_emision_from()
    {
        return $this->_fechaEmisionFrom;
    }
    
    public function setFecha_emision_at($_fechaEmisionAt)
    {
        $this->_fechaEmisionAt = $_fechaEmisionAt;
    }
    
    public function getFecha_emision_at()
    {
        return $this->_fechaEmisionAt;
    }
    
    public function setFecha_consumo_from($_fechaConsumoFrom)
    {
        $this->_fechaConsumoFrom = $_fechaConsumoFrom;
    }
    
    public function getFecha_consumo_from()
    {
        return $this->_fechaConsumoFrom;
    }
    
    public function setFecha_consumo_at($_fechaConsumoAt)
    {
        $this->_fechaConsumoAt = $_fechaConsumoAt;
    }
    
    public function getFecha_consumo_at()
    {
        return $this->_fechaConsumoAt;
    }
    
    public function setFecha_eliminado_from($_fechaEliminadoFrom)
    {
        $this->_fechaEliminadoFrom = $_fechaEliminadoFrom;
    }
    
    public function getFecha_eliminado_from()
    {
        return $this->_fechaEliminadoFrom;
    }
    
    public function setFecha_eliminado_at($_fechaEliminadoAt)
    {
        $this->_fechaEliminadoAt = $_fechaEliminadoAt;
    }
    
    public function getFecha_eliminado_at()
    {
        return $this->_fechaEliminadoAt;
    }
    
    public function getId()
    {
        return $this->_id;
    }

    public function getUsuario_id()
    {
        return $this->_usuarioId;
    }

    public function getSuscriptor_id()
    {
        return $this->_suscriptorId;
    }

    public function getBeneficio_id()
    {
        return $this->_beneficioId;
    }

    public function getCodigo()
    {
        return $this->_codigo;
    }

    public function getFecha_emision()
    {
        return $this->_fechaEmision;
    }

    public function getFecha_vigencia()
    {
        return $this->_fechaVigencia;
    }

    public function getEstado()
    {
        return $this->_estado;
    }

    public function setId($_id)
    {
        $this->_id = $_id;
    }

    public function setUsuario_id($_usuarioId)
    {
        $this->_usuarioId = $_usuarioId;
    }

    public function setSuscriptor_id($_suscriptorId)
    {
        $this->_suscriptorId = $_suscriptorId;
    }

    public function setBeneficio_id($_beneficioId)
    {
        $this->_beneficioId = $_beneficioId;
    }

    public function setCodigo($_codigo)
    {
        $this->_codigo = $_codigo;
    }

    public function setFecha_emision($_fechaEmision)
    {
        $this->_fechaEmision = $_fechaEmision;
    }

    public function setFecha_vigencia($_fechaVigencia)
    {
        $this->_fechaVigencia = $_fechaVigencia;
    }

    public function setEstado($_estado)
    {
        $this->_estado = $_estado;
    }

    public function getCupones()
    {
        $sql = $this->select()->from($this->_name);
        $rs = $this->getAdapter()->fetchAll($sql);
        return $rs;
    }
    
    public function getMaxCupon()
    {
        $db = $this->getAdapter();
        $sql = $db->select()
            ->from(array('benef'=>$this->_name),
                         array('id' => new Zend_Db_Expr("MAX(id)")));
        //echo $sql->assemble();exit;
        $rs = $db->fetchRow($sql);
        return $rs;
    }

    public function getCuponesByBenefSuscripGen()
    {
        $sql = $this->select()->from($this->_name)
            ->where('suscriptor_id = ?', $this->getSuscriptor_id())
            ->where('beneficio_id = ?', $this->getBeneficio_id())
            ->where('estado = ?', self::ESTADO_GENERADO)
            ->order('codigo asc');
        //echo $sql; exit;
        $rs = $this->getAdapter()->fetchAll($sql);
        return $rs;
    }
    
    public function getInfoById()
    {
        $db = $this->getAdapter();
        $sql = $db->select()
            ->from(
                array('cupon' => $this->_name), 
                array(
                    'cupon.id',
                    'cupon.fecha_redencion',
                    'cupon.beneficio_id',
                    'cupon.suscriptor_id'
                )
            );
        $sql->joinLeft(
            array('benef'=>'beneficio'), 'benef.id = cupon.beneficio_id',
            array('benef.titulo')
        );
        $sql->joinleft(
            array('susc'=>'suscriptor'), 'susc.id = cupon.suscriptor_id',
            array('nombre'=>"CONCAT(susc.nombres,' ',susc.apellido_paterno,' ',susc.apellido_materno)")
        );
        
        $id=$this->getId();
        if (!empty($id)) {
            $sql->where('cupon.id = ?', $id);
        }        
        
        $rs = $db->fetchRow($sql);
        return empty($rs)?false:$rs;
    }
    
    public function getCantCuponesByBenef($array=null)
    {
        $sql = $this->select()
            ->from(array('cup' => $this->_name), array('COUNT(1) cant'))
            ->where('cup.beneficio_id = ?', $this->_beneficioId);
        
        if (!empty($array['estado'])) {
            $sql->where("cup.estado = ?",$array['estado']);
        } else {
            $sql->where("cup.estado != ?","eliminado");
        }
        $rs = $this->getAdapter()->fetchRow($sql);
        //echo $rs["cant"].'---'.$cant; exit;
        return $rs["cant"];
    }
    
    public function getCantCuponesByBenefAndSuscrip()
    {
        $sql = $this->select()
            ->from(array('cup' => $this->_name), array('COUNT(1) cant'))
            ->where('cup.beneficio_id = ?', $this->_beneficioId)
            ->where('cup.suscriptor_id = ?', $this->_suscriptorId);
        $sql->where("cup.estado != ?","eliminado");
        $rs = $this->getAdapter()->fetchRow($sql);
        //echo $rs["cant"].'---'.$cant; exit;
        $data["cupon_generado"]=empty($rs)?0:$rs["cant"];
        return $data;
    }
    
    public function getCantCuponesByBenefAndTipDescuento()
    {
        $sql = $this->select()
            ->from(array('cup' => $this->_name), array('COUNT(1) cant'))
            ->where('cup.beneficio_id = ?', $this->_beneficioId);
        $sql->where("cup.estado != ?","eliminado");
        $rs = $this->getAdapter()->fetchRow($sql);
        //echo $rs["cant"].'---'.$cant; exit;
        return $rs["cant"];
    }
    
    public function getCantCuponesByBenefAndCantGestorGenAndTipDesc($idBen,$cant,$idTipDesc=null)
    {
        $sql = $this->select()
            ->from(array('cup' => $this->_name), array('COUNT(1) cant'))
            ->where('cup.beneficio_id = ?', $idBen);
        $sql->where("cup.estado != ?","eliminado");
        if (!empty($idTipDesc)) {
            $sql->where('cup.detalle_tipo_promocion_id = ?', $idTipDesc);
        }
        $rs = $this->getAdapter()->fetchRow($sql);
        //echo $rs["cant"].'---'.$cant; exit;
        return array("valor"=>(!empty($rs["cant"]) ? (($rs["cant"]<=$cant)?true:false) : true),"cant"=>$rs["cant"]);
    }
    
    public static function getCantCuponesByBenefAndTipDesc($idBen,$idTipDesc)
    {
        $objTable = new Application_Model_Cupon();
        $sql = $objTable->select()
            ->from(array('cup' => $objTable->_name), array('COUNT(1) cant'))
            ->where('cup.beneficio_id = ?', $idBen)
            ->where('cup.detalle_tipo_promocion_id = ?', $idTipDesc);
        $sql->where("cup.estado != ?","eliminado");
        $rs = $objTable->getAdapter()->fetchRow($sql);
        //echo $rs["cant"].'---'.$cant; exit;
        return !empty($rs["cant"])?$rs["cant"]:0;
    }
    
    public static function getCantCuponesByBenefAndTipDescAndSuscriptor($idBen,$idTipDesc,$idSuscriptor)
    {
        $objTable = new Application_Model_Cupon();
        $sql = $objTable->select()
            ->from(array('cup' => $objTable->_name), array('COUNT(1) cant'))
            ->where('cup.beneficio_id = ?', $idBen);
        if (!empty($idTipDesc)) {
            $sql->where('cup.detalle_tipo_promocion_id = ?', $idTipDesc);
        }
        if (!empty($idSuscriptor)) {
            $sql->where('cup.suscriptor_id = ?', $idSuscriptor);
        }
        $sql->where("cup.estado != ?","eliminado");
        //echo $sql;exit
        $rs = $objTable->getAdapter()->fetchRow($sql);
        //echo $rs["cant"].'---'; exit;
        return !empty($rs["cant"])?$rs["cant"]:0;
    }
    
    public static function getAniosbySuscritorId($idSuscriptor)
    {
        $objTable = new Application_Model_Cupon();
        $sql = $objTable->select()
            ->from(array('cup' => $objTable->_name), array('YEAR(fecha_redencion) id, YEAR(fecha_redencion) value'))
            ->where('cup.suscriptor_id = ?', $idSuscriptor)
            ->where('cup.estado = ?', self::ESTADO_REDIMIDO);
        //echo $sql;exit
        $rs = $objTable->getAdapter()->fetchPairs($sql);
        //echo $rs["cant"].'---'; exit;
        return $rs;
    }
    
    public function getCantCuponesBySucripGen($idBen,$cant,$idTipDesc=null)
    {
        $sql = $this->select()
            ->from(array('cup' => $this->_name), array('COUNT(1) cant'))
            ->where('cup.beneficio_id = ?', $idBen)
            ->group('suscriptor_id')
            ->limit('1')
            ->order('1 desc');
        if (!empty($idTipDesc)) {
            $sql->where('cup.detalle_tipo_promocion_id = ?', $idTipDesc);
        }
        $sql->where("cup.estado != ?","eliminado");
        $rs = $this->getAdapter()->fetchRow($sql);
        return array("valor"=>(!empty($rs["cant"]) ? (($rs["cant"]<=$cant)?true:false) : true),"cant"=>$rs["cant"]);
    }
    
    public function getCupon()
    {
        $sql = $this->select()
            ->from($this->_name)
            ->where('id = ?', $this->getId());
        $rs = $this->getAdapter()->fetchRow($sql);
        return $rs;
    }

    public function getNroCuponesGenBySuscrip()
    {
        $sql = $this->select()->from($this->_name, array('cant' => 'count(1)'))
            ->where('suscriptor_id = ?', $this->getSuscriptor_id())
            ->where('beneficio_id = ?', $this->getBeneficio_id())
            ->group('beneficio_id');
        //echo $sql;
        $sql->where("estado != ?","eliminado");
        $rs = $this->getAdapter()->fetchRow($sql);
        return (!empty($rs) ? $rs['cant'] : 0);
    }
    
    public function getNroCuponesGenByBeneficio()
    {
        $sql = $this->select()->from($this->_name, array('cant' => 'count(1)'))
            ->where('beneficio_id = ?', $this->getBeneficio_id());
        //echo $sql;
        $sql->where("estado != ?","eliminado");
        $rs = $this->getAdapter()->fetchRow($sql);
        return (!empty($rs) ? $rs['cant'] : 0);
    }

    public function getMisConsumosPorTipos($tipos = '')
    {
        $db = $this->getAdapter();
        $sql = $db->select()->from(array('cupon' => $this->_name))
            ->where('suscriptor_id = ?', $this->getSuscriptor_id())
            ->where("estado in('".self::ESTADO_CONSUMIDO."','".self::ESTADO_REDIMIDO."')")
            ->order('fecha_fin_vigencia desc');
        
        $sql->join(
            array('benef'=>'beneficio'), 'benef.id = cupon.beneficio_id',
            array('titulo','tipo_beneficio_id','slug')
        );

        if($tipos!=Application_Model_TipoBeneficio::TIPO_ALL):
            $tipos = (empty($tipos)?'0':$tipos);
            $sql->where("benef.tipo_beneficio_id in ($tipos)");
        endif;
        //echo $sql; exit;
        //$rs = $db->fetchAll($sql);
        //return $rs;
        return $sql;
    }
    
    public function getMontoPorAnio ($input){
        $tipos = $input['tipos'];
        
        $db = $this->getAdapter();
        $sql = $db->select()->from(array('cupon' => $this->_name),
                array('cupon.tipo_moneda_id','SUM(`cupon`.`monto_descontado`) ahorro_anual'))
            ->where('suscriptor_id = ?', $this->getSuscriptor_id())
            ->where("estado in('".self::ESTADO_REDIMIDO."')");
        $sql->join(
                array('benef'=>'beneficio'), 'benef.id = cupon.beneficio_id',
                false
            );
        $sql->where('YEAR(cupon.fecha_consumo) = ?', $this->getAnio())
            ->group('cupon.tipo_moneda_id');
        
//        if($tipos!=Application_Model_TipoBeneficio::TIPO_ALL):
//            $tipos = (empty($tipos)?'0':$tipos);
//            $sql->where("benef.tipo_beneficio_id in ($tipos)");
//        endif;
        //echo $sql; exit;
        $rs = $db->fetchAll($sql);
        return $rs;
    }
    
    public function getObtenerUltimaFechaRedencion (){
        $db = $this->getAdapter();
        $sql = $db->select()->limit(1)
            ->from(array('cupon' => $this->_name),array('YEAR(fecha_consumo) anio','MONTH(fecha_consumo) mes'))
            ->where('suscriptor_id = ?', $this->getSuscriptor_id())
            ->where("estado in('".self::ESTADO_REDIMIDO."')");
        $sql->order('fecha_consumo DESC');
        //echo $sql; exit;
        $rs = $db->fetchRow($sql);
        return $rs;
    }
    
    public function getMisConsumosPorTipoAndMoneda($input)
    {
        $tipos = $input['tipos'];
        $ord = !empty($input['ord'])?$input['ord']:'DESC';
        $col = !empty($input['col'])?
            (($input['col']=='tipo')?'tipo.nombre':'cupon.fecha_consumo')
            :'tipo.nombre';
                
        $db = $this->getAdapter();
        $sql = $db->select()->from(array('cupon' => $this->_name),
                array('cupon.fecha_consumo','cupon.precio_suscriptor','cupon.monto_descontado','cupon.tipo_moneda_id'))
            ->where('cupon.suscriptor_id = ?', $this->getSuscriptor_id())
            ->where("cupon.estado in('".self::ESTADO_REDIMIDO."')");
        $sql->join(
                array('benef'=>'beneficio'), 'benef.id = cupon.beneficio_id',
                array('benef.titulo')
            )
            ->join(
                array('tipo'=>'tipo_beneficio'), 'tipo.id = benef.tipo_beneficio_id',
                array('tipo.nombre')
            )
            ->join(
                array('tipmon'=>'tipo_moneda'), 'tipmon.id = cupon.tipo_moneda_id',
                array('tip_moneda'=>'tipmon.abreviatura')
            );
        $sql->order( $col.' '.$ord );
        $sql->where('MONTH(cupon.fecha_consumo) = ?', $this->getMesId())
            ->where('YEAR(cupon.fecha_consumo) = ?', $this->getAnio());
        
        
        if($tipos!=Application_Model_TipoBeneficio::TIPO_ALL):
            $tipos = (empty($tipos)?'0':$tipos);
            $sql->where("benef.tipo_beneficio_id in ($tipos)");
        endif;
        //echo $sql; exit;
        $rs = $db->fetchAll($sql);
        //return $rs;
        return $rs;
    }
    
    public function getPromosMasConsumidas()
    {
        $db = $this->getAdapter();
        //mostramos las 8 primeras filas
        $sql = $db->select()
            ->limit(8, 0)
            ->from(array('cup'=>$this->_name), array('cant'=>'count(1)', 'beneficio_id'))
            ->join(
                array('benef'=>'beneficio'), 'benef.id = cup.beneficio_id', 
                array('titulo', 'slug')
            )
            ->where("estado in('".self::ESTADO_CONSUMIDO."','".self::ESTADO_REDIMIDO."')")
            ->group('cup.beneficio_id')
            ->group('benef.titulo')
            ->group('benef.slug')
            ->order('cant DESC');
        
        $sql->join(
            array('benefv'=>'beneficio_version'), 'benefv.beneficio_id = benef.id',
            array('fecha_inicio_vigencia')
        )
        ->where('benefv.activo = ?', 1)
        ->order('benefv.fecha_inicio_vigencia DESC');
        
        //echo $sql; exit;
        $rs = $db->fetchAll($sql);
        return $rs;
    }
    
    public function getConsumosPorEstablecimiento($redimido_por=null)
    {
        $db = $this->getAdapter();
        $sql = $db->select()->from(
            array('cupon' => $this->_name),
            array(
                'fecha_consumo'=>"COALESCE(fecha_consumo,'')",
                'monto_descontado','fecha_emision','cupon.estado','numero_comprobante'
                ,'comentario_redencion','cupon.id','cupon.fecha_eliminacion'
            )
        )->order('cupon.fecha_consumo desc');
        
        $sql->join(
            array('benef'=>'beneficio'), 'benef.id = cupon.beneficio_id',
            array('titulo')
        )
        ->where('benef.elog = ?', 0);
        
        if (!empty($this->_establecimientoId)){
            $sql->where('benef.establecimiento_id = ?', $this->getEstablecimiento_id());
        }
        
        if (!empty($redimido_por)){
            $sql->where('cupon.redimido_por = ?', $redimido_por);
        } else {            
            $sql->joinLeft(
                    array('admin'=>'administrador'), 'admin.usuario_id = cupon.redimido_por',
                    array('redimido_por' => new Zend_Db_Expr("CONCAT(admin.nombres, ' ',
                        IFNULL(admin.apellido_paterno,''), ' ', 
                        IFNULL(admin.apellido_materno,''))"))
                )->joinLeft(
                    array('adminEli'=>'administrador'), 'adminEli.usuario_id = cupon.eliminado_por',
                    array('eliminado_por' => new Zend_Db_Expr("CONCAT(adminEli.nombres, ' ',
                        IFNULL(adminEli.apellido_paterno,''), ' ', 
                        IFNULL(adminEli.apellido_materno,''))")
                )
            );
        }
        
        $sql->join(
            array('susc'=>'suscriptor'), 'susc.id = cupon.suscriptor_id',
            array('codigo_suscriptor', 'tipo_documento', 'numero_documento', 
                'names'=>"CONCAT(susc.nombres,' ',susc.apellido_paterno,' ',susc.apellido_materno)"
                ,'es_invitado','es_suscriptor')
        );
        
        $sql->join(
            array('tbenef'=>'tipo_beneficio'), 'tbenef.id = benef.tipo_beneficio_id',
            array('abreviado')
        );
                
        $sql->joinLeft(
            array('tmoneda'=>'tipo_moneda'), 'tmoneda.id = benef.tipo_moneda_id',
            array('tipmoneda'=>'abreviatura')
        );
        
        if( !empty( $this->_estado ) ) $sql->where('cupon.estado = ?', $this->getEstado());
        else $sql->where('cupon.estado != ?', "eliminado");
        
        if( !empty( $this->_fechaEmisionFrom ) && !empty( $this->_fechaEmisionAt ) ):
            $sql->where(
                "DATE(cupon.fecha_consumo) BETWEEN STR_TO_DATE('".
                $this->getFecha_emision_from()."','%d/%m/%Y') AND STR_TO_DATE('".
                $this->getFecha_emision_at()."','%d/%m/%Y')"
            );
        elseif ( !empty( $this->_fechaEmisionFrom ) ):
            $sql->where(
                "DATE_FORMAT(cupon.fecha_consumo,'%d/%m/%Y') >= ?",
                $this->getFecha_emision_from()
            );
        elseif ( !empty($this->_fechaEmisionAt) ):
            $sql->where(
                "DATE_FORMAT(cupon.fecha_consumo,'%d/%m/%Y') <= ?",
                $this->getFecha_emision_at()
            );
        endif;
        
        if( !empty( $this->_fechaEliminadoFrom ) && !empty( $this->_fechaEliminadoAt ) ):
            $sql->where(
                "DATE(cupon.fecha_consumo) BETWEEN STR_TO_DATE('".
                $this->getFecha_eliminado_from()."','%d/%m/%Y') AND STR_TO_DATE('".
                $this->getFecha_eliminado_at()."','%d/%m/%Y')"
            );
        elseif ( !empty( $this->_fechaEliminadoFrom ) ):
            $sql->where(
                "DATE_FORMAT(cupon.fecha_consumo,'%d/%m/%Y') >= ?",
                $this->getFecha_eliminado_from()
            );
        elseif ( !empty($this->_fechaEliminadoAt) ):
            $sql->where(
                "DATE_FORMAT(cupon.fecha_consumo,'%d/%m/%Y') <= ?",
                $this->getFecha_eliminado_at()
            );
        endif;
        
        
        if( !empty( $this->_contentFiltro ) ):
            $this->_contentFiltro = $this->getRemoveApostrophe($this->_contentFiltro);
            $sql->where(
                "CONCAT(COALESCE(susc.nombres,''),' ',COALESCE(susc.apellido_paterno,'')," .
                "' ',COALESCE(susc.apellido_materno,''),' '".
                ",COALESCE(susc.tipo_documento,''),' ',COALESCE(susc.numero_documento,'')) LIKE '%".
                $this->getContent_filtro()."%'"
            );
        endif;
        
        if ( !empty( $this->_namePromo ) ):
            $this->_namePromo = $this->getRemoveApostrophe($this->_namePromo);
            $sql->where("benef.titulo LIKE '%".$this->getName_promo()."%'");
        endif;
        //echo $sql; exit;
        //$rs = $db->fetchAll($sql);
        //return $rs;
        return $sql;
    }
    
    public static function getEstadosCupon()
    {
        $objTable = new Application_Model_Cupon();
        $cadenavalor = $objTable->getAdapter()
            ->query('SHOW COLUMNS FROM '.$objTable->_name." LIKE 'estado'")->fetchColumn(1);
        //var_dump($cadenavalor); //exit;
        $cadenavalor = str_replace("enum('", '', $cadenavalor);
        $cadenavalor = str_replace("'", '', $cadenavalor);
        $cadenavalor = str_replace(")", '', $cadenavalor);
        $valores = explode(',', $cadenavalor); $array = array();
        foreach ($valores as $item) {
            if ($item!='eliminado') $array[$item] = $item;
        }
        //print_r($valores); exit;
        return $array;
    }
    

    public function cuponXDni($numDni, $tipodoc, $idEstablecimiento)
    {
        $sql = $this->getAdapter()
            ->select()
            ->from(array('c'=>$this->_name))
            ->joinInner(array('b'=>'beneficio'), 'b.id = c.beneficio_id', array())
            ->joinInner(
                array('s'=>'suscriptor'), 
                's.id = c.suscriptor_id', 
                array(
                    'id_suscriptor'=>'s.id', 
                    's.nombres',
                    'apellidos' => new Zend_Db_Expr("CONCAT(s.apellido_paterno, ' ', s.apellido_materno)"),
                    's.activo'
                )
            )
            ->where('b.activo = ?', 1)
            //->where('s.activo = ?', 1)
            ->where('b.establecimiento_id = ?', $idEstablecimiento)
            ->where('s.numero_documento = ?', $numDni)
            ->where('s.tipo_documento = ?', $tipodoc)
            ->limit('1');

        return $this->getAdapter()->fetchRow($sql);
    }
    
    public function getCantRendencionByBeneficioAndSuscriptor()
    {
        $sql = $this->select()->from($this->_name, array('cant' => 'count(1)'))
            ->where('suscriptor_id = ?', $this->getSuscriptor_id())
            ->where('beneficio_id = ?', $this->getBeneficio_id())
            ->group('beneficio_id');
        $sql->where('estado = ?', self::ESTADO_REDIMIDO);
        //echo $sql;exit;
        $rs = $this->getAdapter()->fetchRow($sql);
        $data["cupon_consumido"]=empty($rs)?0:$rs["cant"];
        //var_dump($data);exit;
        return $data;
    }
    
    
    public function getDescripcionRedimirCupon($idSus, $idBen, $oneRow=1, $nroCupon='')
    {
        //EL ORDEN ES IMPORTANTE
        $sql = $this->getAdapter()
            ->select()->distinct()
            ->from(array('c'=>$this->_name), array('id','codigo'))
            ->joinInner(
                array('s'=>'suscriptor'), 
                's.id = c.suscriptor_id',
                array(
                    's.nombres',
                    "apellidos" => new Zend_Db_Expr("CONCAT(s.apellido_paterno, ' ', s.apellido_materno)"), 
                    's.numero_documento',
                    's.tipo_documento'
                )
            )
            ->joinInner(
                array('b'=>'beneficio'), 
                'b.id = c.beneficio_id',
                array('b.titulo', 'b.descripcion_corta', 'b.maximo_por_subscriptor')
            )
            ->joinInner(
                array('tb'=>'tipo_beneficio'), 
                'tb.id=b.tipo_beneficio_id',
                array('tb.nombre')
            )->joinLeft(
                array('sb'=>'suscriptor_beneficio'),
                'sb.beneficio_id = b.id AND sb.suscriptor_id =s.id',
                array('sb.cupon_consumido')
            );
        
        if (!empty($nroCupon)) {
            $sql->where('c.codigo = ?', $nroCupon);
        }
        
        $sql->where('s.id = ?', $idSus)
            ->where('b.id = ?', $idBen)
            ->where('c.redimido_por IS NULL')
            ->where('c.estado NOT IN (?)', array(self::ESTADO_CONCILIADO,self::ESTADO_REDIMIDO))
            ->orWhere('c.estado is null')
            ->order('id asc');
        
        if (!empty($oneRow)) {
            $sql->limit('1');
        }
        if (!empty($oneRow)) {
            $rs = $this->getAdapter()->fetchRow($sql);
        } else {
            $rs = $this->getAdapter()->fetchAll($sql);
        }
        //echo $sql->assemble(); exit;
        return $rs;
    }
    
    public function getInfoRedimeBenefSuscrip($idSus, $idBen)
    {
        $merge = array();
        $db = $this->getAdapter();
        $sql = $db->select()
            ->from(
                array('s'=>'suscriptor'),
                array(
                    's.nombres', 
                    "apellidos" => new Zend_Db_Expr("CONCAT(s.apellido_paterno, ' ', s.apellido_materno)"), 
                    's.numero_documento', 
                    's.tipo_documento'
                )
            )->where('s.id = ?', $idSus);
        $rs = $db->fetchRow($sql);
        $merge = array_merge($merge, $rs);
        
        $sql = $db->select()->from(
            array('b'=>'beneficio'),
            array('b.titulo', 'b.descripcion_corta', 'b.maximo_por_subscriptor')
        )
        ->where('b.id = ?', $idBen)
        ->joinInner(
            array('tb'=>'tipo_beneficio'),
            'tb.id=b.tipo_beneficio_id',
            array('tb.nombre')
        );
        $rs = $db->fetchRow($sql);
        $merge = array_merge($merge, $rs);
        
        $sql = $db->select()->from(
            array('sb'=>'suscriptor_beneficio'),
            array('sb.cupon_consumido')
        )->where("sb.beneficio_id = '".$idBen."' AND sb.suscriptor_id = '".$idSus."'");
        $rs = $db->fetchRow($sql);
        $merge = array_merge($merge, (array)$rs);
        
        //var_dump($merge); exit;
        //echo $sql->assemble(); exit;
        return $merge;
    }

    public function getTotalCuponesPorBeneficio()
    {
        $db = $this->getAdapter();
        $sql = $db->select()->from($this->_name, array('cant'=>'count(1)'))
                            ->where('beneficio_id = ?', $this->getId());
        if ( !empty ( $this->_estado ) ) {
            $sql->where('estado = ?', $this->getEstado());
        } else {
            $sql->where("estado != ?","eliminado");
        }
        
        $rs = $db->fetchRow($sql);
        return $rs['cant'];
    }
    public function getCuponDisponible($codigo, $idEstablecimiento, $col="", $ord="")
    {
        $ord = $ord==""?"ASC":$ord;
        $col = $col==""?"c.fecha_fin_vigencia":$col;

        $db = $this->getAdapter();
        $sql = $db->select()
                ->from(
                    array("c"=>$this->_name),
                    array("titulo"=>"b.titulo",
                        "tipobeneficio"=>"tb.nombre",
                        "descripcion" => "b.descripcion_corta",
                        "fechainicio" => "bv.fecha_inicio_vigencia",
                        "fechafin" => "bv.fecha_fin_vigencia",
                        "stock" => "bv.stock_actual",
                        "suscriptor_id" => "c.suscriptor_id",
                        "id_beneficio" => "b.id",
                        "estado" => "s.es_suscriptor",
//                        "estado" => "s.activo",
                        "nombres" => "s.nombres",
                        "apellidos" => new Zend_Db_Expr("CONCAT(s.apellido_paterno, ' ', s.apellido_materno)"),
                        "idEstablecimiento" => "e.id"
                    )
                )
                ->join(
                    array("b"=>"beneficio"),
                    "b.id = c.beneficio_id",
                    array()
                )->where('b.elog = ?', 0)
                ->join(
                    array("e"=>"establecimiento"),
                    "e.id = b.establecimiento_id",
                    array()
                )
                ->join(
                    array("tb"=>"tipo_beneficio"),
                    "tb.id = b.tipo_beneficio_id",
                    array()
                )
                ->join(
                    array('bv'=>'beneficio_version'),
                    'bv.beneficio_id = b.id',
                    array()
                )
                ->join(
                    array('s'=>'suscriptor'),
                    's.id = c.suscriptor_id',
                    array()
                )
                //->where("e.id=?", $idEstablecimiento)
                ->where("c.estado='generado'")
                ->where("CURRENT_DATE() between c.fecha_inicio_vigencia and c.fecha_fin_vigencia")
                ->where("c.codigo=?", $codigo)
                ->where("bv.activo=?", 1)
                ->order($col." ".$ord);
        //echo $sql->assemble(); exit;
        return $db->fetchAll($sql);
    }

    public function getCuponesConciliacion(
        $idEst="", $desde="", $hasta="", $idBen="", $estado="", $voucher="", $ord="", $col=""
    )
    {
        $col = $col==""?"fecha_redencion":$col;
        $ord = $ord==""?"ASC":$ord;

        $sql = $this->getAdapter()->select()->from(
            array("c" => $this->_name),
            array(
                "idbeneficio" => "b.id",
                "idcupon" => "c.id",
                "promocion" => "b.titulo",
                "fechaconsumo" => new Zend_Db_Expr(
                    "date_format(c.fecha_redencion,'%d/%m/%Y %H:%i:%s')"
                ),
                "numerodocumento" => "s.numero_documento",
                "tipodocumento" => "s.tipo_documento",
                "codigo" => "c.codigo",
                "numerocomprobante" => "c.numero_comprobante",
                "descuento" => "c.monto_descontado",
                "nombres" => "s.nombres",
                "apellidos" => new Zend_Db_Expr("CONCAT(s.apellido_paterno, ' ', s.apellido_materno)"),
                "estado" => "c.estado"
            )
        )
        ->join(
            array("s"=>"suscriptor"),
            "c.suscriptor_id = s.id",
            array()
        )
        ->join(
            array("b"=>"beneficio"),
            "c.beneficio_id = b.id",
            array()
        )
        ->where("c.estado='conciliado' OR c.estado='redimido'")
        ->where("b.elog = 0")
        ->order($col." ".$ord);
        
        if ($idEst!="") {
            $sql = $sql->where("b.establecimiento_id = ?", $idEst);
        }
        if ($idBen!="") {
            $sql = $sql->where("c.beneficio_id = ?", $idBen);
        }
        if ($desde!="") {
            $sql = $sql->where("c.fecha_consumo >= ?", $desde);
        }
        if ($hasta!="") {
            $sql = $sql->where("c.fecha_consumo <= ?", $hasta);
        }
        if ($estado==1) { //conciliados
            $sql = $sql->where("c.estado = 'conciliado'");
        }
        if ($estado==2) { //redimidos
            $sql = $sql->where("c.estado = 'redimido'");
        }
        if ($voucher!="") {
            $sql = $sql->where("upper(c.numero_comprobante) = upper(?)", $voucher);
        }
        //echo $sql->assemble(); exit;
        $rs = $this->getAdapter()->fetchAll($sql);
        return $rs;
    }

    public function listarCuponesConciliacion(
        $idEst="", $desde="", $hasta="", $idBen="", $estado="", $voucher="", $ord="", $col=""
    )
    {
        $paginado = $this->_config->gestor->conciliacion->nropaginas;
        $p = Zend_Paginator::factory(
            $this->getCuponesConciliacion(
                $idEst, $desde, $hasta, $idBen, $estado, $voucher, $ord, $col
            )
        );
        return $p->setItemCountPerPage($paginado);
    }
    
    public function getConciliadosPorEstablecimientoYPromo($_establecimientoId)
    {
        $db = $this->getAdapter();
        $sql = $db->select()->from(
            array('cupon' => $this->_name),
            array(
                'fecha_consumo'=>"COALESCE(fecha_consumo,'')",
                'monto_descontado','estado','numero_comprobante'
            )
        )->where("cupon.estado in('".self::ESTADO_CONCILIADO."')")
        ->order('cupon.fecha_emision desc');
        
        $sql->join(
            array('benef'=>'beneficio'), 'benef.id = cupon.beneficio_id',
            array('titulo')
        )->where('benef.elog = 0');
        
        $sql->join(
            array('susc'=>'suscriptor'), 'susc.id = cupon.suscriptor_id',
            array('tipo_documento', 'numero_documento', 
                'names'=>"CONCAT(nombres,' ',apellido_paterno),' ',apellido_materno")
        );
        
        /*$sql->join(
            array('tbenef'=>'tipo_beneficio'), 'tbenef.id = benef.tipo_beneficio_id',
            array('abreviado')
        );*/
        
        if ( !empty( $_establecimientoId ) ):
            $sql->where('benef.establecimiento_id = ?', $_establecimientoId);
        endif;
        if ( !empty( $this->_beneficioId ) ):
            $sql->where('cupon.beneficio_id = ?', $this->getBeneficio_id());
        endif;
        
        if( !empty( $this->_fechaConsumoFrom ) && !empty( $this->_fechaConsumoAt ) ):
            $sql->where(
                "DATE(cupon.fecha_consumo) BETWEEN STR_TO_DATE('".
                $this->getFecha_consumo_from()."','%d/%m/%Y') AND STR_TO_DATE('".
                $this->getFecha_consumo_at()."','%d/%m/%Y')"
            );
        elseif ( !empty( $this->_fechaConsumoFrom ) ):
            $sql->where(
                "DATE_FORMAT(cupon.fecha_consumo,'%d/%m/%Y') >= ?",
                $this->getFecha_consumo_from()
            );
        elseif ( !empty($this->_fechaConsumoAt) ):
            $sql->where(
                "DATE_FORMAT(cupon.fecha_consumo,'%d/%m/%Y') <= ?",
                $this->getFecha_consumo_at()
            );
        endif;
        
        //echo $sql; exit;
        //$rs = $db->fetchAll($sql);
        //return $rs;
        return $sql;
    }
    
    public function getBeneficiosConciliadosPorEstabYFecConsumo($_establecimientoId)
    {
        $db = $this->getAdapter();
        $sql = $db->select()->from(
            array('cupon' => $this->_name),
            array('total'=>new Zend_Db_Expr('sum(monto_descontado)'))
        )->where("cupon.estado in('".self::ESTADO_CONCILIADO."')")
        ->order('benef.titulo asc')
        ->order('cupon.fecha_emision desc');
        
        $sql->join(
            array('benef'=>'beneficio'), 'benef.id = cupon.beneficio_id',
            array('benef.id', 'titulo')
        );
        
        if ( !empty( $_establecimientoId ) ):
            $sql->where('benef.establecimiento_id = ?', $_establecimientoId);
        endif;
        
        if( !empty( $this->_fechaConsumoFrom ) && !empty( $this->_fechaConsumoAt ) ):
            $sql->where(
                "DATE(cupon.fecha_consumo) BETWEEN STR_TO_DATE('".
                $this->getFecha_consumo_from()."','%d/%m/%Y') AND STR_TO_DATE('".
                $this->getFecha_consumo_at()."','%d/%m/%Y')"
            );
        elseif ( !empty( $this->_fechaConsumoFrom ) ):
            $sql->where(
                "DATE_FORMAT(cupon.fecha_consumo,'%d/%m/%Y') >= ?",
                $this->getFecha_consumo_from()
            );
        elseif ( !empty($this->_fechaConsumoAt) ):
            $sql->where(
                "DATE_FORMAT(cupon.fecha_consumo,'%d/%m/%Y') <= ?",
                $this->getFecha_consumo_at()
            );
        endif;
        $sql->group('cupon.beneficio_id');
        //echo $sql; exit;
        $rs = $db->fetchAll($sql);
        return $rs;
    }
    
    public static function getCuponByCodigo($codigo)
    {
        $obj = new Application_Model_Cupon();
        $db = $obj->getAdapter();
        $sql = $db->select()
            ->from(array('c' => $obj->_name))
            ->where('c.codigo = ?', $codigo)
            ->limit(1);
        return $db->fetchRow($sql);
    }
    
    public static function getCuponesGeneradosBySuscriptorAndBeneficio($suscriptorId, $beneficioId)
    {
        $obj = new Application_Model_Cupon();
        $db = $obj->getAdapter();
        $sql = $db->select()
            ->from(array('c' => $obj->_name))
            ->where("c.estado = ?", self::ESTADO_GENERADO)
            ->where('c.suscriptor_id = ?', $suscriptorId)
            ->where('c.beneficio_id = ?', $beneficioId);
        return $db->fetchAll($sql);
    }

    public function getCuponxIdEditar($idCupon)
    {
        $sql = $this->getAdapter()->select()->from(
            array("c" => $this->_name),
            array(
                "idbeneficio" => "b.id",
                "idcupon" => "c.id",
                "promocion" => "b.titulo",
                "nombreestablecimiento" => "e.nombre",
                "fechaconsumo" => new Zend_Db_Expr("date_format(c.fecha_redencion,'%d/%m/%Y')"),
                "numerodocumento" => "s.numero_documento",
                "tipodocumento" => "s.tipo_documento",
                "codigo" => "c.codigo",
                "numerocomprobante" => "c.numero_comprobante",
                "descuento" => "c.monto_descontado",
                "nombres" => "s.nombres",
                "apellidos" => new Zend_Db_Expr("CONCAT(s.apellido_paterno, ' ', s.apellido_materno)"),
                "estado" => "c.estado",
                "comentario" => "c.comentario"
            )
        )
        ->join(
            array("s"=>"suscriptor"),
            "c.suscriptor_id = s.id",
            array()
        )
        ->join(
            array("b"=>"beneficio"),
            "c.beneficio_id = b.id",
            array()
        )
        ->join(
            array("e"=>"establecimiento"),
            "e.id = b.establecimiento_id",
            array()
        )
        ->where("c.id = ?", $idCupon);
        return $this->getAdapter()->fetchRow($sql);
    }
}
