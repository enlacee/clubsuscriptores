<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TextToUrl
 *
 * @author DjTabo
 */
class App_View_Helper_TextToUrl extends Zend_View_Helper_HtmlElement
{
    //put your code here
    public function TextToUrl($string= '',$maxLength=60)
    {
        $result = strtolower($string);
        $result = preg_replace("/[^a-z0-9\s-]/", "", $result);
        $result = trim(preg_replace("/[\s-]+/", " ", $result));
        $result = trim(substr($result, 0, $maxLength));
        $result = preg_replace("/\s/", "-", $result);
        return $result;
    }
}
