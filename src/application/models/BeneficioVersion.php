<?php

class Application_Model_BeneficioVersion extends App_Db_Table_Abstract
{

    protected $_name = 'beneficio_version';
    protected $_beneficioId;
    protected $_stock;
    protected $_stockActual;
    protected $_fechaInicioVigencia;
    protected $_fechaFinVigencia;
    protected $_fechaRegistro;
    protected $_activo;

    public function __construct($id = '')
    {
        parent::__construct();

        if (!empty($id)):
            $sql = $this->select()->from($this->_name)
                ->where('beneficio_id = ?', $id)
                ->where('activo = ?', 1);
            $rs = $this->getAdapter()->fetchRow($sql);
            $this->_beneficioId = $rs['beneficio_id'];
            $this->_stock = $rs['stock'];
            $this->_stockActual = $rs['stock_actual'];
            $this->_fechaInicioVigencia = $rs['fecha_inicio_vigencia'];
            $this->_fechaFinVigencia = $rs['fecha_fin_vigencia'];
            $this->_fechaRegistro = $rs['fecha_registro'];
            $this->_activo = $rs['activo'];
        endif;
    }

    public function getFecha_fin_vigencia()
    {
        return $this->_fechaFinVigencia;
    }

    public function setFecha_fin_vigencia($_fechaFinVigencia)
    {
        $this->_fechaFinVigencia = $_fechaFinVigencia;
    }
    
    public function getFecha_inicio_vigencia()
    {
        return $this->_fechaInicioVigencia;
    }

    public function setFecha_inicio_vigencia($_fechaInicioVigencia)
    {
        $this->_fechaInicioVigencia = $_fechaInicioVigencia;
    }

    public function getBeneficio_id()
    {
        return $this->_beneficioId;
    }

    public function setBeneficio_id($_beneficioId)
    {
        $this->_beneficioId = $_beneficioId;
    }

    public function setDisminuyeStockActual($reducir)
    {
        $db = $this->getAdapter();
        /*$sql = $db->select()->from(array('benef' => 'beneficio'), array())
            ->where('benef.id = ?', $this->getBeneficio_id())
            ->where('benefv.activo = ?', 1);
        $sql->join(
            array('benefv' => $this->_name), 'benef.id = benefv.beneficio_id', 
            array('stock_actual', 'stock', 'id')
        );*/
        
        //calculando el nro de cupones generados por el beneficio y la version del beneficio actual
        $sql = $db->select()->from(
            array('cp' => 'cupon'),
            array('cant' => new Zend_Db_Expr('COUNT(1)'))
        )
        ->where('cp.beneficio_id = ?', $this->getBeneficio_id())
        ->where('cp.estado != ?', 'eliminado');
//        ->where('cp.fecha_emision >= bv.fecha_registro')    
        $sql->join(
            array('bv' => $this->_name), 'bv.beneficio_id = cp.beneficio_id', 
            array('stock_actual', 'stock', 'id')
        )
        ->where('bv.activo = ?', 1);
        
        $rs = $db->fetchRow($sql);
        
        //var_dump($rs); exit;
        $valuestock['stock_actual'] = ($rs['stock'] - $rs['cant']);//($rs['stock_actual'] - $reducir);
        $valuestock['stock_actual'] = 
            ($valuestock['stock_actual'] < 0 ? 0 : $valuestock['stock_actual']);
        //var_dump($valuestock); exit;
        $where = $this->getAdapter()->quoteInto('id = ?', $rs['id']);
        $this->update($valuestock, $where);
        
        /*actualizando los indices*/
        /*$zl = new ZendLucene();
        $zl->updateIndexBeneficio($this->getBeneficio_id(), array('stockactual'=>$valuestock));*/
    }

    public function getBeneficioVersionVencidos()
    {
        $db = $this->getAdapter();
        $sql = $db->select()->from(
            array('bv' => $this->_name),
            array("id"=>"bv.beneficio_id")
        )
        ->join(
            array("b"=>"beneficio"),
            "b.id = bv.beneficio_id",
            array()
        )
        ->where("b.activo=1")
        ->where("bv.activo=1")
        ->where("bv.fecha_fin_publicacion < ?", new Zend_Db_Expr("CURRENT_DATE()"))
        ->order("b.id DESC");
        return $db->fetchAll($sql);
    }

    public function getDevolverStockCalConsumido($idBeneficio)
    {
        $db = $this->getAdapter();
        $sql = $db->select()->from(
            array('bv' => $this->_name),
            array("totstock"=>"sum(stock - stock_actual)")
        )
        //->where("bv.activo=0")
        ->where("bv.beneficio_id = ?", $idBeneficio);
        //echo $sql; exit;
        $rs = $db->fetchRow($sql);
        return !empty($rs['totstock'])?$rs['totstock']:0;
    }
    
    public function getDevolverStockActualAndCuponConsumido()
    {
        $db = $this->getAdapter();
        $sql = $db->select()->from(
            array('bv' => $this->_name),
            array("stock"=>"stock")
        )
        ->where("bv.activo=1")
        ->where("bv.beneficio_id = ?", $this->_beneficioId);
        $rs = $db->fetchRow($sql);
        return array($rs['stock']);
    }
    
}
