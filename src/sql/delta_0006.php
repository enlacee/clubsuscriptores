<?php

class Delta_0006 extends App_Migration_Delta
{
    protected $_autor = 'Solman Vaisman';
    protected $_desc = 'cupones consumidos para la tabla beneficios';
    
    public function up()
    {
        $sql = "ALTER TABLE beneficio
                ADD COLUMN `ncuponesconsumidos` INT(8) NULL AFTER `slug`;";
        $this->_db->query($sql);
        return true;
    }
}
