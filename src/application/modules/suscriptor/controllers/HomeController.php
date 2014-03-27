<?php

class Suscriptor_HomeController extends App_Controller_Action_Suscriptor
{

    protected $_opcionRespuesta;
    protected $_fecha;

    public function init()
    {
        parent::init();
        $this->_opcionRespuesta = new Application_Model_OpcionRespuesta();
        $this->_fecha = new Zend_Date();
    }

    public function indexAction()
    {
        //OpenGraph
        $this->view->doctype(Zend_View_Helper_Doctype::XHTML1_RDFA);
        $this->view->headMeta()->setProperty('og:type', 'website');
        $this->view->headMeta()->setProperty('og:site_name', 'ClubSuscriptores.pe');
        $this->view->headMeta()->setProperty('og:title', 'ClubSuscriptores.pe');
        $this->view->headMeta()->setProperty('og:description', 'El portal exclusivo de los suscriptores de El Comercio,
            donde podrás ser parte de esta experiencia, encontrando las mejores promociones, ofertas y
            participar de todos nuestros beneficios. Club De Suscriptores El Comercio Perú.');
        $this->view->headMeta()->setProperty('og:url', 'http://ClubSuscriptores.pe/');
        $this->view->headMeta()->setProperty('og:image', $this->mediaUrl . '/images/favicon.ico');
        $this->view->headMeta()->setProperty('og:admins', '100003341577435');
        
        $this->view->headScript()->appendFile(
            $this->mediaUrl.'/js/buscador_beneficios.js'
        );
        
        Zend_Layout::getMvcInstance()->active = App_Controller_Action_Suscriptor::MENU_NAME_INICIO;
//        $this->view->headMeta()->appendName(
//            "description", "Suscriptores, " . $this->getConfig()->app->title
//        );
//        $this->view->headMeta()->appendName(
//            "keywords", ""
//        );
        $this->view->menu_sel = self::MENU_INICIO;
        
        $this->view->headTitle()->set('Portal exclusivo del Club De Suscriptores El Comercio Perú');
        $this->view->headMeta()->setName("description", "El portal exclusivo de los suscriptores de El Comercio, 
                donde podrás ser parte de esta experiencia, encontrando las mejores promociones, ofertas y participar
                de todos nuestros beneficios. Club De Suscriptores El Comercio Perú.");
        $this->view->headMeta()->setName("keywords", "club de suscriptores, suscriptores del comercio, 
            catalogo de beneficios, vida social de suscriptores de el comercio, promociones de el comercio, 
            beneficios de el comercio.");
//        $this->view->headTitle('Inicio');

        $modelBeneficio = new Application_Model_Beneficio();
        $modelArticulo = new Application_Model_Articulo();
        
        $this->view->vida_social=$modelArticulo->getVidaSocialCache();
        
        $maindes = $modelBeneficio->getMainDestacado();
        $this->view->maindestacado = $maindes;
        //sin_stock = 1 : indica si se considerara stock a la promo
//        $this->view->sinstock = empty($maindes['stock_actual']);
//        if ( !empty($maindes['sin_stock']) ) { 
//            $this->view->sinstock = false;
//        }
        
        $this->view->destacados = $modelBeneficio->getDestacadosPortada();
        $this->view->sufixdestaqprin = $this->getConfig()->beneficios->img->big;
        $this->view->sufixdestaq = $this->getConfig()->beneficios->img->medium;
        
        $followus = array(
            'twitter' => $this->getConfig()->followus->cuenta->twitter,
            'facebook' => $this->getConfig()->followus->cuenta->facebook,
            'youtube' => $this->getConfig()->followus->cuenta->youtube,
            'googleplus' => $this->getConfig()->followus->cuenta->googleplus
        );
        $this->view->cuentas = $followus;
        
        $this->view->zona_publicidad = $this->getConfig()->zona_publicidad->estado;
        //validaciones de usuario
        $this->view->user_activo = 
            (!empty($this->auth['suscriptor'])?$this->auth['suscriptor']['es_suscriptor']:0);
        //var_dump($this->auth['suscriptor']['es_suscriptor']); exit;
        $this->_helper->Widgets->Articulo($this);
        $this->_helper->Widgets->Encuesta($this);        
    }
    
    public function ingresarAction()
    {
        if ($this->isAuth && $this->auth['usuario']->rol == Application_Form_Login::ROL_SUSCRIPTOR) {
            $this->_redirect($this->siteUrl);
        } else {
            $this->view->headScript()->appendFile(
                $this->mediaUrl.'/js/ingresar.js'
            );
            $this->_forward('index');
        }
    }
    
    public function miCuentaAction()
    {
        if ($this->isAuth && $this->auth['usuario']->rol == Application_Form_Login::ROL_SUSCRIPTOR) {
            $this->_redirect($this->siteUrl.'/mi-cuenta/index');
            //$this->_helper->redirector("index", "mi-cuenta", "suscriptor");
        } else {
            $this->view->headScript()->appendFile(
                $this->mediaUrl.'/js/ingresar.js'
            );
            $this->_forward('index');
        }        
    }
    
    public function quejasReclamosAction()
    {
        if ($this->isAuth && $this->auth['usuario']->rol == Application_Form_Login::ROL_SUSCRIPTOR) {
            $this->_redirect($this->siteUrl.'/mi-cuenta/contacto/tipo/queja-reclamo');
        } else {
            $this->view->headScript()->appendFile(
                $this->mediaUrl.'/js/ingresar.js'
            );
            $this->_forward('index');
        }
        
    }
    
    public function terminosCondicionesAction()
    {
        //$this->_redirect($this->mediaUrl.'/Terminos_Condiciones_Servicio_de_Suscripcion.pdf');
        $this->view->headScript()->appendFile(
            $this->mediaUrl.'/js/terminos-condiciones.js'
        );
        $this->_forward('index');
    }

    public function verResultadoAction()
    {
        $this->_helper->layout->disableLayout();
        //$this->_helper->viewRenderer->setNoRender();
        //$envio = array('mensaje'=>'error');
        //if($this->getRequest()->isPost()){
        //$envio = array('mensaje'=>'oaa','parametro'=>$this->getRequest()->getPost('dato',''));
        //}
        //$this->_response->appendBody(Zend_Json::encode($envio));

        $id = $this->getRequest()->getPost('id', '');
        $objencuesta = new Application_Model_Encuesta();
        $objencuesta->setId($id);
        $this->view->encuesta = $objencuesta->getEncuesta();
        
        $objOpEnc = new Application_Model_OpcionEncuesta();
        $objOpEnc->setEncuesta_id($id);
        $this->view->resultado_encuesta = $objOpEnc->getResultadoEncuesta();
        $this->view->total_votos = $objOpEnc->getTotalVotosEncuesta();
        
        $this->view->opciones_mas = $objOpEnc->getOpcionesMasElegidas();
        $this->view->opciones_menos = $objOpEnc->getOpcionesMenosElegidas();
        
        //var_dump($this->view->resultado_encuesta);
        //var_dump($this->view->opciones_mas);
        //var_dump($this->view->opciones_menos);
        //exit;
        /*$objrespuesta = new Application_Model_OpcionRespuesta();
        $objrespuesta->setEncuesta_id($id);
        $this->view->resultado_encuesta = $objrespuesta->getResultadoEncuesta();
        $this->view->total_votos = $objrespuesta->getTotalVotosEncuesta();*/
        //var_dump($objencuesta->getEncuesta());
        //$this->_response->appendBody('Holas =>> '.$id);
    }

    public function contactoAction()
    {
        $this->view->headScript()->appendFile(
            $this->mediaUrl.'/js/contacto.js'
        );        
        Zend_Layout::getMvcInstance()->active = 
            App_Controller_Action_Suscriptor::MENU_NAME_CONTACTO;
        $this->view->headTitle()->prepend('Contacto');
        $this->view->headMeta()->setName("description", "Si tienes alguna duda o pregunta, o simplemente, 
            quieres brindarnos un consejo;puedes escribirnos señalando el asunto. 
            Te responderemos a la brevedad posible.Gracias! Club De Suscriptores El Comercio Perú");
        $this->view->headMeta()->setName("keywords", "Contacto, beneficios de el comercio, 
            Club De Suscriptores El Comercio Perú");
        
        $formContacto = new Application_Form_Contacto();
        $this->view->ispost = false;
        
        //$filterSlug = new App_Filter_Slug();
        if ($this->_request->isPost()) {
            $this->view->ispost = true;
            $values = $this->getRequest()->getPost();
            $formContacto->setDefaults($values);
            
            if($formContacto->isValid($values)):
                //validacion del captcha
                //var_dump($this->_getAllParams()); exit;
                $recaptcha = new Zend_Service_ReCaptcha(
                    $this->getConfig()->recaptcha->publickey,
                    $this->getConfig()->recaptcha->privatekey
                );
                $result = $recaptcha->verify(
                    $values['recaptcha_challenge_field'], 
                    $values['recaptcha_response_field']
                );
                
                if (!$result->isValid()) {
                    try {
                        //var_dump($this->_getAllParams()); exit;
                        //enviar correo
                        $this->view->ispost = false;
                        //$values['to'] = $this->auth['usuario']->email;
                        $values['to'] = $this->getConfig()->cuenta->mail->contacto;
                        //var_dump($values); exit;
                        $tipodoc = explode('#', $values['tipo_documento']);
                        $values['tipo_documento'] = 
                            ($tipodoc[0]=='CEX'?'Carnet Extranjería':$tipodoc[0]);
                        $this->_helper->Mail->contactoPortal($values);
                        $formContacto->reset();
                        $this->getMessenger()->success('Mensaje Enviado Correctamente');
                        $this->_redirect(
                            Zend_Controller_Front::getInstance()
                            ->getRequest()->getRequestUri()
                        );
                    } catch (Exception $e) {
                        $this->getMessenger()->error($e->getMessage());
                        //echo $e->getMessage();
                        $this->view->ispost = true;
                    }
                }
                
            endif;
            
        }
        
        $this->view->form_contacto = $formContacto;
    }
    
    public function condicionesAction()
    {
        $this->view->headTitle('Condiciones');
        //$controller = $this->_request->getControllerName();
    }
    
    public function preguntasFrecuentesAction()
    {
        $this->view->headTitle()->prepend('Preguntas Frecuentes');
        $this->view->headMeta()->setName("description", "Respuestas a tus preguntas para acceder a los beneficios 
            y promociones del Club De Suscriptores El Comercio Perú");
        $this->view->headMeta()->setName("keywords", "Preguntas Frecuentes, beneficios de el comercio, 
            Club De Suscriptores El Comercio Perú");
        //$controller = $this->_request->getControllerName();
    }
    
    public function suscripcionesAction()
    {
        $this->view->headScript()->appendFile(
            $this->mediaUrl.'/js/buscador_beneficios.js'
        );
        
        Zend_Layout::getMvcInstance()->active = 
            App_Controller_Action_Suscriptor::MENU_NAME_SERVICIOS_AL_SUSCRIPTOR;
        $this->view->headTitle('Suscripciones');
        //$controller = $this->_request->getControllerName();
        
        $this->_helper->Widgets->Articulo($this);
        $this->_helper->Widgets->Encuesta($this);
        
        $followus = array(
            'twitter' => $this->getConfig()->followus->cuenta->twitter,
            'facebook' => $this->getConfig()->followus->cuenta->facebook,
            'youtube' => $this->getConfig()->followus->cuenta->youtube,
            'googleplus' => $this->getConfig()->followus->cuenta->googleplus
        );
        $this->view->cuentas = $followus;
    }
    
    public function serviciosAction()
    {
        $this->view->headScript()->appendFile(
            $this->mediaUrl.'/js/buscador_beneficios.js'
        );
        
        Zend_Layout::getMvcInstance()->active = 
            App_Controller_Action_Suscriptor::MENU_NAME_SERVICIOS_AL_SUSCRIPTOR;
        $this->view->headTitle('Servicios al Suscriptor');
        //$controller = $this->_request->getControllerName();
        
        $this->_helper->Widgets->Articulo($this);
        $this->_helper->Widgets->Encuesta($this);
        
        $followus = array(
            'twitter' => $this->getConfig()->followus->cuenta->twitter,
            'facebook' => $this->getConfig()->followus->cuenta->facebook,
            'youtube' => $this->getConfig()->followus->cuenta->youtube,
            'googleplus' => $this->getConfig()->followus->cuenta->googleplus
        );
        $this->view->cuentas = $followus;
    }

    public function jsonAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $case = $this->getRequest()->getParam('case', '');
        switch ($case):
            case 'grabavoto':
                $envio = array('mensaje' => 'no grabo');
                try {
                    $db = $this->getAdapter();
                    $db->beginTransaction();
                    if ($this->getRequest()->isPost()) {
                        $objEnc = new Application_Model_Encuesta();
                        //actualizamos tambien opcion_encuesta
                        $objOpEnc = new Application_Model_OpcionEncuesta();
                        
                        $valuesencuesta['opcion_encuesta_id'] = 
                        $this->getRequest()->getPost('rbtnop', '');
                        $valuesencuesta['fecha_voto'] = 
                        $this->_fecha->toString('YYYY-MM-dd HH:mm:ss');
                        $valuesencuesta['ip_votante'] = 
                        $this->getRequest()->getServer('REMOTE_ADDR');
                        $this->_opcionRespuesta->insert($valuesencuesta);
                        
                        $objEnc->setIncrementNroRespuestasByOpEncuestaId(
                            $valuesencuesta['opcion_encuesta_id']
                        );
                        $objOpEnc->setIncrementNroRespuestasByOp(
                            $valuesencuesta['opcion_encuesta_id']
                        );
                        $db->commit();
                        $envio = array('mensaje' => 'grabado');
                        $this->_response->appendBody(Zend_Json::encode($envio));
                    }
                } catch (Exception $e) {
                    $envio = array('mensaje' => 'error :' . $e->getMessage());
                    $db->rollBack();
                    echo $e->getMessage();
                }
                break;
            default:
                break;
        endswitch;
    }
    
    public function suscribeteAction()
    {
        $this->view->headScript()->appendFile(
            $this->mediaUrl.'/js/suscribete.js'
        );        
        
        $this->view->headTitle()->prepend('Suscripción');
        $this->view->headMeta()->setName("description", "Solicita tu suscripción y accede a los beneficios y 
            promociones del Club De Suscriptores El Comercio Perú");
        $this->view->headMeta()->setName("keywords", "suscripcion, suscribete, beneficios de el comercio, 
            Club De Suscriptores El Comercio Perú");
        
        $formSolicitud = new Application_Form_Solicitudes();
        $this->view->ispost = false;
        
        //$filterSlug = new App_Filter_Slug();
        if ($this->_request->isPost()) {
            $this->view->ispost = true;
            $values = $this->getRequest()->getPost();
            $formSolicitud->setDefaults($values);
            
            if($formSolicitud->isValid($values)):
                //validacion del captcha
                //var_dump($this->_getAllParams()); exit;
                $recaptcha = new Zend_Service_ReCaptcha(
                    $this->getConfig()->recaptcha->publickey,
                    $this->getConfig()->recaptcha->privatekey
                );
                $result = $recaptcha->verify(
                    $values['recaptcha_challenge_field'], 
                    $values['recaptcha_response_field']
                );
                
                if (!$result->isValid()) {
                    try {
                        $date = date('Y-m-d H:i:s');
                        $tipodoc = explode('#', $values['tipo_documento']);
                        $solicitarSuscripcion = new Application_Model_Solicitudes();
                        $solicitarSuscripcion->insert(
                            array(
                            'nombres' => $values['nombres'],
                            'apellido_paterno' => $values['apellido_paterno'],
                            'apellido_materno' => $values['apellido_materno'],
                            'tipo_documento' => $tipodoc[0] ,
                            'numero_documento' => $values['nro_documento'],
                            'telefono' => $values['telefonos'],
                            'email' => $values['email'],
                            'mensaje' => $values['mensaje'],
                            'fecha_registro' => $date
                            )
                        );
                        //var_dump($this->_getAllParams()); exit;
                        //enviar correo
                        $values['apellidos']=$values['apellido_paterno']." ".$values['apellido_materno'];
                        $valuesDos=$values;
                        $valuesDos['to']= $values['email'];
                        $this->view->ispost = false;
                        //$values['to'] = $this->auth['usuario']->email;
                        $values['to'] = $this->getConfig()->cuenta->mail->suscribete;
                        //var_dump($values); exit;
                        $tipodoc = explode('#', $values['tipo_documento']);
                        $values['tipo_documento'] = 
                            ($tipodoc[0]=='CEX'?'Carnet Extranjería':$tipodoc[0]);
                        //$this->_helper->Mail->contactoSuscribete($values);
                        $objMail=$this->_helper->Mail;
                        $objMail->contactoSuscribete($values);
                        $objMail->contactoSuscribeteSolicitud($valuesDos);
                        $formSolicitud->reset();
                        $this->getMessenger()->success(
                            'Datos registrados correctamente, en breve un 
                                representante comercial se comunicará contigo.'
                        );
                        $this->_redirect(
                            Zend_Controller_Front::getInstance()
                            ->getRequest()->getRequestUri()
                        );
                    } catch (Exception $e) {
                        $this->getMessenger()->error($e->getMessage());
                        //echo $e->getMessage();
                        $this->view->ispost = true;
                    }
                }
                
            endif;
            
        }
        
        $this->view->form_contacto = $formSolicitud;
        //$this->view->captcha = $this->Captcha();
    }
    
    public function sitemapAction() {
        $this->_helper->layout->disableLayout();
//        $this->_helper->viewRenderer->setNoRender();
//        
//        $nav = new Zend_Navigation();
//
//        foreach (App_Model_Ubicacion::listarUrl() as $route=>$ubicacion) {
//            $nav->addPage(new Zend_Navigation_Page_Mvc(array(
//                'route' => $route,
//                'lastmod' => date('Y-m-d\TH:i:s')
//            )));
//        }
//        foreach (Application_Model_Beneficio::getBeneficioSitemap() as $data) {
//            $nav->addPage(Zend_Navigation_Page::factory(array(
//                'uri'    => SITE_URL."/".$data["slug"]."-".$data["id"],
//                'lastmod' => $data["fecha_inicio_publicacion"]//date('Y-m-d\TH:i:s')
//            )));
//        }
        $this->getResponse()->setHeader('Content-Type', 'text/xml; charset=utf-8');
//        $sitemap = $this->view->navigation()->sitemap($nav)->setFormatOutput(true);
//        $this->getResponse()->appendBody($sitemap);
    }
    
    public function sitemapBeneficiosAction() {
        $this->_helper->layout->disableLayout();
        $this->view->urls = Application_Model_Beneficio::getBeneficioSitemap();
        $this->getResponse()->setHeader('Content-Type', 'text/xml; charset=utf-8');
//        $this->_helper->viewRenderer->setNoRender();        
//        $nav = new Zend_Navigation();
//        foreach (Application_Model_Beneficio::getBeneficioSitemap() as $data) {
//            $nav->addPage(Zend_Navigation_Page::factory(array(
//                'uri'    => SITE_URL."/".$data["slug"]."-".$data["id"],
//                'lastmod' => $data["fecha_inicio_publicacion"]//date('Y-m-d\TH:i:s')
//            )));
//        }        
//        $this->getResponse()->setHeader('Content-Type', 'text/xml; charset=utf-8');
//        $sitemap = $this->view->navigation()->sitemap($nav)->setFormatOutput(true);
//        $this->getResponse()->appendBody($sitemap);
    }
    
    public function sitemapImagesAction() {
        $this->_helper->layout->disableLayout();
        $this->view->urls = Application_Model_Beneficio::getBeneficioSitemap();
        $this->getResponse()->setHeader('Content-Type', 'text/xml; charset=utf-8');
    }
    
}