<?php

class App_View_Helper_BeneficioCuando extends Zend_View_Helper_Abstract
{

    function BeneficioCuando($fromDate, $toDate)
    {
        $from = new Zend_Date($fromDate);
        $to = new Zend_Date($toDate);
        $format = 
            Zend_Date::WEEKDAY . ' ' . 
            Zend_Date::DAY . " 'de' " . 
            Zend_Date::MONTH_NAME . " ";
        return $from->get($format) . ' hasta ' . $to->get($format);
    }

}