<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of Opcion
 *
 * @author Favio Condori
 */
class Application_Form_Opcion extends App_Form
{
    private $_maxlengthNombre = '45';
    private $_maxlengthDescripcion = '150';

    public function init()
    {
        $nombre = new Zend_Form_Element_Text('nombreop');
        $nombre->setAttrib('maxlength', $this->_maxlengthNombre);
        $nombre->setRequired(true);
        $this->addElement($nombre);

        $descripcion = new Zend_Form_Element_Textarea('descripop');
        $descripcion->setRequired(true);
        $descripcion->setAttrib('maxlength', $this->_maxlengthDescripcion);
        $this->addElement($descripcion);

//        $modulo = new Zend_Form_Element_Select('modulo_id');
//        $modulo->addMultiOption(0, 'Seleccione un Módulo');
//        $modulo->addMultiOptions(Application_Model_Modulo::getModulos());
//        $this->addElement($modulo);
    }

}
