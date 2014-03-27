<?php

/**
 * Description of delta_0032
 *
 * @author DjTabo
 */
class Delta_0032 extends App_Migration_Delta
{
    protected $_autor = 'Paul Taboada';
    protected $_desc = 'Insertando un nuevo parametro de configuracion';
    
    public function up()
    {
        $sql = "INSERT INTO `parametro` (`actualizado_por`, `nombre`, `descripcion`, `variable`, 
            `valor`, `tipo`, `categoria`, `es_editable`, `archivo`, `actualizar`, `fecha_registro`, 
            `fecha_actualizacion`) VALUES 
            ('1', 'Mostrar Errores', 'Si el valor es 1, muestra el error correspondiente 
            de programación, sino solo un mensaje indicando que ha ocurrido un error', 'error.page',
            '0', 'aplicacion', 'App', 
            '1', 'cs.ini', '0', '".date('Y-m-d h:i:s')."', '".date('Y-m-d h:i:s')."');";
        $this->_db->query($sql);
        return true;
    }
}
