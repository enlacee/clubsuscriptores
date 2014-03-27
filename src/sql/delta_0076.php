<?php

class Delta_0076 extends App_Migration_Delta
{
    protected $_autor = 'Anderson Poccorpachi';
    protected $_desc = 'Agregar nuevo campo comentario para la rendencion de un cupon';
    
    public function up()
    {
        $sql = "ALTER TABLE `cupon` ADD `comentario_redencion` VARCHAR( 200 ) NULL;";
        $this->_db->query($sql);
        return true;
    }
}
