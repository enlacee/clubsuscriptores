<?php

/**
 * Description of FiltroArticulos
 *
 * @author Solman
 */
class Application_Form_FiltroModulo extends App_Form
{
    public function init()
    {
        $combo = new Zend_Form_Element_Select('modulo');
        $combo->addMultiOption(0, 'Seleccione un Módulo');
        $combo->addMultiOptions(Application_Model_Modulo::getModulos());
        $e = new Zend_Filter_StringToUpper();
        $combo->addFilter($e);
        $this->addElement($combo);
        parent::init();

    }
    
    
}