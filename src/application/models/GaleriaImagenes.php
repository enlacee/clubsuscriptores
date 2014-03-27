<?php

class Application_Model_GaleriaImagenes extends App_Db_Table_Abstract
{

    protected $_name = 'galeria_imagenes';

    public function getImagenesArticulo($articuloId)
    {
        $sql = $this->select()->from($this->_name)
            ->where('articulo_id = ?', $articuloId)
            ->order('orden');
        //echo $sql->assemble(); exit;
        $rs = $this->getAdapter()->fetchAll($sql);
        return $rs;
    }
    
    public function getImagenPrincipal($articuloId)
    {
        $sql = $this->select()
                    ->from($this->_name)
                    ->where('articulo_id = ?', $articuloId)
                    ->where('es_principal = ?', 1)
                    ->limit(1, 0);
        $rs = $this->getAdapter()->fetchRow($sql);
        return $rs;
    }
    
    /*para SEO Ticket 10525 URLIMG migracion*/
    public static function getImagenByPath($path_logo)
    {
        $obj = new Application_Model_GaleriaImagenes();
        $db = $obj->getAdapter();
        $sql = $db->select()
                  ->from(array("i" => $obj->_name),array("i_id"=>"id","orden"))
                  ->where("i.path_imagen_bkp = ?", $path_logo);
        $sql->join(
            array('a'=>'articulo'), 'a.id = i.articulo_id', 
            array('a_id'=>'id','titulo')
        );
//        echo $sql->assemble();exit;
        return $db->fetchRow($sql);
    }
}
