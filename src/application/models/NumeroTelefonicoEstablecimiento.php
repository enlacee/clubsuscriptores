<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of Gestor
 *
 * @author Computer
 */
class Application_Model_NumeroTelefonicoEstablecimiento extends App_Db_Table_Abstract
{
    protected $_name = 'numero_telefonico_establecimiento';
    
    protected $_establecimientoId;
    
    public function getEstablecimiento_id()
    {
        return $this->_establecimientoId;
    }

    public function setEstablecimiento_id($_establecimientoId)
    {
        $this->_establecimientoId = $_establecimientoId;
    }
    
    public function getPhoneNumbersxEstablecimiento()
    {
        $sql = $this->select()->from($this->_name)
                    ->where('establecimiento_id = ?', $this->getEstablecimiento_id());
        return $this->getAdapter()->fetchAll($sql);
    }
    
    public static function getOperadores()
    {
        $objTable = new Application_Model_NumeroTelefonicoEstablecimiento();
        $cadenavalor = $objTable->getAdapter()
            ->query('SHOW COLUMNS FROM '.$objTable->_name." LIKE 'operador'")->fetchColumn(1);
        //var_dump($cadenavalor); //exit;
        $cadenavalor = str_replace("enum('", '', $cadenavalor);
        $cadenavalor = str_replace("'", '', $cadenavalor);
        $cadenavalor = str_replace(")", '', $cadenavalor);
        $valores = explode(',', $cadenavalor); $array = array();
        foreach ($valores as $item) $array[$item] = $item;
        //print_r($valores); exit;
        return $array;
    }
    
    public function getRowByColumnTable($campo = 'numero_telefonico', $valor = '')
    {
        $sql = $this->select()->from($this->_name)
                              ->where($campo.' = ?', $valor);
        return $this->getAdapter()->fetchRow($sql);
    }
}
