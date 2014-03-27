<?php

/**
 * Description of Categoria
 *
 * @author Usuario
 */
class Application_Form_BeneficioUsuario extends App_Form
{
    private $_idUsuario;
    private $_maxlengthEmail = '32';
    private $_maxlengthNombre = '75';
    private $_maxlengthApellido = '75';

    
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

        
        // Email Usuario //
        $fEmail = new Zend_Form_Element_Text('email');
        $fEmail->setAttrib('maxLength', $this->_maxlengthEmail);
        $fEmail->setRequired();
        $fEmailVal = new Zend_Validate_EmailAddress(
            array("allow" => Zend_Validate_Hostname::ALLOW_ALL)
        );
        $fEmail->errMsg = 'Ingrese email';
        $this->addElement($fEmail);
        
        // Nombres //
        $fNames = new Zend_Form_Element_Text('nombres');
        $fNames->setAttrib('maxLength', $this->_maxlengthNombre);
        $fNames->setRequired();
        $fNames->addValidator(
            new Zend_Validate_StringLength(
                array(
                    'min' => '0', 'max' => $this->_maxlengthNombre
                )
            )
        );
        $fNames->addValidator(
            new Zend_Validate_Alpha(true)
        );
        $fNames->errMsg = 'Ingrese Nombre correcto';
        $this->addElement($fNames);

        // Apellidos
        $fApellidoPaterno = new Zend_Form_Element_Text('apellido_paterno');
        $fApellidoPaterno->setAttrib('maxLength', $this->_maxlengthApellido);
        $fApellidoPaterno->setRequired();
        $fApellidoPaterno->addValidator(
            new Zend_Validate_StringLength(
                array(
                    'min' => '0', 'max' => $this->_maxlengthNombre
                )
            )
        );
        $fApellidoPaterno->addValidator(
            new Zend_Validate_Alpha(true)
        );
        $fApellidoPaterno->errMsg = 'Ingrese el apellido paterno correcto';
        $this->addElement($fApellidoPaterno);
        
        $fApellidoMaterno = new Zend_Form_Element_Text('apellido_materno');
        $fApellidoMaterno->setAttrib('maxLength', $this->_maxlengthApellido);
        $fApellidoMaterno->setRequired();
        $fApellidoMaterno->addValidator(
            new Zend_Validate_StringLength(
                array(
                    'min' => '0', 'max' => $this->_maxlengthNombre
                )
            )
        );
        $fApellidoMaterno->addValidator(
            new Zend_Validate_Alpha(true)
        );
        $fApellidoMaterno->errMsg = 'Ingrese el apellido materno correcto';
        $this->addElement($fApellidoMaterno);
        //
        $cboEstado = new Zend_Form_Element_Select('activo');
        $cboEstado->addMultiOption('0', 'Inactivo');
        $cboEstado->addMultiOption('1', 'Activo');
        $this->addElement($cboEstado);
    }
    
    public function validadorEmail($idUsuario, $rol = Application_Form_Login::ROL_SUSCRIPTOR)
    {
        // Email
        $fEmail = new Zend_Form_Element_Text('email');
        $fEmail->setAttrib('maxLength', $this->_maxlengthEmail);
        $fEmail->setRequired();
        $fEmailVal = new Zend_Validate_EmailAddress(
            array("allow"=>Zend_Validate_Hostname::ALLOW_ALL),
            true
        );
        $fEmail->addFilter(new Zend_Filter_StringToLower());
        $fEmail->addValidator($fEmailVal, true);
        $fEmail->addValidator(new Zend_Validate_NotEmpty(), true);
        $f = "Application_Model_Usuario::validarUsuario";
        $fEmailVal = new Zend_Validate_Callback(
            array('callback'=>$f,'options' => array($idUsuario, $rol))
        );
        $fEmail->addValidator($fEmailVal);
        $fRClave->errMsg = 'No parece ser un correo electrónico válido';
        $this->addElement($fEmail);
        
    }
    public static $errorsEmail = array(
        'isEmpty' => 'Campo Requerido',
        'emailAddressInvalidFormat' => 'No parece ser un correo electrónico válido',
        'callbackValue' => 'El email ya se encuentra registrado'
    );

}

