<?php

class Delta_0097 extends App_Migration_Delta
{
    protected $_autor = 'Anderson Poccorpachi';
        protected $_desc = 'Agregar el modulo Anuncios a gestor';
    
    public function up()
    {
        $sql = "INSERT INTO `opcion` (`id`, `modulo_id`, `nombreop`, `descripop`, `controlador`, `orden`)
            	VALUES	('18','1','Anunciantes','','anunciante','2');";
        $this->_db->query($sql);
        $sql = "INSERT INTO `opcion_perfil` (`opcion_id`, `perfil_id`, `activo`)
            	VALUES ('18', '1', '1');";
        $this->_db->query($sql);
        $sql = "CREATE  TABLE IF NOT EXISTS `anunciante` (
                `id` INT(11) NOT NULL AUTO_INCREMENT, `razon_social` VARCHAR(200) NOT NULL ,
                `ruc` VARCHAR(11) NOT NULL, `fecha_registro` DATETIME NULL,
                `fecha_actualizacion` DATETIME NULL, `creado_por` INT(11) NOT NULL ,
                `actualizado_por` INT(11) NOT NULL, `activo` TINYINT(4) NULL DEFAULT 0,
                `de_baja_por` INT(11) NULL, `fecha_de_baja` DATETIME NULL, `elog` TINYINT(1) NULL DEFAULT 0 ,
                PRIMARY KEY (`id`) ,
                INDEX `fk_anunciantes_usuario1` (`creado_por` ASC) ,
                INDEX `fk_anunciantes_usuario2` (`actualizado_por` ASC) ,
                INDEX `fk_anunciante_usuario1` (`de_baja_por` ASC) ,
                CONSTRAINT `fk_anunciantes_usuario1`
                    FOREIGN KEY (`creado_por` )
                    REFERENCES `usuario` (`id` )
                    ON DELETE NO ACTION
                    ON UPDATE NO ACTION,
                CONSTRAINT `fk_anunciantes_usuario2`
                    FOREIGN KEY (`actualizado_por` )
                    REFERENCES `usuario` (`id` )
                    ON DELETE NO ACTION
                    ON UPDATE NO ACTION,
                CONSTRAINT `fk_anunciante_usuario1`
                    FOREIGN KEY (`de_baja_por` )
                    REFERENCES `usuario` (`id` )
                    ON DELETE NO ACTION
                    ON UPDATE NO ACTION)
                ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
        $this->_db->query($sql);
        $sql = "ALTER TABLE `beneficio` ADD COLUMN anunciante_id INT(11) NULL DEFAULT NULL AFTER `tipo_redencion`";
        $this->_db->query($sql);
        $sql = "ALTER TABLE `beneficio` ADD CONSTRAINT `fk_anunciante1` FOREIGN KEY (`anunciante_id`)
            REFERENCES `anunciante` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION";
        $this->_db->query($sql); 
        return true;
    }
}