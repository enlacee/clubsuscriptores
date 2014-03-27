<?php

/**
 * Description of delta_0050
 *
 * @author phpauldj
 */
class Delta_0050 extends App_Migration_Delta
{
    protected $_autor = 'Paul Taboada';
    protected $_desc = 'Creación de lógica de perfiles';
    
    public function up()
    {
        /*$sql = "DROP TABLE IF EXISTS `perfil`;";
        $this->_db->query($sql);
        
        $sql = "CREATE  TABLE IF NOT EXISTS `perfil` (
                  `id` INT NOT NULL AUTO_INCREMENT ,
                  `nombre` VARCHAR(45) NULL ,
                  `descripcion` VARCHAR(150) NULL ,
                  `creado_por` INT NOT NULL ,
                  `actualizado_por` INT NULL ,
                  `fecha_registro` DATETIME NULL ,
                  `fecha_actualizacion` DATETIME NULL ,
                  `modulo_id` INT NULL ,
                  PRIMARY KEY (`id`) ,
                  INDEX `fk_perfil_usuario1` (`creado_por` ASC) ,
                  INDEX `fk_perfil_usuario2` (`actualizado_por` ASC) ,
                  CONSTRAINT `fk_perfil_usuario1`
                    FOREIGN KEY (`creado_por` )
                    REFERENCES `usuario` (`id` )
                    ON DELETE NO ACTION
                    ON UPDATE NO ACTION,
                  CONSTRAINT `fk_perfil_usuario2`
                    FOREIGN KEY (`actualizado_por` )
                    REFERENCES `usuario` (`id` )
                    ON DELETE NO ACTION
                    ON UPDATE NO ACTION)
                ENGINE = InnoDB;";
        $this->_db->query($sql);
        
        $sql = "INSERT INTO `perfil` (`id`, `nombre`, `descripcion`, `creado_por`, `actualizado_por`, `fecha_registro`, `fecha_actualizacion`, `modulo_id`) VALUES
(1, 'Gestor del Portal', '', 1, 1, '2012-01-05 00:00:00', '2012-01-05 00:00:00', '1'),
(2, 'Administrador de Establecimiento', '', 1, 1, '2012-01-05 00:00:00', '2012-01-05 00:00:00', '2'),
(3, 'Administrador del Portal', '', 1, 1, '2012-01-05 00:00:00', '2012-01-05 00:00:00', '3');";
        $this->_db->query($sql);
        
        $sql = "DROP TABLE IF EXISTS `modulo` ;";
        $this->_db->query($sql);
        
        $sql = "CREATE  TABLE IF NOT EXISTS `modulo` (
                  `id` INT NOT NULL ,
                  `nombremod` VARCHAR(20) NULL ,
                  PRIMARY KEY (`id`) )
                ENGINE = InnoDB;";
        $this->_db->query($sql);
        
        $sql = "INSERT INTO `modulo` (`id`, `nombremod`) VALUES
                (1, 'gestor'),
                (2, 'establecimiento'),
                (3, 'admin');";
        
        $this->_db->query($sql);
        
        //$sql = "ALTER TABLE `usuario` DROP FOREIGN KEY `fk_usuario_perfil1`,
        //        DROP COLUMN `perfil_id`;";
        //$this->_db->query($sql);
        
        $sql = "ALTER TABLE `usuario` ADD `perfil_id` INT NULL AFTER `fecha_actualizacion`,
                ADD CONSTRAINT `fk_usuario_perfil1`
                FOREIGN KEY (`perfil_id` )
                REFERENCES `perfil` (`id` );";
        $this->_db->query($sql);
        
        $sql = "DROP TABLE IF EXISTS `opcion` ;";
        $this->_db->query($sql);
        
        $sql = "CREATE  TABLE IF NOT EXISTS `opcion` (
                  `id` INT NOT NULL AUTO_INCREMENT ,
                  `modulo_id` INT NOT NULL ,
                  `nombreop` VARCHAR(45) NULL ,
                  `descripop` VARCHAR(150) NULL ,
                  `controlador` VARCHAR(60) NULL ,
                  `orden` TINYINT NULL ,
                  PRIMARY KEY (`id`) ,
                  INDEX `fk_opcion_modulo1` (`modulo_id` ASC) ,
                  CONSTRAINT `fk_opcion_modulo1`
                    FOREIGN KEY (`modulo_id` )
                    REFERENCES `modulo` (`id` )
                    ON DELETE NO ACTION
                    ON UPDATE NO ACTION)
                ENGINE = InnoDB
                COMMENT = 'Contiene las opciones o menus que se le asignara al usuario.' ;";
        $this->_db->query($sql);
        
        $sql = "INSERT INTO `opcion` (`id`, `modulo_id`, `nombreop`, `descripop`, `controlador`, `orden`) VALUES
                (1, 1, 'Inicio', '', '', 1),
                (2, 1, 'Establecimiento', '', 'establecimientos', 2),
                (3, 1, 'Beneficios', '', 'beneficios', 3),
                (4, 1, 'Vida Social', '', 'vida-social', 4),
                (5, 1, 'Conciliación', '', 'conciliacion', 5),
                (6, 1, 'Encuestas', '', 'encuestas', 6),
                (7, 1, 'Categorías', '', 'categorias', 7),
                (8, 1, 'Configuración', '', 'configuracion', 8),
                (9, 2, 'Inicio', '', 'redencion-beneficio', 1),
                (10, 2, 'Promociones Activas', '', 'beneficios-ofertados', 2),
                (11, 2, 'Reporte', '', 'reporte-consumo', 3),
                (12, 3, 'Inicio', '', 'inicio', 1),
                (13, 3, 'Usuarios', '', 'usuarios', 2),
                (14, 3, 'Perfiles', '', 'perfiles', 3),
                (15, 3, 'Opciones', '', 'opciones', 4);";
        $this->_db->query($sql);
        
        $sql = "DROP TABLE IF EXISTS `opcion_perfil` ;";
        $this->_db->query($sql);
        
        $sql = "CREATE  TABLE IF NOT EXISTS `opcion_perfil` (
                  `opcion_id` INT NOT NULL ,
                  `perfil_id` INT NOT NULL ,
                  `activo` TINYINT NULL ,
                  PRIMARY KEY (`opcion_id`, `perfil_id`) ,
                  INDEX `fk_opcion_has_perfil_perfil1` (`perfil_id` ASC) ,
                  INDEX `fk_opcion_has_perfil_opcion1` (`opcion_id` ASC) ,
                  CONSTRAINT `fk_opcion_has_perfil_opcion1`
                    FOREIGN KEY (`opcion_id` )
                    REFERENCES `opcion` (`id` )
                    ON DELETE NO ACTION
                    ON UPDATE NO ACTION,
                  CONSTRAINT `fk_opcion_has_perfil_perfil1`
                    FOREIGN KEY (`perfil_id` )
                    REFERENCES `perfil` (`id` )
                    ON DELETE NO ACTION
                    ON UPDATE NO ACTION)
                ENGINE = InnoDB;";
        $this->_db->query($sql);
        
        $sql = "INSERT INTO `opcion_perfil` (`opcion_id`, `perfil_id`, `activo`) VALUES
                (1, 1, 1),
                (2, 1, 1),
                (3, 1, 1),
                (4, 1, 1),
                (5, 1, 1),
                (6, 1, 1),
                (7, 1, 1),
                (8, 1, 1),
                (9, 2, 1),
                (10, 2, 1),
                (11, 2, 1),
                (12, 3, 1),
                (13, 3, 1),
                (14, 3, 1),
                (15, 3, 1);";
        $this->_db->query($sql);
        
        $objUser = new Application_Model_Usuario();
        $rsuser = $objUser->select()->from()->columns(array('id','rol'))->query();
        var_dump($rsuser); exit;
        foreach ($rsuser as $id => $value) {
            $equivale = array('gestor'=>1, 'establecimiento'=>2, 'admin'=>3, 'suscriptor'=>'');
            $userId = $value['id'];
            $objUser->update(array('perfil_id'=> $equivale[$value['rol']]), "id = '".$userId."'");
        }
        */
        return true;
    }
}
