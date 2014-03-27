<?php

/**
 * Description of delta_0042
 *
 * @author FCJ
 */
class Delta_0042 extends App_Migration_Delta
{
    protected $_autor = 'Favio Condori';
    protected $_desc = 'Insertando numero telefonicos por defecto';
    
    public function up()
    {
        $prefix = array(1 => '0000', 2 => '000', 3 => '00', 4 => '0', 5 => '');
        $obj = new Application_Model_Establecimiento();
        $establecimientos = $obj->fetchAll()->toArray();
        foreach($establecimientos as $establecimiento) {
            $sql = "INSERT INTO numero_telefonico_establecimiento(`establecimiento_id`, `numero_telefonico`, `activo`, `fecha_creacion`, creado_por)  
                VALUES ('".$establecimiento['id']."', '".$establecimiento['telefono_contacto']."', '".$establecimiento['activo']."', '".date('Y-m-d H:i:s')."', '1')";
            $this->_db->query($sql);
        }
        return true;
    }
}
