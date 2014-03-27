<?php

/**
 * Description of delta_0037
 *
 * @author FCJ
 */
class Delta_0037 extends App_Migration_Delta
{
    protected $_autor = 'Favio Condori';
    protected $_desc = 'Agregar campo codigo para identificar beneficios';

    public function up()
    {
        $sql = "SELECT * FROM beneficio";
        $prefix = array(1 => '0000', 2 => '000', 3 => '00', 4 => '0', 5 => '');
        $obj = new Application_Model_Beneficio();
        $beneficios = $obj->fetchAll()->toArray();
        foreach($beneficios as $beneficio) {
            $zero = $prefix[strlen(''.$beneficio['id'])];
            $codigo = 'B'.date('Ym').$zero.$beneficio['id'];
            $sql = "UPDATE beneficio SET codigo = '".$codigo."' WHERE id = ".$beneficio['id'];
            $this->_db->query($sql);
        }
        return true;
    }

}
