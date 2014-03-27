<?php

class Delta_0090 extends App_Migration_Delta
{
    protected $_autor = 'Anderson Poccorpachi';
    protected $_desc = 'Agregando campo nivel y padre_perfil_id tabla perfil';
    
    public function up()
    {
        $sql = "ALTER TABLE perfil ADD nivel TINYINT(1) NOT NULL DEFAULT 1;";
        $this->_db->query($sql);
        $sql = "ALTER TABLE perfil ADD padre_perfil_id INT(11) NULL;";
        $this->_db->query($sql);
        $sql = "INSERT INTO `perfil` (`id`, `nombre`, `descripcion`, `creado_por`, `actualizado_por`, `fecha_registro`, `fecha_actualizacion`, `modulo_id`,`nivel`,`padre_perfil_id`)
            VALUES ('4','Ejecutivo de Ventas', '','1','1','2012-06-07 00:00:00', '2012-06-07 00:00:00', '2','2','2'),
                   ('5','Jefe de Establecimiento', '','1','1','2012-06-07 00:00:00', '2012-06-07 00:00:00', '2','2','2');";
        $this->_db->query($sql);
        $sql = "INSERT INTO `opcion` (`id`, `modulo_id`, `nombreop`, `descripop`, `controlador`, `orden`)
            VALUES ('17', '2', 'Eliminado', 'Cupones Eliminados', 'cupon', '5');";
        $this->_db->query($sql);
        $sql = "INSERT INTO `opcion_perfil` (`opcion_id`, `perfil_id`, `activo`)
            VALUES ('9', '4','1'),('10', '4','1'),('11', '4','1'),('16', '4','1'),
                   ('9', '5','1'),('10', '5','1'),('11', '5','1'),('16', '5','1'),('17', '5','1');";
        $this->_db->query($sql);
        $sql = "UPDATE `usuario` SET `perfil_id`=4 WHERE `perfil_id`=2;";
        $this->_db->query($sql);
        return true;
    }
}