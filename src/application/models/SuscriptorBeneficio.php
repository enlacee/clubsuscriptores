<?php

class Application_Model_SuscriptorBeneficio extends App_Db_Table_Abstract
{

    protected $_name = 'suscriptor_beneficio';
    
    public function actualizarCuponRedimido($idSus, $idBen)
    {
        $sql = $this->getAdapter()
            ->select()
            ->from(
                $this->_name, 
                array(
                    'id',
                    'cupon_generado'=>new Zend_Db_Expr(
                        '(SELECT COUNT(1)
                        FROM cupon
                        WHERE estado="generado" and suscriptor_id='.$idSus.' and beneficio_id='.$idBen.')'
                    ),
                    'cupon_consumido'=>new Zend_Db_Expr(
                        '(SELECT COUNT(1)
                        FROM cupon
                        WHERE estado="redimido" and suscriptor_id='.$idSus.' and beneficio_id='.$idBen.')'
                    ),
                )
            )
            ->where('suscriptor_id = ?', $idSus)
            ->where('beneficio_id = ?', $idBen)
            ->limit('1');
        //echo $sql;exit;
        return $this->getAdapter()->fetchRow($sql);
    }
    
    public function getCuponesXGenerar($idSus, $idBen)
    {
        $sql = $this->getAdapter()
            ->select()
            ->from(
                array('sb'=>$this->_name), 
                array('sb.id', 'sb.cupon_consumido', 'sb.cupon_generado')
            )
            ->joinInner(
                array('b'=>'beneficio'),
                $this->getAdapter()->quoteInto('b.id = sb.beneficio_id AND b.id = ?', $idBen),
                array('b.maximo_por_subscriptor', 'b.sin_limite_por_suscriptor', 'b.sin_stock')
            )
            ->joinInner(
                array('s'=>'suscriptor'),
                $this->getAdapter()->quoteInto('s.id = sb.suscriptor_id AND s.id = ?', $idSus),
                array()
            )
            ->joinInner(
                array('bv'=>'beneficio_version'),
                'bv.beneficio_id = b.id',
                array("stock_actual")
            )
            ->where("bv.activo=1");
//        echo $sql->assemble();exit;
        return $this->getAdapter()->fetchRow($sql);
    }
    
    public static function getInfoStockBeneficio($idBen)
    {
        $obj = new Application_Model_SuscriptorBeneficio();
        $sql = $obj->getAdapter()
            ->select()
            ->from(
                array('b'=>'beneficio'),
                array('b.maximo_por_subscriptor', 'b.sin_limite_por_suscriptor', 'b.sin_stock')
            )
            ->joinInner(
                array('bv'=>'beneficio_version'),
                'bv.beneficio_id = b.id AND bv.activo = 1',
                array("stock_actual")
            )
            ->where("b.id = ?", $idBen);
        return $obj->getAdapter()->fetchRow($sql);
    }
    
    public static function getInfoStockSuscriptor($idSus, $idBen)
    {
        $obj = new Application_Model_SuscriptorBeneficio();
        $sql = $obj->getAdapter()
            ->select()
            ->from(
                array('s'=>'suscriptor')
            )
            ->joinInner(
                array('c'=>'cupon'),
                $obj->getAdapter()->quoteInto('s.id = c.suscriptor_id AND c.beneficio_id = ?', $idBen),
                array('c_id' => 'c.id', 'c_codigo' => 'c.codigo', 'c_estado' => 'c.estado')
            )
            ->where("s.id = ?", $idSus);
        return $obj->getAdapter()->fetchAll($sql);
    }
    
    public function getCuponesGenerado($beneficioId,$suscriptorId)
    {
        $sql = $this->select()->from($this->_name, array('cupon_generado','id'))->where(
            "beneficio_id = '".$beneficioId."' AND ".
            "suscriptor_id = '".$suscriptorId."'"
        );
        //echo $sql;exit;
        $rs = $this->getAdapter()->fetchRow($sql);
        //var_dump($rs); exit;
        return (!empty($rs['id'])?$rs['cupon_generado']:-1);
    }
}
