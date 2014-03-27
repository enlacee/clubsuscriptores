<?php

/**
 * Description of Categoria
 *
 * @author Usuario
 */
class Application_Form_Usuario extends App_Form
{
    private $_idUsuario;
    private $_maxlengthEmail = '60';
    private $_maxlengthPwsd = '32';
    private $_minlengthPwsd = '6';

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
        $this->_idUsuario = $iu;
    }

    public function init()
    {
        parent::init();
        $this->setAction('/registro/registrar');

        // Clave
        $fClave = new Zend_Form_Element_Password('pswd');
        $fClave->setRequired();
        //
        $fClaveVal = new Zend_Validate_NotEmpty();
        $fClave->setAttrib('maxLength', $this->_maxlengthPwsd);
        $fClave->addValidator($fClaveVal);
        $fClaveVal = new Zend_Validate_StringLength(
            array('min' => $this->_minlengthPwsd, 'max' => $this->_maxlengthPwsd)
        );
        $fClave->addValidator($fClaveVal);
        $fClave->errMsg = '¡Usa de 6 a 32 caracteres!';
        $this->addElement($fClave);

        // Repetir Clave
        $fRClave = new Zend_Form_Element_Password('pswd2');
        $fRClave->setRequired();
        //
        $fRClave->addValidator(new App_Validate_PasswordConfirmation());
        $fRClave->errMsg = 'Las contraseñas introducidas no coinciden. Vuelve a intentarlo.';
        $this->addElement($fRClave);
    }

    public function validadorEmail($idUsuario, $tipo)
    {
        // Email
        $fEmail = new Zend_Form_Element_Text('email');
        $fEmail->setAttrib('maxLength', $this->_maxlengthEmail);
        $fEmail->setRequired();
        $fEmailVal = new Zend_Validate_EmailAddress(
            array("allow" => Zend_Validate_Hostname::ALLOW_ALL)
        );
        $fEmail->addFilter(new Zend_Filter_StringToLower());
        $fEmail->addValidator($fEmailVal, true);
        $fEmail->addValidator(new Zend_Validate_NotEmpty(), true);
        $f = "Application_Model_Usuario::validarUsuario";
        $fEmailVal = new Zend_Validate_Callback(
            array('callback' => $f, 'options' => array($idUsuario, $tipo))
        );
        $fEmail->addValidator($fEmailVal);
        $fEmail->errMsg = 'No parece ser un correo electrónico válido';
        $this->addElement($fEmail);
    }

    public static $errorsEmail = array(
        'isEmpty' => 'Campo Requerido',
        'emailAddressInvalidFormat' => 'No parece ser un correo electrónico válido',
        'callbackValue' => 'El email ya se encuentra registrado'
    );

}

