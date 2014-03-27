<?php

/**
 * Description of delta_0001
 *
 * @author DjTabo
 */
class Delta_0026 extends App_Migration_Delta
{
    protected $_autor = 'Paul Taboada';
    protected $_desc = 'Agregando campo es_suscriptor, para diferenciar datos de un suscriptor y usuario registrado';

    public function up()
    {
        $sql = "DROP TABLE IF EXISTS `Temp_SQL_Nro1`;";
        $this->_db->query($sql);
        $sql = "CREATE TABLE `Temp_SQL_Nro1` (`cod_entesuscriptor` INTEGER UNSIGNED NOT NULL) ENGINE = INNODB";
        $this->_db->query($sql);

        $sql = "DROP TABLE IF EXISTS `Temp_SQL_Nro2`;";
        $this->_db->query($sql);
        $sql = "CREATE TABLE `Temp_SQL_Nro2` (`cod_entesuscriptor` INTEGER UNSIGNED NOT NULL) ENGINE = INNODB;";
        $this->_db->query($sql);
        return true;
    }

}