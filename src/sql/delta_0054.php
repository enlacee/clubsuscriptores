<?php

/**
 * Description of delta_0054
 *
 * @author phpauldj
 */
class Delta_0054 extends App_Migration_Delta
{
    protected $_autor = 'Paul Taboada';
    protected $_desc = 'Agregar tipo de beneficio "cines"';
    
    public function up()
    {
        $sql = "INSERT INTO `tipo_beneficio` (`id`, `nombre`, `descripcion`, `abreviado`, `slug`, `activo`) VALUES
            (6, 'Cines', NULL, 'Cine', 'cine', 1);";
        $this->_db->query($sql);
        return true;
    }
}
