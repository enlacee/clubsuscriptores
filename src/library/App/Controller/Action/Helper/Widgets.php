<?php

class App_Controller_Action_Helper_Widgets extends Zend_Controller_Action_Helper_Abstract
{

    public function init()
    {
        parent::init();
    }
    
    public function Encuesta($controller)
    {
        $objencuesta = new Application_Model_Encuesta();
        $controller->view->encuesta = $objencuesta->getEncuestaActual();
        $id = $objencuesta->getId();
        $controller->view->opciones = NULL;
        if (!empty($id)) {
            $objopciones = new Application_Model_OpcionEncuesta($objencuesta);
            $controller->view->opciones = $objopciones->getOpciones();
        }
        $objrespuesta = new Application_Model_OpcionRespuesta();
        $objrespuesta->setIp_votante($this->getRequest()->getServer('REMOTE_ADDR'));
        $controller->view->existe_voto = $objrespuesta->getExisteVotacion();
    }
    
    public function Articulo($controller)
    {
        $config = Zend_Registry::get('config');
        $articulo = new Application_Model_Articulo();
        $datart = $articulo->getArticuloReciente();
        $controller->view->portada_articulo = $datart;
        $controller->view->imagenes_articulo = NULL;
        if (!empty($datart)) {
            $galeriaArticulo = new Application_Model_GaleriaImagenes();
            $controller->view->imagenes_articulo = 
                $galeriaArticulo->getImagenesArticulo($datart['id']);
            $controller->view->sufixlittle = $config->galeria->sufix->little;
        }
    }
}