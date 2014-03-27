<?php

/**
 * Description of delta_0017
 *
 * @author FCJ
 */
class Delta_0021 extends App_Migration_Delta
{
    protected $_autor = 'Favio Condori';
    protected $_desc = 'Agregar campos de informacion de fechas de estado de publicacion de beneficio';
    
    public function up()
    {
//        $sql = "UPDATE `beneficio` SET generar_cupon = 1";
//        $this->_db->query($sql);
        return true;
    }
}
