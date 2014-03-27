<?php

class Delta_0007 extends App_Migration_Delta
{
    protected $_autor = 'Julio Florian';
    protected $_desc = 'Agregación del campo imagen para los establecimientos';
    
    public function up()
    {
        $sql = "ALTER TABLE establecimiento ADD path_imagen VARCHAR( 45 ) 
            CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 
            'Ubicación fisica de la imagen en el disco duro' AFTER fecha_actualizacion ;";
        $this->_db->query($sql);
        return true;
    }
}
