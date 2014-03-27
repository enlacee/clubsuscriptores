<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of DetalleTipoPromocion
 *
 * @author phpauldj
 */
class Application_Model_DetalleTipoPromocion extends App_Db_Table_Abstract
{
    protected $_name = 'detalle_tipo_promocion';
    protected $_id;
    protected $_beneficioId;
    
    public function setId($_id)
    {
        $this->_id = $_id;
    }
    
    public function getId()
    {
        return $this->_id;
    }
    
    public function setBeneficio_id($_beneficioId)
    {
        $this->_beneficioId=$_beneficioId; 
    }
    
    public function getBeneficio_id()
    {
        return $this->_beneficioId;
    }
    
    public function getTiposPromocionCmbByBenef($opc=0)
    {
        $tipo=new Zend_Db_Expr("dtp.codigo");
        if ($opc==1) {
            $tipo=new Zend_Db_Expr("CONCAT(IFNULL(dtp.codigo,''),' ',IFNULL(dtp.porcentaje_descuento,''),'%') ");
        } elseif ($opc=='descrip') {
            $tipo=new Zend_Db_Expr("descripcion ");
        }
        $sql = $this->select()->from(
            array('dtp' => $this->_name), 
            array(
                'id' => new Zend_Db_Expr("concat(dtp.id,'-',dtp.descuento,'-',
                    IFNULL(dtp.precio_regular,0),'-',IFNULL(dtp.precio_suscriptor,0),'-',
                    IFNULL(dtp.porcentaje_descuento,0))"), 
                'tipo' => $tipo
            )
        ) 
        ->where('dtp.beneficio_id = ?', $this->getBeneficio_id())
        ->where('dtp.activo = 1');
        //echo $sql; exit;
        $rs = $this->getAdapter()->fetchPairs($sql);
        return !empty($rs)?$rs:0;
    }
    
    public function getTiposPromocionByBenef($beneficioId, $cantBenGenerados)
    {
        $data = array();
        $sql = $this->select()->from(
            array('dtp' => $this->_name), array(
            'id' => new Zend_Db_Expr("concat(dtp.id,'-',dtp.descuento)"),
            'tipo' => new Zend_Db_Expr("concat(dtp.descuento,' (',dtp.codigo,')')")                
            )
        )->where('dtp.beneficio_id = ?', $beneficioId)
//        ->where('dtp.stock_actual > 0')
        ->where('dtp.activo = 1')
        ->join(
            array('ben' => 'beneficio'), 'dtp.beneficio_id = ben.id', false
        )
        ->join(
            array('benVer'=>'beneficio_version'),
            'benVer.beneficio_id = ben.id',
            false
        )
//        ->joinleft(
//                array('tipMon'=>'tipo_moneda'),
//                'tipMon.id = dtp.tipo_moneda_id',
//                false
//            )
        ->where('ben.elog = ?', 0)->where('benVer.activo = ?', 1)->where('ben.activo = ?', 1)
        ->where("CURRENT_DATE() BETWEEN benVer.fecha_inicio_vigencia AND benVer.fecha_fin_vigencia")
        ->where('(benVer.stock > 0')
        ->orWhere('ben.sin_stock = 1)')
        ->where('(ben.maximo_por_subscriptor > 0')
        ->orWhere('ben.sin_limite_por_suscriptor = 1)');
        //echo $sql; exit;
        $rs = $this->getAdapter()->fetchPairs($sql);
        $data['tipos'] = $rs;

        $sql = $this->select()->from(
            array('dtp' => $this->_name), array('id' => new Zend_Db_Expr("concat(id,'-',descuento)"), 'descripcion')
        )->where('dtp.beneficio_id = ?', $beneficioId)
//        ->where('dtp.stock_actual > 0')
        ->where('dtp.activo = 1');
        $rs = $this->getAdapter()->fetchPairs($sql);
        $data['descrip'] = $rs;

        /* $sql = $this->select()->from(
          array('dtp' => $this->_name), array('id')
          )->where('dtp.beneficio_id = ?', $beneficioId);
          //->where('dtp.stock_actual = 0');
          $rs = $this->getAdapter()->fetchRow($sql);
          $data['con_stock'] = empty($rs); */
        
        $sql = $this->_db->select()->from(
            array('dtp' => $this->_name), array(
            'id' => new Zend_Db_Expr("concat(dtp.id,'-',dtp.descuento)"),
            'dtp.codigo', 'dtp.descuento', 'stock_actual' => new Zend_Db_Expr("(benVer.stock - $cantBenGenerados)")
            //,'stock_actual'
            )
        )->where('dtp.beneficio_id = ?', $beneficioId)
//        ->where('dtp.stock_actual > 0')
        ->where('dtp.activo = 1')
        ->join(
            array('ben' => 'beneficio'), 'dtp.beneficio_id = ben.id', array( 'ben.sin_stock')
        )
        ->join(
            array('benVer' => 'beneficio_version'), 'benVer.beneficio_id = ben.id', false
        )
        ->where('ben.elog = ?', 0)->where('ben.activo = ?', 1)
        ->where('benVer.activo = ?', 1)
        ->where("CURRENT_DATE() BETWEEN benVer.fecha_inicio_vigencia AND benVer.fecha_fin_vigencia")
        ->where('(benVer.stock > 0')
        ->orWhere('ben.sin_stock = 1)')
        ->where('(ben.maximo_por_subscriptor > ?', $cantBenGenerados)
        ->orWhere('ben.sin_limite_por_suscriptor = 1)');       
        $rs = $this->getAdapter()->fetchAssoc($sql);
        $data['tiposData'] = $rs;
        
//        $sql = $this->_db->select()->from(
//            array('ben' => "beneficio"), 
//            array(
//                'id','maximo_por_subscriptor','sin_limite_por_suscriptor','sin_stock'
//            )
//        )
//        ->join(
//            array('benVer' => 'beneficio_version'), 'benVer.beneficio_id = ben.id',
//            array('benVer.stock','stock_actual')
//        )
//        ->where('ben.id= ?', $beneficioId)
//        ->where('ben.elog = ?', 0)->where('ben.activo = ?', 1)
//        ->where('benVer.activo = ?', 1)
//        ->where("CURRENT_DATE() BETWEEN benVer.fecha_inicio_vigencia AND benVer.fecha_fin_vigencia")
//        ->where('(benVer.stock > 0')
//        ->orWhere('ben.sin_stock = 1)')
//        ->where('(ben.maximo_por_subscriptor > 0')
//        ->orWhere('ben.sin_limite_por_suscriptor = 1)');
//        //echo $sql;exit;
//        $rs = $this->getAdapter()->fetchRow($sql);
//        $data['dataValidar'] = $rs;
        
        return $data;
    }

