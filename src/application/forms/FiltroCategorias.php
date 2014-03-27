<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Categoria
 *
 * @author fcj
 */
class Application_Form_FiltroCategorias extends Zend_Form
{
    protected $_maxlengthNombre = '75';
    
    public function init()
    {
        parent::init();
        
        $eBusqueda = new Zend_Form_Element_Text('nombre');
        $v = new Zend_Validate_StringLength(array('min'=>3,'max'=>20));
        $eBusqueda->setAttrib('maxLength', $this->_maxlengthNombre);
        $eBusqueda->addValidator($v);
        $f = new Zend_Filter_StringTrim();
        $eBusqueda->addFilter($f);
        $this->addElement($eBusqueda);

        $txtDescrip = new Zend_Form_Element_Text('descripcion');
        $txtDescrip->addValidator(new Zend_Validate_StringLength(array('min'=>3,'max'=>20)));
        $txtDescrip->addFilter(new Zend_Filter_StringTrim());
        $this->addElement($txtDescrip);

        $cboEstado = new Zend_Form_Element_Select('estado');
        $cboEstado->addMultiOption('', 'Todos');
        $cboEstado->addMultiOption('1', 'Activo');
        $cboEstado->addMultiOption('0', 'Inactivo');
        $this->addElement($cboEstado);

    }    
}