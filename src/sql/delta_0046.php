<?php

/**
 * Description of delta_0046
 *
 * @author FCJ
 */
class Delta_0046 extends App_Migration_Delta
{
    protected $_autor = 'Favio Condori';
    protected $_desc = 'Generando hash para suscriptores';
    
    public function up()
    {
        $obj = new Application_Model_Suscriptor();
        $suscriptores = $obj->fetchAll()->toArray();
        $helper = new App_Controller_Action_Helper_Suscriptor();
        foreach($suscriptores as $suscriptor) {
            $hash = $helper->generaHashSuscriptor($suscriptor['id']);
            $sql = "UPDATE suscriptor SET hash = '" . $hash . "' WHERE id = " . $suscriptor['id'];
            $this->_db->query($sql);
        }
        return true;
    }
}