    public function disabledActivosEsceptoTipodescuentoEnCuponGenerados($id)
    {
        $where[1] = $this->getAdapter()->quoteInto('beneficio_id = ?', $id);
        $where[2] = " id not in ( select distinct IFNULL(detalle_tipo_promocion_id,0) from cupon where " 
            . $where[1] . ") ";
        $this->update(array('activo' => 0), $where);
    }

    public function setDisminuyeStock($id, $disminuye = 1)
    {
        $sql = $this->select()->from($this->_name, array('stock_actual'))
            ->where('id = ?', $id);
        $rs = $this->getAdapter()->fetchRow($sql);
        $update['stock_actual'] = $rs['stock_actual'] - $disminuye;
        $this->update($update, "id = '" . $id . "'");
    }

    public static function getDetalleTipoPromocionByBeneficioId($id)
    {
        $obj = new Application_Model_DetalleTipoPromocion();
        $db = $obj->getAdapter();
        $sql = $db->select()
            ->from(array('dtp' => $obj->_name))
            ->where('beneficio_id = ?', $id)
            ->where('activo = 1');
        return $db->fetchAll($sql);
    }

    public static function getDetalleTipoPromocionByBeneficioIdCuponGenerado($id)
    {
        $obj = new Application_Model_DetalleTipoPromocion();
        $db = $obj->getAdapter();
        $sql = $db->select()
            ->from(
                array('dtp' => $obj->_name), 
                array(
                    'cupon' => new Zend_Db_Expr(
                        "CASE WHEN id != '0' THEN (select count(*) from cupon where "
                        . "beneficio_id='$id' and detalle_tipo_promocion_id=dtp.id) ELSE '0' END"
                    ), 
                    '*'
                )
            )
            ->where('beneficio_id = ?', $id)
            ->where('activo = 1');
//        echo $sql->assemble(); exit;
        return $db->fetchAll($sql);
    }

    public function getExisteDetalleTipPromo($beneficioId)
    {
        $sql = $this->select()->from(
            array('dtp' => $this->_name), array('id')
        )->where('dtp.beneficio_id = ?', $beneficioId)
        ->where('dtp.activo = 1');
        $rs = $this->getAdapter()->fetchAll($sql);
        return !empty($rs);
    }

    public function getStockCalConsumidoByTipo($beneficioId, $codigo)
    {
        $sql = $this->select()->from(
            array('dtp' => $this->_name), array("totstock" => "sum(cantidad - stock_actual)")
        )
        ->where('dtp.beneficio_id = ?', $beneficioId)
        ->where('dtp.codigo = ?', $codigo)
        ->where('dtp.activo = 0');
        //echo $sql; exit;
        $rs = $this->getAdapter()->fetchRow($sql);
        return !empty($rs['totstock']) ? $rs['totstock'] : 0;
    }
    
    public static function getDataById($idDetTipPro)
    {
        $obj = new Application_Model_DetalleTipoPromocion();
        $db = $obj->getAdapter();
        $sql = $db->select()->from(
            array('dtp' => $obj->_name), 
            array("maximo_por_suscriptor","cantidad","stock_actual","codigo")
        )
        ->where('dtp.id = ?', $idDetTipPro)
        ->where('dtp.activo = 1');
//        echo $sql; exit;
        $rs = $db->fetchRow($sql);
        return !empty($rs) ? $rs : 0;
    }

    public function newVersDetailTip($data)
    {
        $where = "beneficio_id = '" . $data['beneficio_id'] . "' AND " .
            "codigo = '" . $data['codigo'] . "'";
        $this->update(
            array('activo' => 0), $where
        );
        $sql = $this->select()->from(
            array($this->_name), array('descuento', 'descripcion')
        )
        ->where($where);

        $rs = $this->getAdapter()->fetchRow($sql);
        $data['activo'] = 1;
        $data['descripcion'] = $rs['descripcion'];
        $data['descuento'] = $rs['descuento'];
        $this->insert($data);
    }

}