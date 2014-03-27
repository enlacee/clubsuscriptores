<?php

class Application_Form_SuscribirCategorias extends App_Form
{
    private $_listaCategorias;

//  public function __construct()
//  {
//    //;
//  }

    public function init()
    {
        parent::init();
//        $categorias = new Application_Model_Categoria();
        $this->_listaCategorias 
            = Application_Model_Categoria::getCategorias(true, array('activo' => 1));

        $c = new Zend_Form_Element_Select('categoria_1');
        $c->addMultiOption('-1', 'Selecciona una categoría');
        $c->addMultiOptions($this->_listaCategorias);
        $this->addElement($c);
        $h = new Zend_Form_Element_Hidden('id_1');
        $this->addElement($h);


        $c = new Zend_Form_Element_Select('categoria_2');
        $c->addMultiOption('-1', 'Selecciona una categoría');
        $c->addMultiOptions($this->_listaCategorias);
        $this->addElement($c);
        $h = new Zend_Form_Element_Hidden('id_2');
        $this->addElement($h);

        $c = new Zend_Form_Element_Select('categoria_3');
        $c->addMultiOption('-1', 'Selecciona una categoría');
        $c->addMultiOptions($this->_listaCategorias);
        $this->addElement($c);
        $h = new Zend_Form_Element_Hidden('id_3');
        $this->addElement($h);

        $e = new Zend_Form_Element_Checkbox('enviar_alertas_email');
        $this->addElement($e);
    }

}
