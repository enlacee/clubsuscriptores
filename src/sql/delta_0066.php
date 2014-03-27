<?php

/**
 * Description of delta_0066
 *
 * @author phpauldj
 */
class Delta_0066 extends App_Migration_Delta
{
    protected $_autor = 'Paul Taboada';
    protected $_desc = 'Agregar campos de auditoria de baja en articulo de vida social y encuesta';
    
    public function up()
    {
        $sql = "ALTER TABLE encuesta ".
            "ADD fecha_de_baja DATETIME NULL DEFAULT NULL,".
            "ADD de_baja_por INT(11) NULL,".
            "ADD CONSTRAINT `fk_bajapor_encuesta_usuario`
                    FOREIGN KEY (`de_baja_por` )
                    REFERENCES `usuario` (`id` )
                    ON DELETE NO ACTION
                    ON UPDATE NO ACTION;";
        $this->_db->query($sql);
        
        $sql = "ALTER TABLE articulo ".
            "ADD fecha_de_baja DATETIME NULL DEFAULT NULL,".
            "ADD de_baja_por INT(11) NULL,".
            "ADD CONSTRAINT `fk_bajapor_articulo_usuario`
                    FOREIGN KEY (`de_baja_por` )
                    REFERENCES `usuario` (`id` )
                    ON DELETE NO ACTION
                    ON UPDATE NO ACTION;";
        $this->_db->query($sql);
        
        return true;
    }
}