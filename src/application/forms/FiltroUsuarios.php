<?php

/**
 * Description of FiltroConsumos
 *
 * @author Solman
 */
class Application_Form_FiltroUsuarios extends App_Form
{
    private $_idEstablecimiento = '';

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function init()
    {
        parent::init();

        $roles = array(
//            "suscriptor" => "Suscriptor",
            "gestor" => "Gestor",
            "establecimiento" => "Establecimiento",
            "admin" => "Administrador"
        );

        $activo = array(
            "1" => "Activo",
            "0" => "Inactivo"
        );

        $selectEstablecimiento = new Zend_Form_Element_Select('establecimiento');
        $selectEstablecimiento->addMultiOption('', '.:: Todos ::.');
        $selectEstablecimiento->addMultiOptions(
            Application_Model_Establecimiento::getEstablecimientos(array('estado' => 1), true)
        );

        $this->addElement($selectEstablecimiento);
        
        $selectPerfil = new Zend_Form_Element_Select('perfil');
        $selectPerfil->addMultiOption('Todos', '.::Todos::.');
        $selectPerfil->addMultiOptions(
            Application_Model_Perfil::getPerfilesUsuario(1)
        );
        $this->addElement($selectPerfil);
        
        $selectSubPerfil = new Zend_Form_Element_Select('subPerfil');
        $selectSubPerfil->addMultiOption('', '.::Todos::.');
        $selectSubPerfil->addMultiOptions(
            Application_Model_Perfil::getPerfilesUsuario(2)
        );
        $this->addElement($selectSubPerfil);

        $selectEstado = new Zend_Form_Element_Select('estado');
        $selectEstado->addMultiOption('', '.:: Todos ::.');
        $selectEstado->addMultiOptions($activo);
        $this->addElement($selectEstado);
    }

    public function setIdEstablecimiento($id)
    {
        $e = $this->getElement("establecimiento");
        $e->setValue($id);
    }

    public function setPerfil($p)
    {
        $e = $this->getElement("perfil");
        $e->setValue($p);
    }
    
    public function setSubPerfil($p)
    {
        $e = $this->getElement("subPerfil");
        $e->setValue($p);
    }

    public function setEstado($est)
    {
        $e = $this->getElement("estado");
        $e->setValue($est);
    }
    
    public function setPerfilSuscriptor()
    {
        $this->getElement('perfil')->addMultiOption('S', 'Suscriptor');
    }

}