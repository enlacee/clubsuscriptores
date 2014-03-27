<?php

class App_View_Helper_CadenaFecha extends Zend_View_Helper_Abstract
{

    function CadenaFecha($date)
    {
        $from = new Zend_Date($date);
        $format = 
            Zend_Date::WEEKDAY . ' ' . 
            Zend_Date::DAY . " 'de' " . 
            Zend_Date::MONTH_NAME . " 'del' ".
            Zend_Date::YEAR;
        
        return $from->get($format);
    }

}