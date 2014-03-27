<?php

/**
 * Description of delta_0061
 *
 * @author phpauldj
 */
class Delta_0062 extends App_Migration_Delta
{
    protected $_autor = 'Paul Taboada';
    protected $_desc = 'Insertando parametros de configuracion para nro limite de opciones por encuesta';
    
    public function up()
    {
        $sql = "INSERT INTO `parametro` (`actualizado_por`, `nombre`, `descripcion`, `variable`, 
            `valor`, `tipo`, `categoria`, `es_editable`, `archivo`, `actualizar`, `fecha_registro`, 
            `fecha_actualizacion`) VALUES 
            ('1', 'Nro Opciones por Encuesta - Gestor', 'Número de opciones por encuesta - Mantenimiento de Encuestas', 'gestor.encuestas.nrooptions',
            '5', 'gestor', 'Encuesta', 
            '1', 'cs.ini', '1', '".date('Y-m-d h:i:s')."', '".date('Y-m-d h:i:s')."');";
        $this->_db->query($sql);
        
        return true;
    }
}