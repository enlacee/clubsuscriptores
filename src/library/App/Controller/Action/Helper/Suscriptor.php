<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of Suscriptor
 *
 * @author FCJ
 */
class App_Controller_Action_Helper_Suscriptor extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * Genera un hash unico de 30 caractéres para el suscriptor.
     * @param int $id Identificador ID del suscriptor en la base de datos
     */
    public function generaHashSuscriptor($id)
    {
        do {
            $hash = substr(md5($id), 0, 17) . uniqid();
            $exits = Application_Model_Suscriptor::getSuscriptorByHash($hash);
        } while (!empty($exits));
        return $hash;
    }

}