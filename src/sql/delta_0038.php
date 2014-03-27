<?php

/**
 * Description of delta_0038
 *
 * @author Solman
 */
class Delta_0038 extends App_Migration_Delta
{
    protected $_autor = 'Solman Vaisman Gonzalez';
    protected $_desc = 'Delta para agregar un campo a params. conciliacion.nropaginas = 5';

    public function up()
    {
        $sql = "INSERT INTO `parametro` (`actualizado_por`, `nombre`, `descripcion`, `variable`,
            `valor`, `tipo`, `categoria`, `es_editable`, `archivo`, `actualizar`, `fecha_registro`,
            `fecha_actualizacion`) VALUES
            ('1', 'Mostrar Errores', 'Numero de Filas por paginas para Conciliacion en Gestor', 'gestor.conciliacion.nropaginas',
            '5', 'aplicacion', 'App',
            '1', 'cs.ini', '0', '".date('Y-m-d h:i:s')."', '".date('Y-m-d h:i:s')."');";
        $this->_db->query($sql);
        return true;
    }

}
