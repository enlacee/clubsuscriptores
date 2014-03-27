<?php
/**
 * Description of delta_0047
 *
 * @author Solman
 */
class Delta_0047 extends App_Migration_Delta
{
    protected $_autor = 'Solman Vaisman';
    protected $_desc = 'Un campito para la tabla t_actsuscriptor';
    
    public function up()
    {
        $sql = "ALTER TABLE t_actsuscriptor
                ADD COLUMN `indica_mig` INT(1) DEFAULT '0' NULL AFTER `fch_registro`;";
        $this->_db->query($sql);
        return true;
    }
}
