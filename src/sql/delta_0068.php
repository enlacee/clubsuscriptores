<?php

/**
 * Description of delta_0063
 *
 * @author FCJ
 */
class Delta_0068 extends App_Migration_Delta
{
    protected $_autor = 'Favio Condori';
    protected $_desc = 'Tabla detalle_tipo_promocion para agregar información adicional a los beneficios';
    
    public function up()
    {
        $sql = "ALTER TABLE `cupon` ADD COLUMN detalle_tipo_promocion_id INT(11) NULL DEFAULT NULL AFTER `beneficio_id`";
        $this->_db->query($sql);
        $sql = "ALTER TABLE `cupon` ADD CONSTRAINT `fk_detalle_tipo_promocion1` FOREIGN KEY (`detalle_tipo_promocion_id`)
            REFERENCES `detalle_tipo_promocion` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION";
        $this->_db->query($sql);
        return true;
    }
}