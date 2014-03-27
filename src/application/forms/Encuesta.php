<?php

class Application_Form_Encuesta extends App_Form
{
    //Max Length
    private $_maxlengthNombre = '80';
    private $_maxlengthPregunta = '100';
    private $_minlengthPregunta = '10';
    private $_nrOpciones = '5';
    
    public function getNr_opciones()
    {
        return $this->_nrOpciones;
    }

    public function setNr_opciones($_nrOpciones)
    {
        $this->_nrOpciones = $_nrOpciones;
    }
    
    public function init()
    {
        parent::init();
        
        $txtPregunta = new Zend_Form_Element_Text('pregunta');
        $txtPregunta->setRequired();
        $txtPregunta->setAttrib('maxLength', $this->_maxlengthPregunta);
        $txtPregunta->addValidator(
            new Zend_Validate_StringLength(
                array('min'=> $this->_minlengthPregunta, 'max'=> $this->_maxlengthPregunta)
            )
        );
        $txtPregunta->addValidator(new Zend_Validate_NotEmpty());
        $txtPregunta->errMsg = 'Campo Requerido';
        $this->addElement($txtPregunta);
        
        /*$txtOpcion = new Zend_Form_Element_Text('opcion');
        //$txtPregunta->setRequired();
//        $txtOpcion->setAttrib('maxLength', $this->_maxlengthNombre);
//        $txtOpcion->addValidator(
//            new Zend_Validate_StringLength(
//                array('min'=> $this->_minlengthPregunta, 'max'=> $this->_maxlengthNombre)
//            )
//        );
        $txtOpcion->errMsg = 'Campo Requerido';
        $this->addElement($txtOpcion);*/
        
        /*$txtIdOp = new Zend_Form_Element_Hidden('idop');
        $this->addElement($txtIdOp);*/
        
        $btnGuardar = new Zend_Form_Element_Submit('btnGuardarEG');
        $btnGuardar->setLabel('');
        $this->addElement($btnGuardar);
        
        $this->setAction('');
        $this->setMethod('post');
    }
    
    public function addElementsOpcion()
    {
        for ($ind=1; $ind<=$this->_nrOpciones; $ind++) {
            $txtOpcion = new Zend_Form_Element_Text('opcion'.$ind);
            $txtOpcion->errMsg = 'Campo Requerido';
            $this->addElement($txtOpcion);
            $txtIdOp = new Zend_Form_Element_Hidden('idop'.$ind);
            $this->addElement($txtIdOp);
        }
    }
    
    public function addElementPublicar()
    {
        $chkPublicar = new Zend_Form_Element_Checkbox('activo');
        $chkPublicar->setCheckedValue('1');
        $chkPublicar->setUncheckedValue('0');
        $chkPublicar->setChecked(false);
        //$chkLeerCondi->setRequired();
        //$chkLeerCondi->errMsg = 'Debe Aceptar las condiciones';
        $this->addElement($chkPublicar);
    }
    
    public function addElementNombre()
    {
        $txtNombre = new Zend_Form_Element_Text('nombre');
        $txtNombre->setRequired();
        //$txtNombre->addFilter(new Zend_Filter_StringTrim());
        //$txtNombre->addFilter(new Zend_Filter_StringToLower());
        $txtNombre->setAttrib('maxLength', $this->_maxlengthNombre);
        /*$txtNombre->addValidator(
            new Zend_Validate_StringLength(array('min'=>4,'max'=> $this->_maxlengthTituloConsulta))
        );*/
        $txtNombre->addValidator(new Zend_Validate_NotEmpty());
        $txtNombre->errMsg = 'Campo Requerido';
        $this->addElement($txtNombre);
    }
    
    public function setPregunta($value)
    {
        $element = $this->getElement('pregunta');
        $element->setValue($value);
    }
    
    public function setNombre($value)
    {
        $element = $this->getElement('nombre');
        $element->setValue($value);
    }
    
    public function setReadOnly()
    {
        //$this->getElement('nombre')->setAttrib('readonly', 'true');
        $this->getElement('pregunta')->setAttrib('readonly', 'true');
        for ($ind=1; $ind<=$this->_nrOpciones; $ind++) {
            $this->getElement('opcion'.$ind)->setAttrib('readonly', 'true');
        }
    }
}
