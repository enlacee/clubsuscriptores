<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DateFormatOpen
 *
 * @author DjTabo
 */
class App_View_Helper_DateFormatOpen extends Zend_View_Helper_HtmlElement
{
    //put your code here
    public function DateFormatOpen($fecha = '', $format = '')
    {
        $date = new Zend_Date(!empty($fecha)?$fecha:NULL);
        if ( empty( $format ) ) {
            $format = Zend_Date::DATES;
        }
        return $date->toString($format);
    }
}
