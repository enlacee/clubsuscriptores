<?php

/**
 * Descripcion del formulario recuperar clave
 *
 * @author Jesus Fabian
 */
class Application_Form_Perfil extends App_Form
{
    private $_maxlengthNombre = '45';
    private $_maxlengthDescrip = '150';

    public function init()
    {
        parent::init();

        $e = new Zend_Form_Element_Text('nombre');
        $e->setAttrib('maxLength', $this->_maxlengthNombre);
        $e->setRequired(true);
        $e->errMsg = 'Campo Requerido';
        $this->addElement($e);

        $e = new Zend_Form_Element_Textarea('descripcion');
        $e->setAttrib('maxLength', $this->_maxlengthDescrip);
        $e->setRequired(true);
        $e->errMsg = 'Campo Requerido';
        $this->addElement($e);

        $e = new Zend_Form_Element_Select('modulo');
        $e->addMultiOption('', '.:: Módulo ::.');
        $e->setRequired(true);
        $e->errMsg = 'Campo Requerido';
        $e->addMultiOptions(Application_Model_Modulo::getModulos());
        $this->addElement($e);
    }
    
    public function addOptionsPerfil($options)
    {
        foreach ($options as $ind => $opt) {
            $this->addElementIdOption($opt['id'], $opt['id']);
            $this->addElementCheckboxState($opt['id'], $opt['activo']);
        }
    }
    
    public function addElementIdOption($id, $value = '')
    {
        $element = $this->getElement('idoption'.$id);
        //var_dump($element); exit;
        if (!empty($element)) {
            $this->removeElement('idoption'.$id);
        }
        $txtIdOption = new Zend_Form_Element_Hidden('idoption'.$id);
        $txtIdOption->setValue($value);
        $this->addElement($txtIdOption);
    }
    
    public function addElementCheckboxState($id, $value=0)
    {
        $element = $this->getElement('chkActivo'.$id);
        //var_dump($element); exit;
        if (!empty($element)) {
            $this->removeElement('chkActivo'.$id);
        }
        $chkActivo = new Zend_Form_Element_Checkbox('chkActivo'.$id);
        $chkActivo->setCheckedValue('1');
        $chkActivo->setUncheckedValue('0');
        $chkActivo->setChecked(($value==1?true:false));
        $this->addElement($chkActivo);
    }
    
    public function setNombre($value)
    {
        $element = $this->getElement('nombre');
        $element->setValue($value);
    }
    
    public function setDescripcion($value)
    {
        $element = $this->getElement('descripcion');
        $element->setValue($value);
    }
    
    public function setDisabledModulo($value)
    {
        /*$element = $this->getElement('modulo');
        $element->setValue($value);
        $element->setAttrib('disabled', 'true');*/
        $this->removeElement('modulo');
        $txtIdModulo = new Zend_Form_Element_Hidden('modulo');
        $txtIdModulo->setValue($value);
        $this->addElement($txtIdModulo);
    }
    
    public function setCheckboxState($id, $value=0)
    {
        $element = $this->getElement('chkActivo'.$id);
        $element->setChecked(($value==1?true:false));
    }
}
