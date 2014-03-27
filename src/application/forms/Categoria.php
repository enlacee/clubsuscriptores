<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Categoria
 *
 * @author eanaya
 */
class Application_Form_Categoria extends Zend_Form
{
    private $_maxlengthNombre = '30';
    
    public function init()
    {
        parent::init();
        
        $e = new Zend_Form_Element_Text('nombre');
        $e->setRequired(true);
        $e->setAttrib('maxLength', $this->_maxlengthNombre);
        $e->addValidator('StringLength', true, array(0, $this->_maxlengthNombre));
        $e->getValidator('StringLength')
            ->setMessage('Debe ingresar como máximo '.$this->_maxlengthNombre.' caracteres.');
        $e->addValidator('NotEmpty', true);
        $e->getValidator('NotEmpty')->setMessage('Campo requerido.');
        $f = new Zend_Filter_StringTrim();
        $e->addFilter($f);
        $this->addElement($e);

        $e = new Zend_Form_Element_Textarea('descripcion');
        $e->setAttrib('cols', 18);
        $e->setAttrib('rows', 3);
        $v = new Zend_Validate_StringLength(array('min'=>0,'max'=>100));
        $e->setAttrib('maxLength', 100);
        $e->addValidator($v);
        $this->addElement($e);
        
        $cboEstado = new Zend_Form_Element_Select('activo');
        $cboEstado->addMultiOption('1', 'Activo');
        $cboEstado->addMultiOption('0', 'Inactivo');
        $this->addElement($cboEstado);
    }
    
}