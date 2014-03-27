<?php

class Admin_InicioController extends App_Controller_Action_Admin
{
    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
        Zend_Layout::getMvcInstance()->active = App_Controller_Action_Admin::MENU_NAME_INICIO;
    }
    
}

