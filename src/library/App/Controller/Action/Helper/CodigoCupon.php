<?php

/*
 * Ander
 */

class App_Controller_Action_Helper_CodigoCupon extends Zend_Controller_Action_Helper_Abstract
{
    
    public function generar()
    {
        $modelCupon = new Application_Model_Cupon();
        $arrayDato=$modelCupon->getMaxCupon();
        $arrayDato["id"]=$arrayDato["id"]+1;
        $count = strlen($arrayDato["id"]);
        
        $count = 6 - $count ;
        $count = ($count<0)?0:6;
        
        $numero = str_pad($arrayDato["id"], $count, "0", STR_PAD_RIGHT);
        
        $int1 = rand(0,25);$int2 = rand(0,25);
        $AZ = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $rand_letter_1 = substr($AZ, $int1, 1);
        $rand_letter_2 = substr($AZ, $int2, 1);
        return $numero.$rand_letter_1.$rand_letter_2;
    }

}
