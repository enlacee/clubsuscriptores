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
class Application_Form_FiltroPerfiles extends Zend_Form
{
    private $_maxlenghtNombre = 45;
    
    public function init()
    {
        parent::init();
        
        $txtPerfil = new Zend_Form_Element_Text('perfil');
        //$txtDescrip->setLabel('Nombre del Perfil: ');
        $txtPerfil->setAttrib('maxLength', $this->_maxlenghtNombre);
        $this->addElement($txtPerfil);

        $this->addElement(
            'button', 'buscar', array(
                'id' => "btnSearchPerfil",
                'Label'=>''
            )
        );
        
        $this->setAction('');
        $this->setMethod('post');
        $this->setAttribs(array('id'=>'frmPerfiles','name'=>'frmPerfiles'));
    }
}