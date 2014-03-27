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
class Application_Form_FiltroConsumos extends App_Form
{
    public function init()
    {
        parent::init();
        
        $eEstablecimientos = new Zend_Form_Element_Select('establecimiento');
        $eEstablecimientos->addMultiOption(0, 'Todos');
        $eEstablecimientos->addMultiOptions(
            Application_Model_Establecimiento::
            getEstablecimientos(array('estado' => 1), true)
        );
        $this->addElement($eEstablecimientos);
        
        $this->addElement(
            'text', 'nombre_promo', array(
                'label' => 'Promoción :'
                //,'value' => 'Escriba una promo'
            )
        );
        $this->getElement('nombre_promo')->setAttrib('maxLength', 100);
        //$this->getElement('nombre_promo')->addDecorator('Label', array('class' => 'moveL'));
        
        $selectEstado = new Zend_Form_Element_Select('estado');
        $selectEstado->setLabel('Situación :');
        $selectEstado->addMultiOption('', '.:: Todos ::.');
        $selectEstado->addMultiOptions(Application_Model_Cupon::getEstadosCupon());
        //$selectEstado->addDecorator('Label', array('class' => 'moveL'));
        $this->addElement($selectEstado);
        
        $this->addElement(
            'text', 'fecha_emision_desde', array(
                'label' => 'Consumido Del :'
                //,'class' => 'moveL'
            )
        );
        //$this->getElement('fecha_emision_desde')
        //->addDecorator('Label', array('class' => 'moveL'));
        /*$this->getElement('fecha_emision_desde')->addDecorator('HtmlTag', 
            array('tag' => 'dd','class'=>'moveL')
        );*/
        //var_dump($this->getElement('fecha_emision_desde')
        //->getDecorator('HtmlTag')->getOption('tag')); exit;
        $this->addElement(
            'text', 'fecha_emision_hasta', array(
                'label' => 'Al :'
            )
        );
        //$this->getElement('fecha_emision_hasta')
        //->addDecorator('Label', array('class' => 'moveL'));
        
        $selectTipoDocumento = new Zend_Form_Element_Select('tipo_documento');
        $selectTipoDocumento->setLabel('Documento :');
        $selectTipoDocumento->addMultiOption('', 'Escoger');
        $selectTipoDocumento->addMultiOptions(
            Application_Model_Suscriptor::getTiposDocumentoSuscriptor()
        );
        $this->addElement($selectTipoDocumento);
        
        $this->addElement(
            'text', 'nro_documento', array(
                'label' => ''
            )
        );
        $this->addElement(
            'text', 'nombre_suscriptor', array(
                'label' => 'Nombre'
            )
        );
        
        $this->addElement(
            'button', 'buscar', array(
                'id' => "btnSearchConsume",
                'Label'=> ''
            )
        );
        
        //$this->setElementDecorators(array('ViewHelper', 'Label', 'HtmlTag'));
        //$this->getElement('nombre_promo')
        //->addDecorator('HtmlTag',array('tag'=>'div', 'class'=>'moveL','openOnly'=>true));
        //$this->getElement('fecha_emision_hasta')
        //->addDecorator('HtmlTag',array('tag'=>'div', 'closeOnly'=>true));
        
        $this->setAction('');
        $this->setMethod('post');
        $this->setAttribs(array('id'=>'frmRptConsumos','name'=>'frmRptConsumos'));
    }
}