<?php

class App_View_Helper_ExistePalabra extends Zend_View_Helper_HtmlElement
{
    public function ExistePalabra($nueva, $actual, $separador)
    {
        return in_array($nueva, explode($separador, $actual));
    }
}