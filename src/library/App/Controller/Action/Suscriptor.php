<?php

class App_Controller_Action_Suscriptor extends App_Controller_Action
{
    const MENU_INICIO = 'inicio';
    const MENU_NAME_INICIO = 'inicio';
    const MENU_NAME_BENEFICIO = 'beneficio';
    const MENU_NAME_VIDA_SOCIAL = 'vida_social';
    const MENU_NAME_SUSCRIPCIONES = 'servicios_al_suscriptor';
    const MENU_NAME_MI_CUENTA = 'mi_cuenta';
    const MENU_NAME_CONTACTO = 'contacto';

    const MENU_NAME_SERVICIOS_AL_SUSCRIPTOR = 'servicios_al_suscriptor';

    const MENU_POST_MI_CUENTA = 'mi_cuenta';
    const MENU_POST_DATOS_PERSONALES = 'mis_datos';

    const MENU_POST_SIDE_MIS_DATOS = 'mis_datos';
    const MENU_POST_SIDE_CAMBIOCLAVE = 'cambio_clave';
    const MENU_POST_SIDE_REDESSOCIALES = 'redes_sociales';
    const MENU_POST_SIDE_ALERTAS = 'alertas';

    const MENU_POST_SIDE_MIS_CONSUMOS = 'mis_consumos';
    const MENU_POST_SIDE_MIS_BENEFICIARIOS = 'mis_beneficiarios';
    const MENU_POST_SIDE_CONTACTO = 'contacto';
    public function init()
    {
        parent::init();
        $config = $this->getConfig();

        Zend_Layout::getMvcInstance()->assign(
            'AppFacebook', $config->apis->facebook
        );

        Zend_Layout::getMvcInstance()->assign(
            'urlAuthAppFacebook', $config->app->siteUrl . '/auth/validacion-facebook'
        );
        Zend_Layout::getMvcInstance()->assign(
            'urlAuthAppGoogle', sprintf(
                $config->apis->google->openidUrl, 
                $config->app->siteUrl . $config->apis->google->returnUrlAuth, 
                $config->app->siteUrl
            )
        );
        Zend_Layout::getMvcInstance()->assign(
            'recuperarClaveForm', Application_Form_RecuperarClave::factory(
                Application_Form_Login::ROL_SUSCRIPTOR
            )
        );

        Zend_Layout::getMvcInstance()->assign(
            'loginForm', Application_Form_Login::factory(
                Application_Form_Login::ROL_SUSCRIPTOR
            )
        );

        $this->view->flashMessages = $this->_flashMessenger;

        Zend_Layout::getMvcInstance()->categorias 
            = Application_Model_Categoria::getCategoriasBeneficioVigente();
        $promos = new Application_Model_Beneficio();
        Zend_Layout::getMvcInstance()->promos_acogidas = $promos->getPromosAcogidas();

        Zend_Layout::getMvcInstance()->tipos_beneficio 
            = Application_Model_TipoBeneficio::getTiposBeneficio(true, false);
        
        Zend_Layout::getMvcInstance()->promos_mas_consumidas = $promos->getPromosMasConsumidas();
        
        $idSorteosDisponibles=$this->getConfig()->categoria_id->sorteo->disponible;
        Zend_Layout::getMvcInstance()->sorteos_disponibles 
            = Application_Model_Beneficio::getBeneficiosSorteosDisponibles($idSorteosDisponibles);
        $idConcurso=$this->getConfig()->tipo_beneficio_id->concurso;
        Zend_Layout::getMvcInstance()->zona_concurso 
            = Application_Model_Beneficio::getBeneficiosTipoConcursos($idConcurso);
        Zend_Layout::getMvcInstance()->tipos_beneficio_activoPublicado
            = Application_Model_Beneficio::getBeneficiosActivosByTipoConcursos();
        
    }

}