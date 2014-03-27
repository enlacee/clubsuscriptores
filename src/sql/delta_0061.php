<?php

/**
 * Description of delta_0060
 *
 * @author FCJ
 */
class Delta_0061 extends App_Migration_Delta
{
    protected $_autor = 'Favio Condori';
    protected $_desc = 'Creacion de indices en la tablas t_actsuscriptor y suscriptor para acelerar la busqueda de usuarios por tipo y numero de documento';

    public function up()
    {
        $sql = 'CREATE INDEX idx_t_actsuscriptor_tipo_numero_documento ON t_actsuscriptor(cod_tipdocid, des_numdocid);';
        $this->_db->query($sql);
        $sql = 'CREATE INDEX idx_suscriptor_tipo_numero_documento ON suscriptor(tipo_documento, numero_documento);';
        $this->_db->query($sql);
        return true;
    }

}
