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
class Application_Form_FiltroConfiguracion extends Zend_Form
{
    protected $_maxlengthNombre = '20';
    protected $_parametro = null;
    
    public function init()
    {
        parent::init();
        $this->_parametro = new Application_Model_Parametro();
        
        $cboTipo = new Zend_Form_Element_Select('tipo_parametro');
        $tipos = $this->_parametro->getTiposParametro();
        $cboTipo->addMultiOption('', 'Todos');
        $cboTipo->addMultiOptions($tipos);
        $this->addElement($cboTipo);
    }    
}