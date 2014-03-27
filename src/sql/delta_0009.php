<?php

class Delta_0009 extends App_Migration_Delta
{
    protected $_autor = 'Julio Florian';
    protected $_desc = 'Tabla para mantener los contadores de los cupones consumidos y generados';

    public function up()
    {
        $sql = "DROP TABLE IF EXISTS `suscriptor_beneficio`;";
        $this->_db->query($sql);
        $sql = "CREATE TABLE IF NOT EXISTS suscriptor_beneficio (
                id INT NOT NULL AUTO_INCREMENT ,
                suscriptor_id INT NOT NULL ,
                beneficio_id INT NOT NULL ,
                cupon_consumido TINYINT( 5 ) NULL COMMENT 'Cantidad de cupones consumidos',
                cupon_generado TINYINT( 5 ) NULL COMMENT 'Cantidad de cupones generados',
                PRIMARY KEY ( id ) ,
                INDEX fk_suscriptor_beneficio_suscriptor ( suscriptor_id ASC ) ,
                INDEX fk_suscriptor_beneficio_beneficio ( beneficio_id ASC ) ,
                CONSTRAINT fk_suscriptor_beneficio_suscriptor FOREIGN KEY ( suscriptor_id ) REFERENCES suscriptor (
                id
                ) ON DELETE NO ACTION ON UPDATE NO ACTION ,
                CONSTRAINT fk_suscriptor_beneficio_beneficio FOREIGN KEY ( beneficio_id ) REFERENCES beneficio (
                id
                ) ON DELETE NO ACTION ON UPDATE NO ACTION
                )";
        $this->_db->query($sql);
        return true;
    }

}
