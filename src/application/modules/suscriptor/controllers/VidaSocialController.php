<?php

class Suscriptor_VidaSocialController extends App_Controller_Action_Suscriptor
{

    public function init()
    {
        parent::init();
        $this->view->headScript()->appendFile(
            $this->getConfig()->app->mediaUrl . '/js/compartir.mail.js'
        );
        
        $this->_articulo = new Application_Model_Articulo();
        $this->_galeriaImagenes = new Application_Model_GaleriaImagenes();
        
        $compartirArticulo = new Application_Form_CompartirPorMail();
        $compartirArticulo->setAction($this->view->url(array(), 'compartir_mail', true));
        if (!empty($this->auth['suscriptor'])) {
            $compartirArticulo->correoEmisor->setValue($this->auth['usuario']->email);
            $compartirArticulo->nombreEmisor->setValue(
                $this->auth['suscriptor']['nombres'].' '.
                $this->auth['suscriptor']['apellido_paterno']. ' ' .
                $this->auth['suscriptor']['apellido_materno']
            );
        }
        Zend_Layout::getMvcInstance()->assign(
            'compartirPorMail',
            $compartirArticulo
        );
    }

    public function indexAction()
    {
        Zend_Layout::getMvcInstance()->active =
            App_Controller_Action_Suscriptor::MENU_NAME_VIDA_SOCIAL;
        $this->view->headScript()->appendFile(
            $this->getConfig()->app->mediaUrl . '/js/vida_social.js'
        );
        
        $this->view->headTitle()->set('Vida Social del Club De Suscriptores El Comercio Perú');
        $this->view->headMeta()->setName("description", "Entérate de los acontecimientos en Vida Social 
            del Club De Suscriptores El Comercio Perú");
        $this->view->headMeta()->setName("keywords", "Vida Social, beneficios de el comercio, 
            Club De Suscriptores El Comercio Perú");
        
        $this->_helper->Widgets->Encuesta($this);
        
        $page = $this->_getParam('page', 1);
        $nropaginasarticulos = $this->getConfig()->articulos->nropaginas;
        $zp = Zend_Paginator::factory($this->_articulo->getArticulos());
        $paginator = $zp->setItemCountPerPage($nropaginasarticulos);
        
        $this->view->mostrando = "Mostrando "
            . $paginator->getItemCount($paginator->getItemsByPage($page)) . " de "
            . $paginator->getTotalItemCount();
        $this->view->totalitems = $paginator->getItemCount($paginator->getItemsByPage($page));
        $paginator->setCurrentPageNumber($page);
        $this->view->articulos = $paginator;
        
        $this->view->showarticulo = $this->_getParam('id');
        $this->view->indice = $this->getRequest()->getPost('imagen', '');
        
        $followus = array(
            'twitter' => $this->getConfig()->followus->cuenta->twitter,
            'facebook' => $this->getConfig()->followus->cuenta->facebook,
            'youtube' => $this->getConfig()->followus->cuenta->youtube,
            'googleplus' => $this->getConfig()->followus->cuenta->googleplus
        );
        $this->view->cuentas = $followus;
    }
    
    public function detailArticuloAction()
    {
        $this->_helper->layout->disableLayout();
        
        $id = $this->getRequest()->getPost('id', '');
        
        $this->view->articulo = $this->_articulo->getArticulo($id);
        $this->view->imagenes = $this->_galeriaImagenes->getImagenesArticulo($id);
        $mainImg = $this->_galeriaImagenes->getImagenPrincipal($id);
        $this->view->principal = $mainImg;
        $this->view->indice = $mainImg['orden'];
        $this->view->imgindice = $mainImg['path_imagen'];
        $this->view->sufix = $this->getConfig()->galeria->sufix->little;
    }
    
    public function compartirMailAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        
        $dataPost = $this->_getAllParams();
        if ($dataPost == null) {
            $this->_redirect('/');
        }
        //var_dump($dataPost); exit;
        $this->_helper->Mail->compartirArticulo(
            array(
                'to' => $dataPost['correoReceptor'],
                'nombreReceptor' => $dataPost['nombreReceptor'],
                'nombreEmisor' => $dataPost['nombreEmisor'],
                'mensajeCompartir' => $dataPost['mensajeCompartir'],
                'avisoUrl' => SITE_URL.$dataPost['hdnOculto']
            )
        );
        
        $response = array(
            'status' => 'ok',
            'msg' => 'Se envio el correo'
        );
        $this->_response->appendBody(Zend_Json::encode($response));
    }
}