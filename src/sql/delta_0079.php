<?php

class Delta_0079 extends App_Migration_Delta
{
    protected $_autor = 'Anderson Poccorpachi';
    protected $_desc = 'Actualizar los beneficios de tipo sorteo y categoria concurso
        al tipo beneficio beneficio';

    public function up()
    {
        $sql = "UPDATE beneficio SET tipo_beneficio_id=7 WHERE tipo_beneficio_id=5 AND id IN (
                    SELECT IFNULL(beneficio_id,0) id FROM categoria_beneficio WHERE categoria_id=20
                );";
        $this->_db->query($sql);
        return true;
    }

}
