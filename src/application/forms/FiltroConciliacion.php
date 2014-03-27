<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FiltroConsumos
 *
 * @author Usuario
 */
class Application_Form_FiltroConciliacion extends App_Form
{
    
    public function init()
    {
        parent::init();
        
        $this->setConfig($this->_config->frmsgestor->filtros->conciliacion);
        
        $selectEstable = new Zend_Form_Element_Select('establecimiento');
        //$selectEstable->setLabel('Establicimiento ');
        $selectEstable->addMultiOption('', '.:: Seleccionar Establecimiento ::.');
        $selectEstable->addMultiOptions(
            Application_Model_Establecimiento::getEstablecimientos(array(), true)
        );
        $this->addElement($selectEstable);
        
        $this->addElement(
            'text', 'fecha_consumo_desde', array(
                //'label' => 'Consumido Del :'
            )
        );
        $this->addElement(
            'text', 'fecha_consumo_hasta', array(
                //'label' => 'Al :'
            )
        );
        
        $selectPromo = new Zend_Form_Element_Select('promocion');
        $selectPromo->setLabel('Promoción ');
        $selectPromo->addMultiOption('', '.:: Todas ::.');
        $this->addElement($selectPromo);
        
        $this->addElement(
            'button', 'buscar', array(
                'id' => "btnSearchConcilia",
                'Label'=> ''
            )
        );
        
        $this->setAction('');
        $this->setMethod('post');
        $this->setAttribs(array('id'=>'','name'=>''));
    }
    
    public function llenarCombos($idE="")
    {
        $item = $this->getElement("establecimiento");
        $item->addMultiOptions(
            Application_Model_Establecimiento::getEstablecimientos(array('estado' => 1), true)
        );

        $item = $this->getElement("promociones");
        $item->addMultiOptions(
            Application_Model_Beneficio::getBeneficiosxEstablecimiento($idE)
        );
    }
}