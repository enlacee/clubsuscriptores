<?php

class Gestor_InicioController extends App_Controller_Action_Gestor
{
    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
        Zend_Layout::getMvcInstance()->active = App_Controller_Action_Gestor::MENU_NAME_INICIO;
    }
    
}

