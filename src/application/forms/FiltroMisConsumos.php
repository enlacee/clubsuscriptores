<?php

/**
 * @author Ander
 */
class Application_Form_FiltroMisConsumos extends App_Form
{
    private $_idSuscriptor = '0';
    
    public function  __construct($options = null)
    {
        parent::__construct($options);
        //$this->setConfig($this->_config->frmsgestor->filtros->articulos);
    }
    
    public function init()
    {
        parent::init();        
        $e = new Zend_Form_Element_Hidden('col');
        $this->addElement($e);
        
        $e = new Zend_Form_Element_Hidden('ord');
        $this->addElement($e);
    }
    
    public function setIdSuscriptor($id)
    {
        $this->_idSuscriptor=$id;
    }
    
    public function getIdSuscriptor()
    {
        return $this->_idSuscriptor;
    }

    public function add_cboAnio()
    {
        $combo = new Zend_Form_Element_Select('cboAnio');
//        $combo->addMultiOption(0, 'Año');
        $Anio=Application_Model_Cupon::getAniosbySuscritorId($this->getIdSuscriptor());
        if (empty($Anio)) {
            $dateAnio=date('Y');
            $combo->addMultiOption($dateAnio,$dateAnio);
        } else {
            $combo->addMultiOptions($Anio);
        }
//        $e = new Zend_Filter_StringToUpper();
//        $combo->addFilter($e);
        $this->addElement($combo);
        //parent::init();
    }
    
    public function add_cboMes()
    {
        $combo = new Zend_Form_Element_Select('cboMes');
        //$combo->addMultiOption(0, 'Mes');
        $combo->addMultiOption('1', 'Enero');
        $combo->addMultiOption('2', 'Febrero');
        $combo->addMultiOption('3', 'Marzo');
        $combo->addMultiOption('4', 'Abril');
        $combo->addMultiOption('5', 'Mayo');
        $combo->addMultiOption('6', 'Junio');
        $combo->addMultiOption('7', 'Julio');
        $combo->addMultiOption('8', 'Agosto');
        $combo->addMultiOption('9', 'Septiembre');
        $combo->addMultiOption('10', 'Octubre');
        $combo->addMultiOption('11', 'Noviembre');
        $combo->addMultiOption('12', 'Diciembre');
//        $e = new Zend_Filter_StringToUpper();
//        $combo->addFilter($e);
        $this->addElement($combo);
        //parent::init();
    }
    
    
}