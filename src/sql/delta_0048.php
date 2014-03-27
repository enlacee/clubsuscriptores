<?php
/**
 * Description of delta_0048
 *
 * @author FCJ
 */
class Delta_0048 extends App_Migration_Delta
{
    protected $_autor = 'Favio Condori';
    protected $_desc = 'Tabla con los distritos disponibles para suscriptores';
    
    public function up()
    {
        $sql = "DROP TABLE IF EXISTS `distrito_entrega`;";
        $this->_db->query($sql);
        $sql = "CREATE TABLE `distrito_entrega` (
            `id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
            `cciudis` INT( 11 ) NOT NULL ,
            `dciudis` VARCHAR( 100 ) NOT NULL ,
            `cod_ubigeo` INT( 11 ) NULL ,
            `sregact` VARCHAR( 10 ) NOT NULL
        );";
        $this->_db->query($sql);
        return true;
    }
}
