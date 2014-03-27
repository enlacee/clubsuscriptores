<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Tipo Establecimiento
 *
 * @author Usuario
 */
class Application_Model_TipoEstablecimiento extends App_Db_Table_Abstract
{

    //put your code here
    protected $_name = 'tipo_establecimiento';
    protected $_model = 'tipoestablecimiento';

    public function getAllTipoEstablecimiento($cache = false)
    {
        
        $cacheEt = $this->_config->cache->{$this->_model}->{__FUNCTION__};
        $cacheId = $this->_model.'_'.__FUNCTION__;
        if ($this->_cache->test($cacheId) && $cache) {
            return $this->_cache->load($cacheId);
        }
        $db = $this->getAdapter();
        $sql = $db->select()
            ->from(
                array(
                    $this->_name,
                    array('id', 'nombre')
                )
            )->order('nombre ASC');
            
        $result = $this->getAdapter()->fetchPairs($sql);
        $this->_cache->save($result, $cacheId, array(), $cacheEt);
        return $result;
        
    }
}