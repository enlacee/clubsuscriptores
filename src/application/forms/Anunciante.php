<?php

class Application_Form_Anunciante extends App_Form
{
    //Max
    private $_maxlengthRazonSocial = '100';
    private $_maxlengthNumRuc = '11';
        
    public function __construct()
    {
        parent::__construct();
    }
    
    public function init()
    {
        parent::init();
        
        //Nombre
        $fNombreComercial = new Zend_Form_Element_Text('razon_social');
        $fNombreComercial->errMsg="Debe ingresar la Razon Social del establecimiento";
        $fNombreComercial->setRequired();
        $fNombreComercial->setAttrib('maxLength', $this->_maxlengthRazonSocial);
        $fNombreComercial->addValidator(
            new Zend_Validate_StringLength(
                array('min' => '1', 'max' => $this->_maxlengthRazonSocial,
                'encoding' => $this->_config->resources->view->charset)
            )
        );
        $this->addElement($fNombreComercial);
        
        //estado 
        $cboEstado = new Zend_Form_Element_Select('activo');
        $cboEstado->addMultiOption('1', 'Activo');
        $cboEstado->addMultiOption('0', 'Inactivo');
        $this->addElement($cboEstado);
        
    }
    
    public function validadorRuc($id)
    {
        //Ruc
        $fNRuc = new Zend_Form_Element_Text('ruc');
        $fNRuc->setRequired();
        $fNRuc->setAttrib('maxLength', $this->_maxlengthNumRuc);
        $fNRuc->addValidator(new Zend_Validate_NotEmpty(), true);
        $fNRuc->addValidator(new App_Validate_Ruc());

        $f = "Application_Model_Anunciante::validacionRuc";
        $fNRucVal = new Zend_Validate_Callback(
            array('callback'=>$f,'options' => array($id))
        );
        $fNRuc->addValidator($fNRucVal);
        $this->addElement($fNRuc);
    }
    
    public static $errorsRuc = array(
        'callbackValue' => 'Ruc Registrado.',
        'invalidruc'    => 'Debe ingresar un Ruc válido.',
        'isEmpty'       => 'Debe ingresar Ruc de 11 Digitos.',
        'notdigits'     => 'Debe ingresar Numeros.',
        'not11digits'   => 'Debe ingresar Ruc de 11 Digitos.'
    );
    
}

