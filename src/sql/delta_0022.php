<?php

/**
 * Description of delta_0017
 *
 * @author FCJ
 */
class Delta_0022 extends App_Migration_Delta
{
    protected $_autor = 'Favio Condori';
    protected $_desc = 'Agregar campos de informacion de fechas de estado de publicacion de beneficio';
    
    public function up()
    {
        $sql = "ALTER TABLE `beneficio` CHANGE `es_destacado` `es_destacado` TINYINT( 4 ) NOT NULL DEFAULT '0',
            CHANGE `es_destacado_principal` `es_destacado_principal` TINYINT( 4 ) NOT NULL DEFAULT '0',
            CHANGE `activo` `activo` TINYINT( 4 ) NOT NULL DEFAULT '0'";
        $this->_db->query($sql);
        return true;
    }
}
