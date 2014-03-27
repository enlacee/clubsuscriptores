<?php

/**
 * Description of delta_0043
 *
 * @author DjTabo
 */
class Delta_0043 extends App_Migration_Delta
{
    protected $_autor = 'Paul Taboada';
    protected $_desc = 'Delta para agregar un campo a param.: redimidosconcilia.nropaginas = 10';

    public function up()
    {
        $sql = "INSERT INTO `parametro` (`actualizado_por`, `nombre`, `descripcion`, `variable`,
            `valor`, `tipo`, `categoria`, `es_editable`, `archivo`, `actualizar`, `fecha_registro`,
            `fecha_actualizacion`) VALUES
            ('1', 'Reporte de Conciliados', 'Numero de Filas por paginas para Reporte de".
            " Conciliación', 'gestor.redimidosconcilia.nropaginas',
            '10', 'gestor', 'Conciliacion',
            '1', 'cs.ini', '0', '".date('Y-m-d h:i:s')."', '".date('Y-m-d h:i:s')."');";
        $this->_db->query($sql);
        
        $sql = "INSERT INTO `parametro` (`actualizado_por`, `nombre`, `descripcion`, `variable`,
            `valor`, `tipo`, `categoria`, `es_editable`, `archivo`, `actualizar`, `fecha_registro`,
            `fecha_actualizacion`) VALUES
            ('1', 'Configuración de Parametros', 'Numero de Filas por paginas para Configuración ".
            " de Parametros', 'gestor.parametro.nropaginas',
            '10', 'gestor', 'Configuracion',
            '1', 'cs.ini', '0', '".date('Y-m-d h:i:s')."', '".date('Y-m-d h:i:s')."');";
        $this->_db->query($sql);
        return true;
    }

}
