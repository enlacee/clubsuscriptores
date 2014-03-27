<?php

/**
 * Description of delta_0063
 *
 * @author FCJ
 */
class Delta_0067 extends App_Migration_Delta
{
    protected $_autor = 'Favio Condori';
    protected $_desc = 'Tabla detalle_tipo_promocion para agregar información adicional a los beneficios';
    
    public function up()
    {
        $sql = "CREATE TABLE `detalle_tipo_promocion` (
            `id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
            `beneficio_id` INT( 11 ) NOT NULL ,
            `codigo` VARCHAR( 50 ) NOT NULL ,
            `descuento` DECIMAL( 8, 2 ) NOT NULL ,
            `descripcion` TEXT NOT NULL ,
            `cantidad` INT NOT NULL,
            KEY `fk_beneficio_detalle1` (`beneficio_id`),
            CONSTRAINT `fk_beneficio_detalle1` FOREIGN KEY (`beneficio_id`) 
            REFERENCES `beneficio` (`id`)  ON DELETE NO ACTION ON UPDATE NO ACTION
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;
        ";
        $this->_db->query($sql);
        
        return true;
    }
}