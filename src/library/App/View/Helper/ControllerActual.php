<?php

class App_View_Helper_ControllerActual extends Zend_View_Helper_HtmlElement
{

    public function ControllerActual()
    {
        return Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
    }

}