<?php

/**
 * Description of Categoria
 *
 * @author Dennis Pozo
 */
class Application_Form_CambioClave extends App_Form
{
    private $_maxlengthEmail = '60';
    private $_idUsuario;
    
    public function setIdUsuario($iu)
    {
        $this->_idUsuario = $iu;
    }
    
    public function getIdUsuario()
    {
        return $this->_idUsuario;
    }
    
    public function __construct($iu='')
    {
        parent::__construct();
        $this->_idUsuario =$iu;
    }
    
    public function init()
    {
        parent::init();

        // Nueva Clave
        $fNewClave = new Zend_Form_Element_Password('pswd');
        $fNewClave->setRequired();
        $fValidClaveMaxMin = 
            new Zend_Validate_StringLength(array('min' => 6, 'max' => 32));
        $fNewClave->addValidator($fValidClaveMaxMin);
        $fNewClave->errMsg = '¡Usa de 6 a 32 caracteres!';
        $this->addElement($fNewClave);

        // Repetir Clave
        $fConfirmClave = new Zend_Form_Element_Password('pswd2');
        $fConfirmClave->setRequired();
        $fValidClaveMaxMin = new Zend_Validate_StringLength(
            array('min' => 6, 'max' => 32)
        );
        $fConfirmClave->addValidator(
            new App_Validate_PasswordConfirmation(
                array('match-field' => 'pswd')
            )
        );
        $fConfirmClave->errMsg =
            'Las contraseñas introducidas no coinciden. Vuelve a intentarlo.';
        $this->addElement($fConfirmClave);
    }
    
    public function validarPswd($emailUsuario)
    {
        // Clave Anterior
        $fClaveAnterior = new Zend_Form_Element_Password('oldpswd');
        $fClaveAnterior->setRequired();
        $fClaveAnterior->addValidator(new Zend_Validate_NotEmpty(), true);
        $f = "Application_Model_Usuario::validacionPswd";
        $options = array(
            $emailUsuario,
            Application_Form_Login::ROL_SUSCRIPTOR
        );
        $fClaveAnteriorVal = new Zend_Validate_Callback(
            array('callback'=>$f,'options' => $options)
        );
        $fClaveAnterior->addValidator($fClaveAnteriorVal);
        $fClaveAnterior->errMsg = 
        'La contraseña proporcionada no coincide con la actual.';
        $this->addElement($fClaveAnterior);
    }
    
    public function validarEstPswd($emailUsuario)
    {
        $fClaveAnterior = new Zend_Form_Element_Password('oldpswd');
        $fClaveAnterior->setRequired();
        $fClaveAnterior->addValidator(new Zend_Validate_NotEmpty(), true);
        $f = "Application_Model_Usuario::validacionPswd";
        $options = array(
            $emailUsuario,
            Application_Form_Login::ROL_ESTABLECIMIENTO
        );
        $fClaveAnteriorVal = new Zend_Validate_Callback(
            array('callback'=>$f,'options' => $options)
        );
        $fClaveAnterior->addValidator($fClaveAnteriorVal);
        $fClaveAnterior->errMsg = 
        'La contraseña proporcionada no coincide con la actual.';
        $this->addElement($fClaveAnterior);
    }
    
    public function validarAllPswd($emailUsuario,$rol = Application_Form_Login::ROL_ADMIN)
    {
        $fClaveAnterior = new Zend_Form_Element_Password('oldpswd');
        $fClaveAnterior->setRequired();
        $fClaveAnterior->addValidator(new Zend_Validate_NotEmpty(), true);
        $f = "Application_Model_Usuario::validacionPswd";
        $options = array(
            $emailUsuario,
            $rol
        );
        $fClaveAnteriorVal = new Zend_Validate_Callback(
            array('callback'=>$f,'options' => $options)
        );
        $fClaveAnterior->addValidator($fClaveAnteriorVal);
        $fClaveAnterior->errMsg = 
        'La contraseña proporcionada no coincide con la actual.';
        $this->addElement($fClaveAnterior);
    }
    
    public function addOldPswd()
    {
        $fClaveAnterior = new Zend_Form_Element_Password('oldpswd');
        $fClaveAnterior->setRequired();
        $fClaveAnterior->addValidator(new Zend_Validate_NotEmpty(), true);
        $this->addElement($fClaveAnterior);
    }
    
    public function addComboRolUser()
    {
        $cboTipoUser = new Zend_Form_Element_Select('cborol');
        $cboTipoUser->addMultiOption(0, 'Usuario del Portal');
        $cboTipoUser->addMultiOptions(
            Application_Model_Perfil::getPerfilesUsuario(1)
        );
        $this->addElement($cboTipoUser);
    }
    
    public function addComboSubRolUser()
    {
        $cboTipoUser = new Zend_Form_Element_Select('cbosubrol');
        $cboTipoUser->addMultiOption('', 'Seleccione...');
        $cboTipoUser->addMultiOptions(
            Application_Model_Perfil::getPerfilesUsuario(2)
        );
        $this->addElement($cboTipoUser);
    }    
    
    public function addEmailUser()
    {
        $txtEmail = new Zend_Form_Element_Text('txtemail');
        $txtEmail->setAttrib('maxLength', $this->_maxlengthEmail);
        $eVal = new Zend_Validate_EmailAddress(
            array("allow" => Zend_Validate_Hostname::ALLOW_ALL)
        );
        $txtEmail->addFilter(new Zend_Filter_StringToLower());
        $txtEmail->addValidator($eVal, true);
        $txtEmail->setRequired(true);
        $txtEmail->errMsg = 'No parece ser un correo electrónico válido';
        $this->addElement($txtEmail);
    }
}

