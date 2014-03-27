<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SufijoImagen
 *
 * @author Ander
 */
class App_View_Helper_ImgName extends Zend_View_Helper_HtmlElement
{
    //put your code here
    public function ImgName($path, $sufix)
    {
        $imgName = substr($path, 0, strrpos($path, '.'));
        $imgExt  = substr($path, strrpos($path, '.'));
        $array = explode("-",$imgName);
        $count = count($array);
        $count = ($count>1)?$count:1;
        $count_1 = $count - 1;
        $img_name = str_replace("-".$array[$count_1].$imgExt, "", $path);
        $id = "-".$array[$count_1];
        return $img_name.$sufix.$id.$imgExt;
    }
}