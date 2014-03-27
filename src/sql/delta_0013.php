<?php

class Delta_0013 extends App_Migration_Delta
{
    protected $_autor = "Ernesto Anaya";
    protected $_desc = "Agregar tabla temporal para datos de session";

    public function up()
    {
        $sql = "DROP TABLE IF EXISTS `zend_session`;";
        $this->_db->query($sql);
        $sql = "CREATE TABLE `zend_session` (
                `id` VARCHAR( 150 ) NOT NULL ,
                `modified` VARCHAR( 150 ) NOT NULL ,
                `data` TEXT NOT NULL ,
                `lifetime` VARCHAR( 150 ) NOT NULL ,
                PRIMARY KEY ( `id` )
                ) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;";
        $this->_db->query($sql);
        return true;
    }

}