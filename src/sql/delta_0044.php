<?php

/**
 * Description of delta_0044
 *
 * @author DjTabo
 */
class Delta_0044 extends App_Migration_Delta
{
    protected $_autor = 'Paul Taboada';
    protected $_desc = 'Delta para actualizar valores en tabla tipo_beneficio';

    public function up()
    {
        $sql = "UPDATE tipo_beneficio SET nombre='Establecimientos',
            abreviado='Establec.',slug='establecimiento' WHERE id='2';";
        $this->_db->query($sql);
        
        $sql = "UPDATE tipo_beneficio SET nombre='Entradas',
            abreviado='Entrada',slug='entrada' WHERE id='4';";
        $this->_db->query($sql);
        
        $sql = "UPDATE tipo_beneficio SET nombre='Viajes',
            abreviado='Viaje',slug='viaje' WHERE id='1';";
        $this->_db->query($sql);
        return true;
    }

}
