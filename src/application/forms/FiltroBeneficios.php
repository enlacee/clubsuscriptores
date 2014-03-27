<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Categoria
 *
 * @author eanaya
 */
class Application_Form_FiltroBeneficios extends Zend_Form
{
    private $_maxlenghtNombre = 75;
    private $_maxlenghtDescripcion = 75;
    
    public function init()
    {
        parent::init();
        
        $eBusqueda = new Zend_Form_Element_Text('nombre');
        $v = new Zend_Validate_StringLength(array('min'=>3,'max'=>20));
        $eBusqueda->addValidator($v);
        $f = new Zend_Filter_StringTrim();
        $eBusqueda->addFilter($f);
        $eBusqueda->setAttrib('maxLength', $this->_maxlenghtNombre);
        $this->addElement($eBusqueda);
        
        $eTipoBeneficio = new Zend_Form_Element_Select('tipo_beneficio');
        $eTipoBeneficio->addMultiOption(0, 'Todos');
        $eTipoBeneficio
            ->addMultiOptions(Application_Model_TipoBeneficio::getTiposBeneficio(true, true));
        $this->addElement($eTipoBeneficio);
        
        $eEstablecimientos = new Zend_Form_Element_Select('establecimiento');
        $eEstablecimientos->addMultiOption(0, 'Todos');
        $eEstablecimientos->addMultiOptions(
            Application_Model_Establecimiento::
            getEstablecimientos(array('estado' => 1), true)
        );
        $this->addElement($eEstablecimientos);
        
        $eCategorias = new Zend_Form_Element_Select('categoria');
        $eCategorias->addMultiOption(0, 'Todas');
        $eCategorias->addMultiOptions(
            Application_Model_Categoria::getCategorias(true, array('activo' => 1))
        );
        $this->addElement($eCategorias);
        
        // ==================================================
        $txtDescrip = new Zend_Form_Element_Text('descripcion');
        //$txtDescrip->setLabel('Descripción: ');
        $txtDescrip->setAttrib('maxLength', $this->_maxlenghtDescripcion);
        $this->addElement($txtDescrip);

        $cboEstado = new Zend_Form_Element_Select('estado');
        $this->addElement($cboEstado);
        
        $cboVigencia = new Zend_Form_Element_Select('vigencia');
        //$cboEstado->setLabel('Estado: ');
        $cboVigencia->addMultiOption('', 'Todos');
        $cboVigencia->addMultiOption('1', 'Vigentes');
        $cboVigencia->addMultiOption('0', 'No vigentes');
        $this->addElement($cboVigencia);

        $this->addElement(
            'button', 'buscar', array(
                'id' => "btnSearchBenef",
                'Label'=>''
            )
        );
        
        $this->setAction('');
        $this->setMethod('post');
        $this->setAttribs(array('id'=>'frmBeneficios','name'=>'frmBeneficios'));
    }
    
    public function addOptionsEstadoBenefiosEstablecimiento()
    {
        $cboEstado = $this->getElement('estado');
        //$cboEstado->setLabel('Estado: ');
        $cboEstado->addMultiOption('1', 'Activos');
        $cboEstado->addMultiOption('0', 'Inactivos');
        $cboEstado->addMultiOption('', 'Todos');
    }
    
    public function addOptionsEstadoListaBeneficios()
    {
        $cboEstado = $this->getElement('estado');
        $cboEstado->addMultiOption('0', 'Todos');
        $cboEstado->addMultiOption('1', 'Pendiente');
        $cboEstado->addMultiOption('2', 'Borrador');
        $cboEstado->addMultiOption('3', 'Publicado');
        $cboEstado->addMultiOption('4', 'Vencido');
    }
}