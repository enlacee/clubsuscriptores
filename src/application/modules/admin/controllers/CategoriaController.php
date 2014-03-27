<?php

class Admin_CategoriaController extends App_Controller_Action_Admin
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $_categoria = new Application_Model_Categoria();
        $this->view->categorias = $_categoria->fetchAll();
        
    }

    public function nuevoAction()
    {
        $form = new Application_Form_Categoria();
        $_categoria = new Application_Model_Categoria();
        $filterSlug = new App_Filter_Slug();
        
        if ($this->_request->isPost() && $form->isValid($this->_getAllParams()) ) {
            $values = $form->getValues();
            $values['slug'] = $filterSlug->filter($values['nombre']);
            $_categoria->insert($values);
            $this->_redirect($this->view->url(array('action'=>'index')));
        }
        
        $this->view->form = $form;
    }
    
    public function editarAction()
    {
    }

    public function activarAction()
    {
    }

    public function desactivarAction()
    {
    }

    public function borrarAction()
    {
    }

}

