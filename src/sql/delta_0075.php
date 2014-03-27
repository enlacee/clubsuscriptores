<?php

class Delta_0075 extends App_Migration_Delta
{
    protected $_autor = 'Anderson Poccorpachi';
    protected $_desc = 'Crear el Tipo de beneficio Concurso';

    public function up()
    {
        $sql = "INSERT INTO `tipo_beneficio` (`id`,`nombre`,`descripcion`,`abreviado`,`slug`,`activo`)
                    VALUES (7,'Concurso',NULL,'Concurso','concurso','1');";
        $this->_db->query($sql);
        return true;
    }

}
