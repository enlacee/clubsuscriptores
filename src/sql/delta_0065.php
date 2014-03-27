<?php

/**
 * Description of delta_0065
 *
 * @author phpauldj
 */
class Delta_0065 extends App_Migration_Delta
{
    protected $_autor = 'Paul Taboada';
    protected $_desc = 'Agregar campo elog para eliminar logicamente un articulo de vida social';

    public function up()
    {
        $sql = "ALTER TABLE articulo ADD elog TINYINT(1) NOT NULL DEFAULT 0";
        $this->_db->query($sql);

        return true;
    }

}