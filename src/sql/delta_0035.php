<?php

/**
 * Description of delta_0035
 *
 * @author FCJ
 */
class Delta_0035 extends App_Migration_Delta
{
    protected $_autor = 'Favio Condori';
    protected $_desc = 'Tabla numero_telefonico_establecimiento que contendrá los numeros de celular habilitados para consultar los webservices';

    public function up()
    {
        $sql = "DROP TABLE IF EXISTS `numero_telefonico_establecimiento`;";
        $this->_db->query($sql);

        $sql = "CREATE TABLE `numero_telefonico_establecimiento` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT ,
                    `establecimiento_id` INT(11) NOT NULL ,
                    `numero_telefonico` VARCHAR( 20 ) NOT NULL,
                    `operador` ENUM(  'claro',  'movistar',  'nextel',  'otro' ) NOT NULL DEFAULT  'claro',
                    `activo` TINYINT(4) NOT NULL DEFAULT 1 ,
                    `fecha_creacion` DATETIME NOT NULL ,
                    `fecha_actualizacion` DATETIME NULL ,
                    `creado_por` INT NOT NULL ,
                    `actualizado_por` INT NULL,
                    PRIMARY KEY (`id`),
                    KEY `fk_numero_establecimiento1` ( `establecimiento_id`),
                    KEY `fk_numero_creado_por` ( `creado_por`),
                    KEY `fk_numero_actualizado_por` ( `actualizado_por`),
                    CONSTRAINT `fk_numero_establecimiento1` FOREIGN KEY ( `establecimiento_id` ) REFERENCES `establecimiento` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
                    CONSTRAINT `fk_numero_creado_por` FOREIGN KEY ( `creado_por` ) REFERENCES `usuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
                    CONSTRAINT `fk_numero_actualizado_por` FOREIGN KEY ( `actualizado_por` ) REFERENCES `usuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
                ) ENGINE=InnoDB AUTO_INCREMENT=1";
        $this->_db->query($sql);
        return true;
    }
}
