<?php

/**
 * Description of delta_0040
 *
 * @author FCJ
 */
class Delta_0040 extends App_Migration_Delta
{
    protected $_autor = 'Favio Condori';
    protected $_desc = 'Insertando parametros de configuracion para web services';
    
    public function up()
    {
        $sql = "INSERT INTO `parametro` (`actualizado_por`, `nombre`, `descripcion`, `variable`, 
            `valor`, `tipo`, `categoria`, `es_editable`, `archivo`, `actualizar`, `fecha_registro`, 
            `fecha_actualizacion`) VALUES 
            ('1', 'WSDL suscriptor', 'Url de ubicacion del WSDL del WS de suscriptor', 'services.wsdl.suscriptor',
            'http://devel.clubsc.info/services/soap/suscriptor', 'servicio', 'services', 
            '1', 'cs.ini', '0', '".date('Y-m-d h:i:s')."', '".date('Y-m-d h:i:s')."');";
        $this->_db->query($sql);
        $sql = "INSERT INTO `parametro` (`actualizado_por`, `nombre`, `descripcion`, `variable`, 
            `valor`, `tipo`, `categoria`, `es_editable`, `archivo`, `actualizar`, `fecha_registro`, 
            `fecha_actualizacion`) VALUES 
            ('1', 'WSDL establecimiento', 'Url de ubicacion del WSDL del WS de establecimiento', 'services.wsdl.establecimiento',
            'http://devel.clubsc.info/services/soap/establecimiento', 'servicio', 'services', 
            '1', 'cs.ini', '0', '".date('Y-m-d h:i:s')."', '".date('Y-m-d h:i:s')."');";
        $this->_db->query($sql);
        return true;
    }
}
