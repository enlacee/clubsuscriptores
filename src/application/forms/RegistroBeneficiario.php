<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of RegistroBeneficiario
 *
 * @author Computer
 */
class Application_Form_RegistroBeneficiario extends App_Form
{
    private $_maxlengthNombre = '45';
    private $_maxlengthApellido = '75';
    private $_maxlengthInvitacion = '250';
    private $_maxlengthTipoDocDni = '8';
    private $_maxlengthTipoDocCe = '15';
    private $_maxlengthTipoDocRuc = '11'; //Max CEX
    private $_maxlengthTipoDocPas = '11'; //Max CEX
    //@codingStandardsIgnoreStart
    public static $valorDocumento;
    //@codingStandardsIgnoreEnd
    private $_id;

    public function __construct()
    {
        $keyDni = 'DNI#' . $this->_maxlengthTipoDocDni;
        $keyCe = 'CEX#' . $this->_maxlengthTipoDocCe;
        $keyRuc = 'RUC#' . $this->_maxlengthTipoDocRuc;
        $keyPas = 'PAS#' . $this->_maxlengthTipoDocPas;
        self::$valorDocumento = array(
            $keyDni => 'Documento Nacional de Identidad',
            $keyCe => 'Carnet de Extranjería',
            $keyRuc => 'RUC',
            $keyPas => 'Pasaporte'
        );
        parent::__construct();
    }

    public function init()
    {
        parent::init();

        // Nombres //
        $fNames = new Zend_Form_Element_Text('nombres');
        $fNames->setAttrib('maxLength', $this->_maxlengthNombre);
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
        $this->addElement($fNames);

        // Apellidos //
        $fApellidoPaterno = new Zend_Form_Element_Text('apellido_paterno');
        $fApellidoPaterno->setAttrib('maxLength', $this->_maxlengthApellido);
        $fApellidoPaterno->addValidator(
            new Zend_Validate_StringLength(
                array(
                    'min' => '0', 'max' => $this->_maxlengthApellido
                )
            )
        );
        $fApellidoPaterno->addValidator(
            new Zend_Validate_Alpha(true)
        );
        $this->addElement($fApellidoPaterno);
        $fApellidoMaterno = new Zend_Form_Element_Text('apellido_materno');
        $fApellidoMaterno->setAttrib('maxLength', $this->_maxlengthApellido);
        $fApellidoMaterno->addValidator(
            new Zend_Validate_StringLength(
                array(
                    'min' => '0', 'max' => $this->_maxlengthApellido
                )
            )
        );
        $fApellidoMaterno->addValidator(
            new Zend_Validate_Alpha(true)
        );
        $this->addElement($fApellidoMaterno);
        
        
        $eInvitacion = new Zend_Form_Element_Textarea('invitacion');
        $eInvitacion->setAttrib('maxLength', $this->_maxlengthInvitacion);
        $eInvitacion->addValidator(
            new Zend_Validate_StringLength(
                array(
                    'min' => '0', 'max' => $this->_maxlengthInvitacion
                )
            )
        );
        $this->addElement($eInvitacion);

        // Sexo //
        $fSexo = new Zend_Form_Element_Radio('sexoMF');
        $fSexo->addMultiOption("M", "Masculino");
        $fSexo->addMultiOption("F", "Femenino");
        $fSexo->setValue("M");
        $fSexoVal = new Zend_Validate_NotEmpty(); //Validador
        $fSexo->addValidator($fSexoVal);
        $fSexo->errMsg = 'Campo Requerido';
        $fSexo->setSeparator('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
        $this->addElement($fSexo);

        // Combo Tipo Documento
        $fSelDoc = new Zend_Form_Element_Select('tipo_documento');
        $fSelDoc->addMultiOptions(self::$valorDocumento);
        $fSelDocVal = new Zend_Validate_InArray(array_keys(self::$valorDocumento));
        $fSelDoc->addValidator($fSelDocVal);
        $this->addElement($fSelDoc);

        // Numero Documento //
        $fNDoc = new Zend_Form_Element_Text('numero_documento');
        $fNDoc->setAttrib('maxLength', $this->_maxlengthTipoDocDni);
        $fNDocVal = new Zend_Validate_StringLength(
            array(
                'min' => $this->_maxlengthTipoDocDni, 'max' => $this->_maxlengthTipoDocDni
            )
        );
        $fNDoc->addValidator($fNDocVal);
        $this->addElement($fNDoc);

        // Fecha de Nacimiento //
        $fBirthDate = new Zend_Form_Element_Hidden('fecha_nacimiento');
        $validador = new Zend_Validate_Callback(
            function($date) {
                $now = new Zend_Date();
                $bd = new Zend_Date($date);
                return $bd->isEarlier($now);
            }
        );
        $fBirthDate->errMsg = 'Fecha invalida';
        $fBirthDate->addValidator($validador, true);
        $this->addElement($fBirthDate);
    }

    public function validarEmailInvitado($email)
    {
        // Email Usuario //
        $fEmail = new Zend_Form_Element_Text('email');
        $fEmail->setAttrib('maxLength', $this->_maxlengthEmail);
        $fEmail->setRequired();
        $fEmailVal = new Zend_Validate_EmailAddress(
            array("allow" => Zend_Validate_Hostname::ALLOW_ALL)
        );

        $fEmail->addFilter(new Zend_Filter_StringToLower());
        $fEmail->addValidator($fEmailVal, true);
        $fEmail->addValidator(new Zend_Validate_NotEmpty(), true);

        $f = "Application_Model_Suscriptor::validarInvitado";
        $fEmailVal = new Zend_Validate_Callback(
            array('callback' => $f, 'options' => array($email))
        );
//        $fEmail->addValidator($fEmailVal);
        $fEmail->errMsg = 'No parece ser un correo electrónico válido';
        $this->addElement($fEmail);
    }
}
