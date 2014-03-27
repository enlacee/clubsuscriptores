<?php

class Application_Form_Establecimiento extends App_Form
{
    private $_idUsuario;
    protected $_listaRubro;
    protected $_listaDistrito;
    
    //Max
    private $_maxlengthNombreC = '56';
    private $_maxlengthDireccion = '150';
    private $_maxlengthNumRuc = '11';
    private $_minlengthTelefono = '5';
    private $_maxlengthTelefono = '20';
    private $_maxlengthEmail = '60';
    
    public function setIdUsuario($iu)
    {
        $this->_idUsuario = $iu;
    }
    
    public function getIdUsuario()
    {
        return $this->_idUsuario;
    }
    
    public function __construct($iu)
    {
        parent::__construct();
        $this->_idUsuario =$iu; 
    }
    
    
    public function init()
    {
        parent::init();
        
        //Nombre
        $fNombreComercial = new Zend_Form_Element_Text('nombre');
        $fNombreComercial->errMsg="Debe ingresar un nombre del establecimiento";
        $fNombreComercial->setRequired();
        $fNombreComercial->setAttrib('maxLength', $this->_maxlengthNombreC);
        $fNombreComercial->addValidator(
            new Zend_Validate_StringLength(
                array('min' => '1', 'max' => $this->_maxlengthNombreC,
                'encoding' => $this->_config->resources->view->charset)
            )
        );
        $this->addElement($fNombreComercial);
        
        //Contacto
        $fContacto = new Zend_Form_Element_Text('contacto');
        $fContacto->errMsg="Debe ingresar un Nombre del Contacto";
        $fContacto->setRequired();
        $fContacto->setAttrib('maxLength', $this->_maxlengthNombreC);
        $fContacto->addValidator(
            new Zend_Validate_StringLength(
                array('min' => '1', 'max' => $this->_maxlengthNombreC,
                'encoding' => $this->_config->resources->view->charset)
            )
        );
        $this->addElement($fContacto);
        
        //Contacto
        $fDireccion = new Zend_Form_Element_Text('direccion');
        $fDireccion->errMsg = "Debe ingresar la dirección del establecimiento";
        $fDireccion->setRequired();
        $fDireccion->setAttrib('maxLength', $this->_maxlengthDireccion);
        $fDireccion->addValidator(
            new Zend_Validate_StringLength(
                array('min' => '1', 'max' => $this->_maxlengthDireccion,
                    'encoding' => $this->_config->resources->view->charset)
            )
        );
        $this->addElement($fDireccion);
        
        //Email
        $fEmailContacto = new Zend_Form_Element_Text('email_contacto');
        $fEmailContacto->setAttrib('maxLength', $this->_maxlengthEmail);
        $fEmailVal = new Zend_Validate_EmailAddress(
            array("allow" => Zend_Validate_Hostname::ALLOW_ALL)
        );
        $fEmailContacto->setRequired();
        $fEmailContacto->addValidator(new Zend_Validate_NotEmpty(), true);
        $fEmailContacto->addFilter(new Zend_Filter_StringToLower());
        $fEmailContacto->addValidator($fEmailVal, true);
        $fEmailContacto->errMsg = 'No parece ser un correo electrónico válido';
        $this->addElement($fEmailContacto);
        
        
        // Telefono Fijo/Cel
        $fTlfFC = new Zend_Form_Element_Text('telefono_contacto');
        $fTlfFC->setRequired();
        $fTlfFC->setAttrib('maxLength', $this->_maxlengthTelefono);
        $fTlfFC->addValidator(
            new Zend_Validate_StringLength(
                array(
                    'min' => $this->_minlengthTelefono, 
                    'max' => $this->_maxlengthTelefono
                )
            )
        );
        $fTlfFCVal = new Zend_Validate_NotEmpty();
        $fTlfFC->addValidator($fTlfFCVal);
        $fTlfFC->errMsg = 'Campo Requerido';
        $this->addElement($fTlfFC);
        
        //estado 
        $cboEstado = new Zend_Form_Element_Select('activo');
        $cboEstado->addMultiOption('1', 'Activo');
        $cboEstado->addMultiOption('0', 'Inactivo');
        $this->addElement($cboEstado);
        
        //tipo Establecimiento 
        $modelTipoEstablecimiento = new Application_Model_TipoEstablecimiento();
        $arrayTipoestablecimiento = $modelTipoEstablecimiento->getAllTipoEstablecimiento(true);
        $cboEstable = new Zend_Form_Element_Select('tipo_establecimiento');
        $cboEstable->addMultiOption('', 'Selecciona tipo de establecimiento');
        $cboEstable->addMultiOptions($arrayTipoestablecimiento);
        $cboEstableVal = new Zend_Validate_InArray(array_keys($arrayTipoestablecimiento));
        $cboEstable->addValidator($cboEstableVal);
        $cboEstable->errMsg = "Seleccione tipo de establecimiento.";
        $this->addElement($cboEstable);
        
        //Logotipo
        $fLogo = new Zend_Form_Element_File('path_imagen');
        $fLogo->setDestination($this->_config->paths->elementsEstablecimientoRoot);
        $fLogo->addValidator(
            new Zend_Validate_File_Size(array('max'=>$this->_config->app->maxSizeFile))
        );
        $fLogo->addValidator('Extension', false, 'jpg,jpeg,png');
        $fLogo->getValidator('Size')->setMessage('Tamaño de Imagen debe ser mejor a 2Mb');
        $fLogo->getValidator('Extension')
            ->setMessage('Seleccione un archivo con extensión .jpg,.jpeg,.png');
        $fLogo->addValidator('Count', false, array('min' =>1, 'max' => 1));
        $fLogo->getValidator('Count')
            ->setMessage('Seleccione un archivo');
        $this->addElement($fLogo);
    }
    
    public function validadorRuc($id)
    {
        //Ruc
        $fNRuc = new Zend_Form_Element_Text('RUC');
        $fNRuc->setRequired();
        $fNRuc->setAttrib('maxLength', $this->_maxlengthNumRuc);
        $fNRuc->addValidator(new Zend_Validate_NotEmpty(), true);
        $fNRuc->addValidator(new App_Validate_Ruc());

        $f = "Application_Model_Establecimiento::validacionRuc";
        $fNRucVal = new Zend_Validate_Callback(
            array('callback'=>$f,'options' => array($id))
        );
        $fNRuc->addValidator($fNRucVal);
        $this->addElement($fNRuc);
    }
    
    public function setValuesEdit()
    {
        $this->removeElement('nombre');
        $this->removeElement('tipo_establecimiento');
        $this->removeElement('activo');
    }
    
    public static $errorsRuc = array(
        'callbackValue' => 'Ruc Registrado.',
        'invalidruc' => 'Debe ingresar un Ruc válido.',
        'isEmpty' => 'Debe ingresar Ruc de 11 Digitos.',
        'notdigits' => 'Debe ingresar Numeros.',
        'not11digits'=> 'Debe ingresar Ruc de 11 Digitos.'
    );
    
    public static $errorsEmail = array(
        'isEmpty' => 'Campo Requerido.',
        'emailAddressInvalidFormat' => 'No parece ser un correo electrónico válido.',
        'callbackValue' => 'El email ya se encuentra registrado.',
        'emailAddressInvalidHostname' => 'No parece ser un correo electrónico válido.',
        
    );
    
}

