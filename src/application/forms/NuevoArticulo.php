<?php

/**
 * Description of FiltroArticulos
 *
 * @author Solman
 */
class Application_Form_NuevoArticulo extends App_Form
{
    public function  __construct($options = null)
    {
        parent::__construct($options);
        $this->setConfig($this->_config->frmsgestor->frmnuevo->articulos);
    }

    public function init()
    {
        parent::init();
        
    }
    
    
}