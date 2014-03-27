<?php

/**
 * Description of delta_0033
 *
 * @author Solman
 */
class Delta_0034 extends App_Migration_Delta
{
    protected $_autor = 'Solman Vaisman Gonzalez';
    protected $_desc = 'Tabla TempLucene';

    public function up()
    {
        $sql = "DROP TABLE IF EXISTS `temp_lucene`;";
        $this->_db->query($sql);

        $sql = "CREATE TABLE `temp_lucene` (
                  `id` INT(8) NOT NULL AUTO_INCREMENT,
                  `tipo` ENUM('beneficios') DEFAULT NULL,
                  `params` TEXT,
                  `namefunction` VARCHAR(150) DEFAULT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=MYISAM AUTO_INCREMENT=1 COMMENT=''
                                ROW_FORMAT=DEFAULT
                                CHARSET=latin1
                                COLLATE=latin1_swedish_ci;";
        $this->_db->query($sql);
        return true;
    }
}
