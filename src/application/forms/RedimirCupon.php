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
class Application_Form_RedimirCupon extends App_Form
{
    private $_maxlengthMonto = '10';
    private $_maxlengthNumCupon = '45';
    private $_maxlengthNro = '2';
    private $_nrCuponGen = '5';
    
    public function getNr_cuponGen()
    {
        return $this->_nrCuponGen;
    }

    public function setNr_cuponGen($_nrCuponGen)
    {
        $this->_nrCuponGen = $_nrCuponGen;
    }
    
    public function init()
    {
        parent::init();

        // monto
        $fNDoc = new Zend_Form_Element_Text('monto1');
        $fNDoc->addValidator(new Zend_Validate_Float(new Zend_Locale('es_PE')));
        $fNDoc->setAttrib('maxLength', $this->_maxlengthMonto);
        //$fNDoc->setRequired();
        $fNDocVal = new Zend_Validate_StringLength(
            array(
                'max' => $this->_maxlengthMonto
            )
        );
        $fNDoc->addValidator($fNDocVal);
        $fNDoc->errMsg = 'Ingrese monto';
        $this->addElement($fNDoc);

        // Numero Cupon
        $fNCupon = new Zend_Form_Element_Text('numero_cupon1');
        $fNCupon->setAttrib('maxLength', $this->_maxlengthNumCupon);
        //$fNCupon->setRequired();
        $fNCuponVal = new Zend_Validate_StringLength(
            array(
                'max' => $this->_maxlengthNumCupon
            )
        );
        $fNCupon->addValidator($fNCuponVal);
        $fNCupon->errMsg = 'Ingrese número de cupón';
        $this->addElement($fNCupon);
    }
    
    public function addNroCuponesGen()
    {
        // Numero de Cupones
        $nroCupon = new Zend_Form_Element_Text('nrocupones');
        $nroCupon->setAttrib('maxLength', $this->_maxlengthNro);
        $nroCupon->setRequired();
        $nroCuponVal = new Zend_Validate_StringLength(
            array(
                'max' => $this->_maxlengthNro
            )
        );
        $nroCupon->addValidator($nroCuponVal);
        $nroCupon->errMsg = 'Ingrese cantidad';
        $nroCupon->setValue(1);
        $this->addElement($nroCupon);
    }
    
    public function addMontoCupon()
    {
        for ($ind=1; $ind<=$this->_nrCuponGen; $ind++) {
            $txtMonto = new Zend_Form_Element_Text('monto'.$ind);
            $txtMonto->setRequired();
            $txtMonto->errMsg = 'Campo Requerido';
            $this->addElement($txtMonto);
        }
    }
    
    public function addTipoPromocionCupon($options = array())
    {
        for ($ind=1; $ind<=$this->_nrCuponGen; $ind++) {
            $cboTipo = new Zend_Form_Element_Select('cbotipo'.$ind);
            $cboTipo->addMultiOption('', '.:Seleccione:.');
            $cboTipo->addMultiOptions($options);
            $cboTipo->setRequired();
            $cboTipo->errMsg = 'Campo Requerido';
            $this->addElement($cboTipo);
        }
    }
    
    public function addNumeroCupon()
    {
        for ($ind=1; $ind<=$this->_nrCuponGen; $ind++) {
            $txtNumCupon = new Zend_Form_Element_Text('numero_cupon'.$ind);
            //$txtNumCupon->setRequired();
            $txtNumCupon->errMsg = 'Campo Requerido';
            $this->addElement($txtNumCupon);
        }
    }
}
