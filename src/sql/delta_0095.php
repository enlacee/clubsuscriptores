<?php

class Delta_0095 extends App_Migration_Delta
{
    protected $_autor = 'Anderson Poccorpachi';
    protected $_desc = 'Agregando subperfil Establecimiento Master';
    
    public function up()
    {
        $sql = "INSERT INTO `perfil` (`id`, `nombre`, `descripcion`, `creado_por`, `actualizado_por`, `fecha_registro`, `fecha_actualizacion`, `modulo_id`,`nivel`,`padre_perfil_id`)
            VALUES ('7','Establecimiento Master', '','1','1','2012-06-28 00:00:00', '2012-06-28 00:00:00', '2','2','2');";
        $this->_db->query($sql);
        $sql = "INSERT INTO `opcion_perfil` (`opcion_id`, `perfil_id`, `activo`)
            VALUES ('9', '7','1'),('10', '7','1'),('11', '7','1');";
        $this->_db->query($sql);
        return true;
    }
}