<?php

class Application_Model_CategoriaBeneficio extends App_Db_Table_Abstract
{
    protected $_name = 'categoria_beneficio';
    
    public function getCadsCategoriasPorBeneficio($beneficioId)
    {
        $db = $this->getAdapter();
        $sql = $db->select()
            ->from(array('catbenef'=> $this->_name), array())
            ->order('categoria_id asc')
            ->where('beneficio_id = ?', $beneficioId);
        $sql->join(
            array('cat'=>'categoria'),
            'catbenef.categoria_id = cat.id',
            array('nombre')
        );
        //echo $sql; exit;
        $rs = $db->fetchAll($sql);
        $strcats = '';
        foreach ($rs as $row) {
            $strcats .= $row['nombre'].',';
        }
        $strcats = substr($strcats, 0, -1);
        return str_replace(',', " - ", $strcats);
    }
    
    public function validCategoriaSorteoByBenef($beneficioId, $idSorteoResult)
    {
        $db = $this->getAdapter();
        $sql = $db->select()
            ->from(array('catbenef'=> $this->_name), array('categoria_id'))
            ->where('beneficio_id = ?', $beneficioId)
            ->where('categoria_id = ?', $idSorteoResult);
        
        $rs = $db->fetchRow($sql);
        return !empty($rs);
    }
}
