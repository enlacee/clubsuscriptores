<?php

/**
 * Description of delta_0040
 *
 * @author FCJ
 */
class Delta_0041 extends App_Migration_Delta
{
    protected $_autor = 'Favio Condori';
    protected $_desc = 'Insertando parametros de configuracion para lucene';
    
    public function up()
    {
        $sql = "INSERT INTO `parametro` (`actualizado_por`, `nombre`, `descripcion`, `variable`, 
            `valor`, `tipo`, `categoria`, `es_editable`, `archivo`, `actualizar`, `fecha_registro`, 
            `fecha_actualizacion`) VALUES 
            ('1', '[Lucene]ReadOnly', 'Indicador de solo lectura para lucene', 'lucene.readOnly',
            '1', 'aplicacion', 'lucene', 
            '0', 'cs.ini', '0', '".date('Y-m-d h:i:s')."', '".date('Y-m-d h:i:s')."');";
        $this->_db->query($sql);
        $sql = "INSERT INTO `parametro` (`actualizado_por`, `nombre`, `descripcion`, `variable`, 
            `valor`, `tipo`, `categoria`, `es_editable`, `archivo`, `actualizar`, `fecha_registro`, 
            `fecha_actualizacion`) VALUES 
            ('1', '[Lucene]TimeOut', 'Indicador de timeout para lucene', 'lucene.timeout',
            '2', 'aplicacion', 'lucene', 
            '0', 'cs.ini', '0', '".date('Y-m-d h:i:s')."', '".date('Y-m-d h:i:s')."');";
        $this->_db->query($sql);
        return true;
    }
}
