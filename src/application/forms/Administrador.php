<?php

/**
 * Descripcion del formulario recuperar clave
 *
 * @author Jesus Fabian
 */
class Application_Form_Administrador extends App_Form
{
    private $_perfiles = array(
        'gestor' => 'Gestor del Portal',
        'establecimiento' => 'Administrador de Establecimiento',
        'admin' => 'Administrador del Portal'
    );
    private $_estado = array(
        1 => 'Activo',
        0 => 'Inactivo'
    );
    private $_maxlengthNombre = '45';
    private $_maxlengthApellido = '75';
    private $_maxlengthEmail = '60';
    private $_id;
    public static $valorDocumento;
    private $_maxlengthTipoDocDni = '8';
    private $_maxlengthTipoDocCe = '15';
    private $_maxlengthTipoDocRuc = '11'; //Max CEX
    private $_maxlengthTipoDocPas = '11'; //Max CEX

    public function __construct($i)
    {
        parent::__construct();
        $this->_id = $i;

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
    }

    public function init()
    {
        parent::init();

        $e = new Zend_Form_Element_Text('nombres');
        $e->setAttrib('maxLength', $this->_maxlengthNombre);
        $e->setRequired(true);
        $this->addElement($e);

        $e = new Zend_Form_Element_Text('apellido_paterno');
        $e->setAttrib('maxLength', $this->_maxlengthApellido);
        $e->setRequired(true);
        $this->addElement($e);
        $e = new Zend_Form_Element_Text('apellido_materno');
        $e->setAttrib('maxLength', $this->_maxlengthApellido);
        $e->setRequired(true);
        $this->addElement($e);

        $e = new Zend_Form_Element_Text('email');
        $e->setAttrib('maxLength', $this->_maxlengthEmail);
        $eVal = new Zend_Validate_EmailAddress(
            array("allow" => Zend_Validate_Hostname::ALLOW_ALL)
        );
        $e->addFilter(new Zend_Filter_StringToLower());
        $e->addValidator($eVal, true);
        $e->setRequired(true);
        $this->addElement($e);

        $e = new Zend_Form_Element_Text('fecha_nacimiento');
        $this->addElement($e);

        $e = new Zend_Form_Element_Select('rol');
        $e->addMultiOption(0, '.:: Selecciones un Perfil ::.');
        //$e->addMultiOptions($this->_perfiles); //change
        $e->addMultiOptions(
            Application_Model_Perfil::getPerfilesUsuario(1)
        );
        $this->addElement($e);
        
        $e = new Zend_Form_Element_Select('subRol');
        $e->addMultiOption(0, '.:: Selecciones un Sub Perfil ::.');
        //$e->addMultiOptions($this->_perfiles); //change
        $e->addMultiOptions(
            Application_Model_Perfil::getPerfilesUsuario(2)
        );
        $this->addElement($e);

        $e = new Zend_Form_Element_Select('establecimiento');
        $e->addMultiOption(0, '.:: Seleccione un Establecimiento ::.');
        $e->addMultiOptions(
            Application_Model_Establecimiento::getEstablecimientos(array('activo' => 1), true)
        );
        $this->addElement($e);

        $e = new Zend_Form_Element_Radio('sexo');
        $e->addMultiOption("M", "Masculino");
        $e->addMultiOption("F", "Femenino");
        $e->setValue("M");
        $e->setSeparator('');
        $this->addElement($e);

        $e = new Zend_Form_Element_Select('activo');
        $e->addMultiOptions($this->_estado);
        $this->addElement($e);
        
        
        
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
//        $fNDocVal = new Zend_Validate_StringLength(
//            array(
//                'min' => $this->_maxlengthTipoDocDni,
//                'max' => $this->_maxlengthTipoDocDni
//            )
//        );
//        $fNDoc->addValidator($fNDocVal);

        $f = "Application_Model_Suscriptor::validacionDocumento";
        $fNDocVal = new Zend_Validate_Callback(
            array('callback' => $f, 'options' => array($fSelDoc, $id))
        );
        $fNDoc->addValidator($fNDocVal);

        $this->addElement($fNDoc);
    }
    
    public function setRolSuscriptor()
    {
        $this->getElement('rol')->addMultiOption('S', 'Suscriptor');
    }

}
