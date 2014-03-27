<?php

/**
 * Description of delta_0039
 *
 * @author Solman
 */
class Delta_0039 extends App_Migration_Delta
{
    protected $_autor = 'Solman Vaisman Gonzalez';
    protected $_desc = 'Delta para agregar un campo a cupon "comentario"';

    public function up()
    {
        $sql = "ALTER TABLE cupon
                ADD COLUMN `comentario` TEXT NULL AFTER `medio_redencion`;";
        $this->_db->query($sql);
        return true;
    }
}
