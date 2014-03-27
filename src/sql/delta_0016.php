<?php

/**
 * Description of delta_0016
 *
 * @author JFlorian
 */
class Delta_0016 extends App_Migration_Delta
{
    protected $_autor = 'Julio Florian';
    protected $_desc = 'Aumento de tamaño al campo path_imagen';
    
    public function up()
    {
        $sql = "ALTER TABLE establecimiento CHANGE path_imagen path_imagen VARCHAR( 100 ) 
        	CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Ubicación fisica de la imagen en el disco duro'";
        $this->_db->query($sql);
        return true;
    }
}
