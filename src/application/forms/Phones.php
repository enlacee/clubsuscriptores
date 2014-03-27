<?php

class Application_Form_Phones extends App_Form
{
    //Max Length
    private $_maxlengthPhone = '15';
    private $_minlengthPhone = '10';
    private $_nrPhones = '5';
    
    public function getNr_phones()
    {
        return $this->_nrPhones;
    }

    public function setNr_phones($_nrPhones)
    {
        $this->_nrPhones = $_nrPhones;
    }
    
    public function init()
    {
        parent::init();
        
        /*$txtPregunta->addValidator(
            new Zend_Validate_StringLength(
                array('min'=> $this->_minlengthPregunta, 'max'=> $this->_maxlengthPregunta)
            )
        );
        $txtPregunta->addValidator(new Zend_Validate_NotEmpty());*/
        
        $btnGuardar = new Zend_Form_Element_Submit('btnGuardarEG');
        $btnGuardar->setLabel('');
        $this->addElement($btnGuardar);
        
        $this->setAction('');
        $this->setMethod('post');
    }
    
    public function addElementsPhones($phones)
    {
        foreach ($phones as $ind => $phone) {
            /*$txtPhone = new Zend_Form_Element_Text('txtNumberPhone'.($ind+1));
            $txtPhone->setValue($phone['numero_telefonico']);
            $txtPhone->setAttrib('maxLength', $this->_maxlengthPhone);
            $txtPhone->errMsg = 'Campo Requerido';
            $this->addElement($txtPhone);*/
            $this->addElementPhoneNumber(($ind+1), $phone['numero_telefonico']);
            
            /*$txtIdPhone = new Zend_Form_Element_Hidden('idphone'.($ind+1));
            $txtIdPhone->setValue($phone['id']);
            $this->addElement($txtIdPhone);*/
            $this->addElementIdPhone(($ind+1), $phone['id']);
            
            /*$cboOperador = new Zend_Form_Element_Select('operador'.($ind+1));
            $cboOperador->setMultiOptions(
                Application_Model_NumeroTelefonicoEstablecimiento::getOperadores()
            );
            $cboOperador->setValue($phone['operador']);
            $this->addElement($cboOperador);*/
            $this->addElementDDLOperador(($ind+1), $phone['operador']);
            
            /*$chkActivo = new Zend_Form_Element_Checkbox('chkActivo'.($ind+1));
            $chkActivo->setCheckedValue('1');
            $chkActivo->setUncheckedValue('0');
            $chkActivo->setChecked(($phone['activo']==1?true:false));
            $this->addElement($chkActivo);*/
            $this->addElementCheckboxState(($ind+1), $phone['activo']);
        }
    }
    
    public function addElementPhoneNumber($id, $value = '')
    {
        $txtPhone = new Zend_Form_Element_Text('txtNumberPhone'.$id);
        $txtPhone->setValue($value);
        $txtPhone->setAttrib('maxLength', $this->_maxlengthPhone);
        $txtPhone->errMsg = 'Campo Requerido';
        $this->addElement($txtPhone);
    }
    
    public function addElementIdPhone($id, $value = '')
    {
        $txtIdPhone = new Zend_Form_Element_Hidden('idphone'.$id);
        $txtIdPhone->setValue($value);
        $this->addElement($txtIdPhone);
    }
    
    public function addElementDDLOperador($id, $value = '')
    {
        $cboOperador = new Zend_Form_Element_Select('operador'.$id);
        $cboOperador->setMultiOptions(
            Application_Model_NumeroTelefonicoEstablecimiento::getOperadores()
        );
        $cboOperador->setValue($value);
        $this->addElement($cboOperador);
    }
    
    public function addElementCheckboxState($id, $value=0)
    {
        $chkActivo = new Zend_Form_Element_Checkbox('chkActivo'.$id);
        $chkActivo->setCheckedValue('1');
        $chkActivo->setUncheckedValue('0');
        $chkActivo->setChecked(($value==1?true:false));
        $this->addElement($chkActivo);
    }
}
