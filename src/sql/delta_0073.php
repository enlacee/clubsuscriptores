<?php

/**
 * Description of Delta_0072
 *
 * @author Anderson
 */
class Delta_0073 extends App_Migration_Delta
{
    protected $_autor = 'Anderson Poccorpachi';
    protected $_desc = 'Agregar nuevo campo como en la tabla beneficio';
    
    public function up()
    {
        $sql = "ALTER TABLE `beneficio` ADD `como` VARCHAR(100) NULL DEFAULT NULL;";
        $this->_db->query($sql);
        return true;
    }
}
