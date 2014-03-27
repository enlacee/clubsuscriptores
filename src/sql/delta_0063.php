<?php

/**
 * Description of delta_0063
 *
 * @author FCJ
 */
class Delta_0063 extends App_Migration_Delta
{
    protected $_autor = 'Favio Condori';
    protected $_desc = 'Agregar Campo para nombre de archivo PDF para resultados de sorteo';
    
    public function up()
    {
        $sql = "ALTER TABLE beneficio ADD pdf_file VARCHAR(100) NULL DEFAULT NULL";
        $this->_db->query($sql);
        
        return true;
    }
}