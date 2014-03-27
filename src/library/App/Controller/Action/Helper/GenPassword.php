<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UtilFiles
 *
 * @author Julio Florian
 */
class App_Controller_Action_Helper_GenPassword extends Zend_Controller_Action_Helper_Abstract
{

    public function _genPassword($length = 8)
    {
        $cset = 'aeuybdghjmnpqrstvz23456789';
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $cset[(rand() % strlen($cset))];
        }
        return $password;
    }
    
}
