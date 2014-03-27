<?php

/**
 * Description of Categoria
 *
 * @author Usuario
 */
class Application_Form_Suscriptor extends App_Form
{
    //Max/Min Length
    private $_maxlengthNombre = '45';
    private $_maxlengthApellido = '75';
    private $_maxlengthTelefono = '30'; //telefono
    private $_minlengthTelefono = '6';
    private $_maxlengthTipoDocDni = '8';//Max DNI
    private $_maxlengthTipoDocCe = '15';//Max CEX
    private $_maxlengthTipoDocRuc = '11';//Max CEX
    private $_maxlengthTipoDocPas = '11';//Max CEX
    private $_maxlengthCodigo = '10';
    private $_maxlengthEmail = '60';
    //@codingStandardsIgnoreStart
    public static $valorDocumento;
    //@codingStandardsIgnoreEnd
    private $_id;

    public function setId($i)
    {
        $this->_id = $i;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function __construct($i)
    {
        parent::__construct();
        $this->_id = $i;

        $keyDni = 'DNI#' . $this->_maxlengthTipoDocDni;
        $keyCe = 'CEX#' . $this->_maxlengthTipoDocCe;
        $keyRuc = 'RUC#' . $this->_maxlengthTipoDocRuc;
        $keyPas = 'PAS#' . $this->_maxlengthTipoDocPas;

        self::$valorDocumento = array(
            $keyDni => 'DNI',
            $keyCe => 'Carnet de Extranjería',
            $keyRuc => 'RUC',
            $keyPas => 'Pasaporte'
        );
    }

    public function init()
    {
        parent::init();

        $fEmailContacto = new Zend_Form_Element_Text('email_contacto');
        $fEmailVal = new Zend_Validate_EmailAddress(
            array("allow" => Zend_Validate_Hostname::ALLOW_ALL)
        );
        $fEmailContacto->setRequired(false);
        $fEmailContacto->addFilter(new Zend_Filter_StringToLower());
        $fEmailContacto->addValidator($fEmailVal, true);
        $fEmailContacto->setAttrib('maxLength', $this->_maxlengthEmail);
        $fEmailContacto->errMsg = 'No parece ser un correo electrónico válido';
        $this->addElement($fEmailContacto);

        // Nombre
        $fNames = new Zend_Form_Element_Text('nombres');
        $fNames->setAttrib('maxLength', $this->_maxlengthNombre);
        $fNames->addValidator(
            new Zend_Validate_StringLength(
                array('min' => '1', 'max' => $this->_maxlengthNombre)
            )
        );

        $e = new Zend_Form_Element_Hidden('es_suscriptor');
        $e->setValue(0);
        $this->addElement($e);

        $fNames->setRequired();
        $fNamesVal = new Zend_Validate_NotEmpty();
        $fNames->addValidator($fNamesVal);
//        $fNames->errMsg = '¡Se requiere su nombre!';
        $fNames->addValidator(
            new Zend_Validate_Alpha(true)
        );
        $fNames->errMsg = '¡Se requiere su nombre correcto!';
        $this->addElement($fNames);

        // Apellido
        $fApePat = new Zend_Form_Element_Text('apellido_paterno');
        $fApePat->setRequired();
        $fApePat->setAttrib('maxLength', $this->_maxlengthApellido);
        $fApePat->addValidator(
            new Zend_Validate_StringLength(array('min' => '1', 'max' => $this->_maxlengthNombre))
        );
        $fApePatVal = new Zend_Validate_NotEmpty();
        $fApePat->addValidator($fApePatVal);
//        $fApePat->errMsg = '¡Se requiere su apellido paterno!';
        $fApePat->addValidator(
            new Zend_Validate_Alpha(true)
        );
        $fApePat->errMsg = '¡Se requiere su apellido paterno correcto!';
        $this->addElement($fApePat);
        $fApeMat = new Zend_Form_Element_Text('apellido_materno');
        $fApeMat->setRequired();
        $fApeMat->setAttrib('maxLength', $this->_maxlengthApellido);
        $fApeMat->addValidator(
            new Zend_Validate_StringLength(array('min' => '1', 'max' => $this->_maxlengthNombre))
        );
        $fApeMatVal = new Zend_Validate_NotEmpty();
        $fApeMat->addValidator($fApeMatVal);
//        $fApeMat->errMsg = '¡Se requiere su apellido materno!';
        $fApeMat->addValidator(
            new Zend_Validate_Alpha(true)
        );
        $fApeMat->errMsg = '¡Se requiere su apellido materno correcto!';
        $this->addElement($fApeMat);

        // Fecha
        $fBirthDate = new Zend_Form_Element_Hidden('fecha_nacimiento');
//        $fBirthDate->setRequired();
        //
        $fBirthDateVal = new Zend_Validate_NotEmpty();
        $fBirthDate->addValidator($fBirthDateVal, true);
        //$fBirthDate->errMsg = 'Campo Requerido';
        $validador = new Zend_Validate_Callback(
            function($date) {
                $now = new Zend_Date();
                $bd = new Zend_Date($date);
                return $bd->isEarlier($now);
            }
        );
        $fBirthDate->addValidator($validador, true);
        $this->addElement($fBirthDate);

        // Sexo
        $fSexo = new Zend_Form_Element_Radio('sexoMF');
        $fSexo->addMultiOption("M", "Masculino");
        $fSexo->addMultiOption("F", "Femenino");
        $fSexo->setValue("M");

//        $fSexoVal = new Zend_Validate_NotEmpty();
//        $fSexo->addValidator($fSexoVal);
//        $fSexo->errMsg = 'Campo Requerido';
        $fSexo->setSeparator('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
        $this->addElement($fSexo);

        // Telefono Fijo/Cel
        $fTlfFC = new Zend_Form_Element_Text('telefono');
        $fTlfFC->setRequired();
        //
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

//        $tipo_documento_suscriptor = new Zend_Form_Element_Select('tipo_documento_suscriptor');
//        $this->addElement($tipo_documento_suscriptor);
//
//        $eDoc = new Zend_Form_Element_Text('documento_suscriptor');
//        $this->addElement($eDoc);
        
        $eDistrito = new Zend_Form_Element_Select('distrito_entrega');
        $eDistrito->addMultiOption('0', '-- Seleccione un Distrito --');
        $eDistrito->addMultiOptions(Application_Model_DistritoEntrega::getDistritosEntrega(true));
        $this->addElement($eDistrito);
    }

    public function isValid($data)
    {
        if (isset($data['tipo_documento'])) {
            if ($data['tipo_documento'] == 'DNI#' . $this->_maxlengthTipoDocDni) {
                // @codingStandardsIgnoreStart
                $this->numero_documento->addValidator(
                    new Zend_Validate_StringLength(
                        array('min' => $this->_maxlengthTipoDocDni,
                            'max' => $this->_maxlengthTipoDocDni
                        )
                    )
                );
                // @codingStandardsIgnoreEnd
            } elseif ($data['tipo_documento'] == 'CEX#' . $this->_maxlengthTipoDocCe) {
                // @codingStandardsIgnoreStart
                $this->numero_documento->addValidator(
                    new Zend_Validate_StringLength(
                        array('min' => $this->_maxlengthTipoDocCe,
                            'max' => $this->_maxlengthTipoDocCe
                        )
                    )
                );

                // @codingStandardsIgnoreEnd
            } elseif ($data['tipo_documento'] == 'RUC#' . $this->_maxlengthTipoDocRuc) {
                // @codingStandardsIgnoreStart
                $this->numero_documento->addValidator(
                    new Zend_Validate_StringLength(
                        array('min' => $this->_maxlengthTipoDocRuc,
                            'max' => $this->_maxlengthTipoDocRuc
                        )
                    )
                );

                // @codingStandardsIgnoreEnd
            } elseif ($data['tipo_documento'] == 'PAS#' . $this->_maxlengthTipoDocPas) {
                // @codingStandardsIgnoreStart
                $this->numero_documento->addValidator(
                    new Zend_Validate_StringLength(
                        array('min' => 6,
                            'max' => $this->_maxlengthTipoDocPas
                        )
                    )
                );

                // @codingStandardsIgnoreEnd
            }
        }
        return parent::isValid($data);
    }

    public function validadorNumDoc($id)
    {

        // Combo Documento
        $fSelDoc = new Zend_Form_Element_Select('tipo_documento');
        $fSelDoc->setRequired();
        $fSelDoc->addMultiOptions(self::$valorDocumento);
        $fSelDocVal = new Zend_Validate_InArray(array_keys(self::$valorDocumento));
        $fSelDoc->addValidator($fSelDocVal);
        $this->addElement($fSelDoc);

        $fNDoc = new Zend_Form_Element_Text('numero_documento');
        $fNDoc->setRequired();

        $fNDoc->addValidator(new Zend_Validate_NotEmpty(), true);
        $fNDoc->setAttrib('maxLength', $this->_maxlengthTipoDocDni);
        $fNDocVal = new Zend_Validate_StringLength(
            array(
                'min' => $this->_maxlengthTipoDocDni,
                'max' => $this->_maxlengthTipoDocDni
            )
        );
        $fNDoc->addValidator($fNDocVal);

        $f = "Application_Model_Suscriptor::validacionDocumento";
        $fNDocVal = new Zend_Validate_Callback(
            array('callback' => $f, 'options' => array($fSelDoc, $id))
        );
        $fNDoc->addValidator($fNDocVal);

        $this->addElement($fNDoc);
    }

    public function validadorCodigoSuscriptor($id)
    {
        //TODO agregar validadores
        $fCodSus = new Zend_Form_Element_Text('codigo_suscriptor');
        $fCodSus->setAttrib('maxLength', $this->_maxlengthCodigo);
//    $fCodSus->setRequired();
        $f = "Application_Model_Suscriptor::validacionCodigoSuscriptor";
        $fCodSusVal = new Zend_Validate_Callback(
            array('callback' => $f, 'options' => array($id))
        );
        $fCodSus->addValidator($fCodSusVal);

        $this->addElement($fCodSus);
    }
    
    public function setElementsDisabled($value = 0)
    {
        if ($value) {
            $this->getElement('nombres')->setAttrib('disabled', 'disabled');
            $this->getElement('email_contacto')->setAttrib('disabled', 'disabled');
            $this->getElement('apellido_paterno')->setAttrib('disabled', 'disabled');
            $this->getElement('apellido_materno')->setAttrib('disabled', 'disabled');
            $this->getElement('tipo_documento')->setAttrib('disabled', 'disabled');
            $this->getElement('numero_documento')->setAttrib('disabled', 'disabled');
            $this->getElement('sexoMF')->setAttrib('disabled', 'disabled');
            $this->getElement('telefono')->setAttrib('disabled', 'disabled');
        }
    }
    
    public function docSuscriptor()
    {

        // Combo Documento
        $fSelDoc = new Zend_Form_Element_Select('tipo_documento_suscriptor');
        $fSelDoc->addMultiOptions(self::$valorDocumento);
        $fSelDocVal = new Zend_Validate_InArray(array_keys(self::$valorDocumento));
        $fSelDoc->addValidator($fSelDocVal);
        $this->addElement($fSelDoc);
        $fNDoc = new Zend_Form_Element_Text('documento_suscriptor');

        $fNDoc->setAttrib('maxLength', $this->_maxlengthTipoDocDni);

        $this->addElement($fNDoc);
    }
    
    public function disableElementensWhenSuscriptor()
    {
        $this->getElement('tipo_documento')->setAttrib('disabled', true);
        $this->getElement('numero_documento')->setAttrib('disabled', true);
        $this->getElement('nombres')->setAttrib('disabled', true);
        $this->getElement('apellido_paterno')->setAttrib('disabled', true);
        $this->getElement('apellido_materno')->setAttrib('disabled', true);
        $this->getElement('sexoMF')->setAttrib('disabled', true);
        $this->getElement('telefono')->setAttrib('disabled', true);
    }

    //Errores Estaticos
    public static $errors = array(
        'isEmpty' => 'Campo Requerido',
        'stringLengthTooShort' => 'Documento inválido',
        'stringLengthTooLong' => 'Documento inválido',
        'callbackValue' => 'El Número del documento ya se encuentra registrado'
    );
    public static $errorsFechaNac = array(
        'callbackValue' => 'Ingrese una fecha válida.',
        'isEmpty' => 'Campo Requerido.',
    );

}

