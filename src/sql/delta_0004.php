<?php

/**
 * Description of delta_0003
 *
 * @author DjTabo
 */
class Delta_0004 extends App_Migration_Delta
{

    protected $_autor = 'Favio Condori';
    protected $_desc = 'Agregando tabla invitacion para manejo de invitaciones a beneficiaros por parte del suscriptor';

    public function up()
    {
        $sql = "DROP TABLE IF EXISTS `invitacion`;";
        $this->_db->query($sql);
        $sql = "CREATE TABLE IF NOT EXISTS `invitacion` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `suscriptor_id` int(11) NOT NULL,
            `suscriptor_invitado_id` int(11) DEFAULT NULL COMMENT 'Campo asociado al suscriptor en caso de que ya este registrado',
            `email` varchar(75) DEFAULT NULL,
            `nombres` varchar(45) DEFAULT NULL,
            `apellidos` varchar(75) DEFAULT NULL,
            `sexo` enum('M','F') DEFAULT 'M',
            `fecha_nacimiento` date DEFAULT NULL,
            `tipo_documento` enum('DNI','Carnet Extranjeria') DEFAULT 'DNI',
            `numero_documento` varchar(45) DEFAULT NULL,
            `token_activacion` varchar(50) DEFAULT NULL,
            `token_expiracion` datetime DEFAULT NULL,
            `fecha_invitacion` datetime DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `fk_invitacion_suscriptor1` (`suscriptor_invitado_id`),
            KEY `fk_invitacion_suscriptor2` (`suscriptor_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
        $this->_db->query($sql);
        $sql = "ALTER TABLE `suscriptor` CHANGE `es_invitado` `es_invitado` TINYINT( 4 ) NULL DEFAULT '0'";
        $this->_db->query($sql);
        $sql = "ALTER TABLE `beneficio` ADD `chapita_color` VARCHAR( 20 ) NOT NULL AFTER `chapita`";
        $this->_db->query($sql);
        return true;
    }

}
