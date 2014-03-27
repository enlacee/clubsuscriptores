<?php

/**

 * Description of delta_0069
 *
 * @author FCJ
 */
class Delta_0069 extends App_Migration_Delta
{
    protected $_autor = 'Favio Condori';
    protected $_desc = 'Agregar campo elog para eliminar logicamente un beneficio registrado';
    
    public function up()
    {
        $sql = "ALTER TABLE beneficio 
            ADD elog TINYINT(1) NOT NULL DEFAULT 0 AFTER publicado,
            ADD fecha_de_baja DATETIME NULL DEFAULT NULL AFTER fecha_retiro,
            ADD de_baja_por INT(11) NULL DEFAULT NULL AFTER retirado_por,
            ADD CONSTRAINT `fk_bajapor_beneficio_usuario`
                FOREIGN KEY (`de_baja_por`)
                REFERENCES `usuario` (`id`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION;
            ";
        $this->_db->query($sql);
        return true;
    }
}