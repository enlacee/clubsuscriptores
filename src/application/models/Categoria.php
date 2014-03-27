<?php

class Application_Model_Categoria extends App_Db_Table_Abstract
{
    protected $_name = 'categoria';

    /**
     * Devuelve un arreglo de categorias segun los parametros de $criteria y seleccion de $ids
     * @param type $getPairs Obtener valores id => nombre
     * @param type $criteria Array con valores de la columna
     * @param type $in Obtener incluidos o no incluidos en $ids
     * @param type $ids Ids a incluir/excluir
     * @return type record set de categorias
     */
    public static function getCategorias($getPairs = false, $criteria = array(), $in = false,
                                         $ids = array())
    {
        $obj = new Application_Model_Categoria();
        $db = $obj->getAdapter();
        $sql = $db->select()
            ->from($obj->_name, array());
        if(isset($criteria['activo']))
            $sql = $sql->where('activo = ?', $criteria['activo']);
        if (count($ids) > 0)
            $sql = $sql->where($in?'id IN (?)':'id NOT IN (?)', $ids);
        if ($getPairs) {
            $sql = $sql->columns(array('id', 'nombre'))
                    ->order('nombre asc');
            $rs = $db->fetchPairs($sql);
        } else {
            $sql = $sql->columns()
                    ->order('nombre asc');
            $rs = $db->fetchAll($sql);
        }
        return $rs;
    }
    
    public static function getCategoriasBeneficioVigente($getPairs = false)
    {
        $obj = new Application_Model_Categoria();
        $db = $obj->getAdapter();
        $sql = $db->select()
            ->distinct()
            ->from(array('cat' => $obj->_name), array('slug', 'nombre'))
            ->join(array('catBen' => 'categoria_beneficio'), 'catBen.categoria_id=cat.id', false)
            ->join(array('ben' => 'beneficio'), 'ben.id=catBen.beneficio_id', false)
            ->join(array('benVer' => 'beneficio_version'), 'benVer.beneficio_id=ben.id', false)
            ->where('ben.activo = ?', 1)
            ->where('ben.publicado = ?', 1)
            ->where('ben.elog = ?', 0)
            ->where(
                'CURRENT_DATE() BETWEEN benVer.fecha_inicio_publicacion AND benVer.fecha_fin_publicacion'
            )
            ->where('benVer.activo = ?', 1)
            ->order('nombre asc');
//        echo $sql->assemble(); exit;
        $rs = $db->fetchPairs($sql);
        return $rs;
    }
    
    public function getCategoriaSinGeneral()
    {
        $sql = $this->select()
            ->from($this->_name, array('value' => 'nombre', 'nombre'))
            ->where('activo = ?', 1)
            ->where('id <> ?', 0)
            ->order('id asc');
        return $this->getAdapter()->fetchPairs($sql);
    }
    
    public function getCategoriaPaginator($criteria = array())
    {
        $nropag = 5;
        $paginator = Zend_Paginator::factory(
            Application_Model_Categoria::getlistarCategoria($criteria)
        );
        return $paginator->setItemCountPerPage($nropag);
    }
    
    public static function getlistarCategoria($criteria)
    {
        $col = $criteria['col'] == '' ? 'c.nombre' : $criteria['col'];
        $ord = $criteria['ord'] == '' ? 'DESC' : $criteria['ord'];
        
        $obj = new Application_Model_Categoria();
        $sql = $obj->getAdapter()
            ->select()
            ->from(array('c' => 'categoria'), array('c.id','c.descripcion','c.nombre','c.activo'))
            ->joinLeft(
                array('cb'=>'categoria_beneficio'), 
                'cb.categoria_id = c.id', 
                array('num_beneficio'=>'count(cb.categoria_id)')
            );
        if (isset($criteria['estado'])) {
            $sql = $sql->where('c.activo = ?', $criteria['estado']);
        }
        if (isset($criteria['nombre'])) {
            $sql = $sql->where("c.nombre LIKE ?", '%'.$criteria['nombre'].'%');
        }
        $sql = $sql->group('c.id');
        $sql = $sql->order(sprintf('%s %s', $col, $ord));
        return $sql;
    }
    
    public function getCategoriaXiD($idCat)
    {
        $sql = $this->getAdapter()
            ->select()
            ->from(array('c'=>$this->_name), array('c.id','c.nombre', 'c.descripcion', 'c.activo'))
            ->where('c.id in ( ? )', $idCat);
            
        return $sql;
    }
    
    public function validarNombre($nomCat, $idCat)
    {
        
        $sql = $this->getAdapter()->select()
            ->from($this->_name)
            ->where('UPPER(REPLACE(nombre," ","")) = ?', $nomCat)
            ->limit('1');
            if ($idCat != null) {
                $sql = $sql->where('id != ?', $idCat);
            }
        return $this->getAdapter()->fetchOne($sql);
    }
}
