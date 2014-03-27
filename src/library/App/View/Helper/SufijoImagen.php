<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SufijoImagen
 *
 * @author DjTabo
 */
class App_View_Helper_SufijoImagen extends Zend_View_Helper_HtmlElement
{
    //put your code here
    public function SufijoImagen($path, $sufix)
    {
        $imgName = substr($path, 0, strrpos($path, '.'));
        $imgExt  = substr($path, strrpos($path, '.'));
        return $imgName.$sufix.$imgExt;
    }
}
