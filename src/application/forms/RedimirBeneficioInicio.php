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
class Application_Form_RedimirBeneficioInicio extends App_Form
{
    private $_maxlengthTipoDocDni = '8'; //Max DNI
    private $_maxlengthTipoDocCe = '15'; //Max CEX
    private $_maxlengthTipoDocRuc = '11'; //Max CEX
    private $_maxlengthTipoDocPas = '11'; //Max CEX
    private $_maxlengthNumCupon = '45';
    public static $valorDocumento;

    public function __construct()
    {
        parent::__construct();

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

        // Numero Documento
        $fNDoc = new Zend_Form_Element_Text('numero_documento');
        $fNDoc->setAttrib('maxLength', $this->_maxlengthTipoDocDni);
        $fNDocVal = new Zend_Validate_StringLength(
            array(
                'min' => $this->_maxlengthTipoDocDni, 'max' => $this->_maxlengthTipoDocDni
            )
        );
        $fNDoc->addValidator($fNDocVal);
        $this->addElement($fNDoc);

        // Numero Cupon
        $fNCupon = new Zend_Form_Element_Text('numero_cupon');
        $fNCupon->setAttrib('maxLength', $this->_maxlengthNumCupon);
        $fNCuponVal = new Zend_Validate_StringLength(
            array(
                'max' => $this->_maxlengthNumCupon
            )
        );
        $fNCupon->addValidator($fNCuponVal);
        $this->addElement($fNCupon);
    }

    public function addTipoDocumento()
    {
        $fSelDoc = new Zend_Form_Element_Select('tipodoc');
        $fSelDoc->setRequired();
        $fSelDoc->addMultiOptions(self::$valorDocumento);
        $fSelDocVal = new Zend_Validate_InArray(array_keys(self::$valorDocumento));
        $fSelDoc->addValidator($fSelDocVal);
        $this->addElement($fSelDoc);
    }

}
