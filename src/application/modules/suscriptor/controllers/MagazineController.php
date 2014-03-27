<?php

class Suscriptor_MagazineController extends App_Controller_Action_Suscriptor
{
    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
        Zend_Layout::getMvcInstance()->setLayout('catalogo_pdf');
        $style = ".page-break{ display:block; page-break-before:always;}";
        $this->view->headStyle()->appendStyle($style);
        $alto = empty($this->config->catalogo->pagina->alto)? 1145:$this->config->catalogo->pagina->alto;
        $js = sprintf('var maximo = %s;', $alto);
        $this->view->headScript()->appendScript($js);
        $this->view->headScript()->appendFile(
            $this->mediaUrl . '/js/magazine.js'
        );
        $this->view->mediaUrl = $this->config->app->mediaUrl;
        $xCategorias = Application_Model_Beneficio::getBeneficiosInfoCatalogo();
        $this->view->categorias = $xCategorias;
        $this->view->elementsUrl = $this->config->app->elementsUrl;
//        $this->view->sufixmedium = $this->getConfig()->beneficios->logo->sufix->medium;
        $this->view->sufixmedium = $this->getConfig()->beneficios->img->medium;
        $this->view->sufixlittle = "";//$this->getConfig()->beneficios->logo->sufix->little;
    }

    public function mostrarCatalogoAction()
    {
        $id = "catalogodebeneficios.pdf";
        $enlace = ELEMENTS_ROOT . "/pdfs_tmp/" . $id;
        header("Content-Disposition: attachment; filename=" . $id . "\n\n");
        header("Content-Type: application/octet-stream");
        header("Content-Length: " . filesize($enlace));
        readfile($enlace);
        exit;
    }

}