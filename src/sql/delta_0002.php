<?php
class Delta_0002 extends App_Migration_Delta
{
    protected $_autor = "Favio Condori";
    protected $_desc = "Agregar el campo email_contacto en la tabla suscriptor";

    public function up()
    {
        $sql = "ALTER TABLE `suscriptor` ADD `email_contacto` VARCHAR(100) NULL;";
        $this->_db->query($sql);
        return true;
    }
}