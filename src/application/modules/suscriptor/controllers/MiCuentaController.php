<?php

class Suscriptor_MiCuentaController extends App_Controller_Action_Suscriptor
{
    protected $_suscriptor;
    protected $_usuario;
    protected $_messageSuccess = "Tus datos se actualizaron con éxito";

    public function init()
    {
        parent::init();
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_redirect('/');
        }

        /* Initialize action controller here */
        $this->_suscriptor = new Application_Model_Suscriptor();
        $this->_usuario = new Application_Model_Usuario();

        Zend_Layout::getMvcInstance()->assign(
            'bodyAttr', array('id' => 'myAccount')
        );
        if ($this->isAuth && $this->auth['usuario']->rol
            == Application_Form_Login::ROL_SUSCRIPTOR) {
            $this->idSuscriptor = $this->auth['suscriptor']['id'];
        }
        $this->usuario = $this->auth['usuario'];
        Zend_Layout::getMvcInstance()->active
            = App_Controller_Action_Suscriptor::MENU_NAME_MI_CUENTA;
    }

    public function indexAction()
    {
        $this->view->headScript()->appendFile(
            $this->mediaUrl.'/js/buscador_beneficios.js'
        );
        
        if ($this->isAuth && !empty($this->idSuscriptor)) {
            $id = $this->idSuscriptor;
            $this->_forward('mis-consumos');
//      $perfil = $this->_suscriptor->getPerfil($id);
//      $this->view->beneficiarios = $perfil['beneficiarios'];
//      exit();
        } else {
            $this->_redirect('/');
        }
    }

    public function misConsumosAction()
    {
        $this->view->headScript()->appendFile(
            $this->mediaUrl.'/js/buscador_beneficios.js'
        );
        
        $this->view->menu_sel_side = self::MENU_POST_SIDE_MIS_CONSUMOS;
        $this->view->menu_post_sel = self::MENU_POST_MI_CUENTA;

//        $objTipoBeneficio = new Application_Model_TipoBeneficio();
//        $this->view->tipo_beneficio = $objTipoBeneficio->getTiposBeneficio();
        $tipo_beneficio = Application_Model_TipoBeneficio::getTiposBeneficio(false, true);
        
        $objCupon = new Application_Model_Cupon();
        $objCupon->setSuscriptor_id($this->auth['suscriptor']['id']);
        
        $formFiltro = new Application_Form_FiltroMisConsumos();
        $formFiltro->setIdSuscriptor($this->auth['suscriptor']['id']);
        $formFiltro->add_cboAnio();
        $formFiltro->add_cboMes();
        
        $arrayTipos = $this->_getParam('chktipobenef', '');
        $tipos = '';$arrayTiposView='';
        if(!empty($arrayTipos)){
            foreach ($arrayTipos as $type):
                $tipos=$type.','.$tipos;
                $arrayTiposView[$type]=$type;
            endforeach;
            $tipos=substr($tipos, 0 ,-1);            
        }
        $page = $this->_getParam('page', 1);
        $arrayForm['cboMes']= $this->_getParam('cboMes','');
        $arrayForm['cboAnio']= $this->_getParam('cboAnio','');
        if(empty($arrayForm['cboMes'])){
            $arrayUltimoFechaRedencion=$objCupon->getObtenerUltimaFechaRedencion();
            $arrayForm['cboMes']= empty($arrayUltimoFechaRedencion)?'':$arrayUltimoFechaRedencion['mes'];
            $arrayForm['cboAnio']= empty($arrayUltimoFechaRedencion)?'':$arrayUltimoFechaRedencion['anio'];
        }
        
        $arrayForm['ord'] = $param['ord'] = $this->_getParam('ord','DESC');
        $arrayForm['col'] = $param['col'] = $this->_getParam('col','tipo');
        $param['tipos']=(empty($tipos)?Application_Model_TipoBeneficio::TIPO_ALL:$tipos);
        $param['idMes']=$arrayForm['cboMes'];
        $param['anio']=$arrayForm['cboAnio'];
//        $this->misconsumosPaginator($param); 
        $objCupon->setMesId($param['idMes']);
        $objCupon->setAnio($param['anio']);
        
        $formFiltro->setDefaults($arrayForm);
        
        $this->view->ord = $param['ord'];
        $this->view->col = $param['col'];
        $this->view->consumos = $objCupon->getMisConsumosPorTipoAndMoneda($param);
        $this->view->consumosAnual = $objCupon->getMontoPorAnio($param); 
        
        $this->view->tipo_beneficio = $tipo_beneficio;
        $this->view->arrayTiposSeleccionados = $arrayTiposView;
        $this->view->frmFiltroDate = $formFiltro;
        $this->view->nombre = 'Todos';
    }
    
    protected function misconsumosPaginator($param)
    {
//        $idtipos = $param['idTipos'];
//        $idMes=$param['idMes'];
//        $anio=$param['anio'];
//        $ord=$param['ord'];
//        $col=$param['col'];
//                
//        $this->view->headScript()->appendFile(
//            $this->mediaUrl.'/js/buscador_beneficios.js'
//        );
//
//        $objCupon = new Application_Model_Cupon();
//        $objCupon->setSuscriptor_id($this->auth['suscriptor']['id']);
//        
//        $objCupon->setMesId($idMes);
//        $objCupon->setAnio($anio);
//        $input['tipos'] = $idtipos;
//        $this->view->ord = $input['ord'] = $ord;
//        $this->view->col = $input['col'] = $col;
//        $this->view->consumos=$objCupon->getMisConsumosPorTipoAndMoneda($input);
//        $this->view->consumosAnual=$objCupon->getMontoPorAnio($input); 
        
    }

    public function misConsumosTipoAction()
    {        
//        $this->view->headScript()->appendFile(
//            $this->mediaUrl.'/js/buscador_beneficios.js'
//        );
//        
//        $this->_helper->layout->disableLayout();
//        $tipos = $this->_getParam('tipo', '');
//        $tipos = (!empty($tipos)?substr($tipos, 0, -1):$tipos);
//        $objTipoBeneficio = new Application_Model_TipoBeneficio();
//        
//        $arrayTipos = explode(',', $tipos);
//        $cadTitles = '';
//        $allflag = false;
//        foreach ($arrayTipos as $type):
//            $objTipoBeneficio->setId($type);
//            $cadTitles .= ($type == '0' ? 'Todo' : $objTipoBeneficio->getNombre()).', ';
//            $allflag = ($type == '0'?true:$allflag);
//        endforeach;
//        
//        $this->view->nombre = 
//            (( strlen($tipos)>0 && !$allflag)?
//               substr($cadTitles, 0, -2):
//               ($allflag?'Todo':'Seleccione Tipo'));
//        
//        $page = $this->_getParam('page', 1);
//        $idMes = $this->_getParam('idMes');
//        $anio = $this->_getParam('anio');
//        $param['ord'] = $this->_getParam('ord');
//        $param['col'] = $this->_getParam('col');
//        $param['idTipos']=($allflag?Application_Model_TipoBeneficio::TIPO_ALL:$tipos);
//        $param['idMes']=$idMes;
//        $param['anio']=$anio;
//        $this->misconsumosPaginator($param);
    }

    public function cambioDeClaveAction()
    {
        $this->view->headScript()->appendFile(
            $this->mediaUrl.'/js/buscador_beneficios.js'
        );
        
        $this->view->menu_sel_side = self::MENU_POST_SIDE_CAMBIOCLAVE;
        $this->view->menu_post_sel = self::MENU_POST_DATOS_PERSONALES;
        $this->view->menu_sel = self::MENU_NAME_MI_CUENTA;
        $this->view->isAuth = $this->isAuth;
        $this->view->headScript()->appendFile(
            $this->mediaUrl . '/js/cambio_clave.js'
        );

        $arraySuscriptor = $this->_suscriptor->getSuscriptor($this->idSuscriptor);
        $idUsuario = $arraySuscriptor['usuario_id'];
        $emailUsuario = $arraySuscriptor['email'];
        $formCambioClave = new Application_Form_CambioClave($idUsuario);
        $formCambioClave->validarPswd($emailUsuario);

        if ($this->_request->isPost()) {

            $allParams = $this->_getAllParams();
            $validClave = $formCambioClave->isValid($allParams);
            if ($validClave) {
                $valuesClave = $formCambioClave->getValues();
                try {
                    $db = $this->getAdapter();
                    $db->beginTransaction();

                    //Captura de los datos de usuario
                    $valuesClave['pswd'] =
                        App_Auth_Adapter_ClubDbTable::generatePassword($valuesClave['pswd']);
                    unset($valuesClave['pswd2']);
                    unset($valuesClave['oldpswd']);

                    $where = $this->_usuario->getAdapter()
                        ->quoteInto('id = ?', $idUsuario);
                    $this->_usuario->update($valuesClave, $where);
                    //actualizar club del vino  y Tiendaclub
                    $valuesData['actualizado']=1;
                    $valuesData['actualizadocv']=1;
                    $whereSuscriptor = $this->_usuario->getAdapter()
                        ->quoteInto('id = ?', $this->idSuscriptor);
                    $this->_suscriptor->update($valuesData, $whereSuscriptor);                    
                    $db->commit();

                    $this->getMessenger()->success('Se cambio la clave con éxito!');
                } catch (Exception $e) {
                    $db->rollBack();
                    $this->getMessenger()->error('Error al cambiar la clave!');
                    echo $e->getMessage();
                }
            } else {
                $this->getMessenger()->error(
                    'Hubo un error al intentar actualizar tus datos, verifica tu información'
                );
            }
        }
        $this->view->formCambioClave = $formCambioClave;
    }

    public function misBeneficiariosAction()
    {
        $p = new Plugin_CsrfProtect();
        $this->view->csrf = $p->session->key;
        
        $this->view->headScript()->appendFile(
            $this->mediaUrl.'/js/buscador_beneficios.js'
        );
        $maximoInvitados =
            empty($this->config->beneficiarios->maximoInvitados) ?
            3 : $this->config->beneficiarios->maximoInvitados;
        $nroInvitados = Application_Model_Suscriptor::getNumeroInvitadoSuscriptorPadre($this->auth['suscriptor']['id']);
        $this->view->maximoInvitados = $maximoInvitados;
        $this->view->headScript()->appendFile($this->mediaUrl . '/js/registro_invitado.js');
        $this->view->menu_post_sel = self::MENU_POST_MI_CUENTA;
        $this->view->menu_sel_side = self::MENU_POST_SIDE_MIS_BENEFICIARIOS;
        if ($this->isAuth) {
            $id = $this->idSuscriptor;
            $perfil = $this->_suscriptor->getPerfil($id);
            $this->view->beneficiarios = $perfil['beneficiarios'];
            $formBeneficiario = new Application_Form_RegistroBeneficiario();
            $this->view->formBeneficiario = $formBeneficiario;
            $formBeneficiario->validarEmailInvitado(null);
        } else {
            $this->_redirect('/');
        }
    }

    public function misDatosPersonalesAction()
    {
        $this->view->headScript()->appendFile(
            $this->mediaUrl.'/js/buscador_beneficios.js'
        );
        $this->_forward('mis-datos');
    }

    public function misDatosAction()
    {
        $this->view->headScript()->appendFile(
            $this->mediaUrl.'/js/buscador_beneficios.js'
        );
        
        $this->view->menu_sel_side = self::MENU_POST_SIDE_MIS_DATOS;
        $this->view->menu_post_sel = self::MENU_POST_DATOS_PERSONALES;
        $id = $this->idSuscriptor;
        
        $this->view->headScript()->appendFile(
            $this->mediaUrl . '/js/mis-datos.js'
        );

        $formSuscriptor = new Application_Form_Suscriptor($id);
        $arraySuscriptor = $this->_suscriptor->getSuscriptor($id);
        $formSuscriptor->validadorNumDoc($id);
//        $formSuscriptor->validadorCodigoSuscriptor($id);
        $formSuscriptor->setElementsDisabled($arraySuscriptor['es_suscriptor']);
        

        foreach (array_keys(Application_Form_Suscriptor::$valorDocumento) as $valor) {
            $valor = explode('#', $valor);
            if (strtoupper($arraySuscriptor['tipo_documento']) == $valor[0]) {
                $arraySuscriptor['tipo_documento'] = strtoupper($arraySuscriptor['tipo_documento']) . '#' . $valor[1];
                $formSuscriptor->getElement('numero_documento')->setAttrib('maxLength', $valor[1]);
            }
        }
        $js = sprintf('var ddoc = "%s";', $arraySuscriptor['numero_documento']);
        $this->view->headScript()->appendScript($js);

        $fn = explode('-', $arraySuscriptor['fecha_nacimiento']);
        $arraySuscriptor['fecha_nacimiento'] = $fn[2] . '/' . $fn[1] . '/' . $fn[0];
        $formSuscriptor->setDefaults($arraySuscriptor);

        if ($this->_request->isPost()) {
            if ($arraySuscriptor['es_suscriptor'] == 1) {
                $this->getMessenger()
                    ->error('Si deseas modificar tus datos, comunícate con nuestras oficinas');
            } else {
                $allParams = $this->_getAllParams();
                $validSuscriptor = $formSuscriptor->isValid($allParams);
                if ($validSuscriptor) {
                    $valuesSuscriptor = $formSuscriptor->getValues();
                    $date = date('Y-m-d H:i:s');
                    try {
                        $db = $this->getAdapter();
                        $db->beginTransaction();

//                    $lastId = $arraySuscriptor['id'];
                        $valuesSuscriptor['fecha_nacimiento'] = date(
                            'Y-m-d', strtotime(
                                str_replace('/', '-', $valuesSuscriptor['fecha_nacimiento'])
                            )
                        );
                        $valorTipoDoc = explode('#', $valuesSuscriptor['tipo_documento']);
                        $valuesSuscriptor['tipo_documento'] = $valorTipoDoc[0];
                        $valuesSuscriptor['sexo'] = $valuesSuscriptor['sexoMF'];
//                        $valuesSuscriptor['apellidos'] 
//                            = $valuesSuscriptor['apellido_paterno'] . ' ' . $valuesSuscriptor['apellido_materno'];
                        unset($valuesSuscriptor['sexoMF']);
                        unset($valuesSuscriptor['distrito_entrega']);
                        $valuesSuscriptor['fecha_actualizacion'] = $date;
                        $where = $this->_suscriptor->getAdapter()
                            ->quoteInto('id = ?', $id);
                        $this->_suscriptor->update($valuesSuscriptor, $where);
                        $db->commit();

                        // Updating session data
                        $this->getMessenger()->success($this->_messageSuccess);
                        $storage = Zend_Auth::getInstance()->getStorage()->read();
                        $storage['suscriptor']['nombres'] = $valuesSuscriptor['nombres'];
                        $storage['suscriptor']['apellido_paterno'] = $valuesSuscriptor['apellido_paterno'];
                        $storage['suscriptor']['apellido_materno'] = $valuesSuscriptor['apellido_materno'];
                        $storage['suscriptor']['sexo'] = $valuesSuscriptor['sexo'];
                        Zend_Auth::getInstance()->getStorage()->write($storage);
                        $this->_redirect(
                            Zend_Controller_Front::getInstance()
                            ->getRequest()->getRequestUri()
                        );
                    } catch (Exception $e) {
                        $db->rollBack();
//                        echo $e->getMessage();
                        $this->getMessenger()->error('Hubo un error al actualizar tus datos ['.$e->getMessage().']');
                    }
                }
            }
        }
        $this->view->formSuscriptor = $formSuscriptor;
    }

    public function redesSocialesAction()
    {
        $this->view->headScript()->appendFile(
            $this->mediaUrl.'/js/buscador_beneficios.js'
        );
        $this->view->menu_post_sel = self::MENU_POST_DATOS_PERSONALES;
        $this->view->menu_sel_side = self::MENU_POST_SIDE_REDESSOCIALES;
        $this->view->mediaUrl = $this->mediaUrl;
        
        
        $redesSociales = new Application_Model_CuentaRs();
        $redes = $redesSociales->getRedesByUser($this->usuario->id);
        $this->view->isFacebook = false;
        $this->view->isGoogle = false;
        foreach ($redes as $red) {
            if ($red['rs'] == 'facebook') {
                $this->view->isFacebook = true;
            }
            if ($red['rs'] == 'google') {
                $this->view->isGoogle = true;
            }
        }
        $config = $this->getConfig();
        $this->view->openUrl = 
            sprintf(
                $config->apis->google->openidUrl, 
                $config->app->siteUrl.'/'.$config->apis->google->returnUrl,
                $config->app->siteUrl
            );
        $this->view->facebookAppId = $config->apis->facebook->appid;
        $this->view->urlFacebook = $config->app->siteUrl
        . '/mi-cuenta/agregar-cuenta-facebook';
        $this->view->redes = $redes;
    }

    public function misAlertasAction()
    {
        $this->view->headScript()->appendFile(
            $this->mediaUrl.'/js/buscador_beneficios.js'
        );
        $this->view->headScript()->appendFile(
            $this->mediaUrl.'/js/alertas.js'
        );
        $this->view->menu_post_sel = self::MENU_POST_DATOS_PERSONALES;
        $this->view->menu_sel_side = self::MENU_POST_SIDE_ALERTAS;
        $formCategorias = new Application_Form_SuscribirCategorias();

        $_alerta = new Application_Model_Alerta();
        $alertas = $_alerta->getAlertasBySuscriptor($this->idSuscriptor);

        $arrayAlertas = array();
        $i = 1;
        foreach ($alertas as $alerta) {
            $arrayAlertas['categoria_' . $i] = $alerta['categoria_id'];
            $arrayAlertas['id_' . $i] = $alerta['id'];
            $i++;
        }
        $arrayAlertas['enviar_alertas_email'] = $this->auth['suscriptor']['enviar_alertas_email'];

        $formCategorias->setDefaults($arrayAlertas);

        if ($this->_request->isPost()) {
            $allParams = $this->_getAllParams();
            $validAlertas = $formCategorias->isValid($allParams);
            if ($validAlertas) {
                try {
                    $db = $this->getAdapter();
                    $db->beginTransaction();

                    $where = $this->_suscriptor->getAdapter()
                        ->quoteInto('id = ?', $this->idSuscriptor);
                    $this->_suscriptor->update(
                        array('enviar_alertas_email' => $allParams['enviar_alertas_email']), $where
                    );

                    $date = date('Y-m-d H:i:s');
                    for ($i = 1; $i < 4; $i++) {
                        $alerta = new Application_Model_Alerta();
                        if ($allParams['id_' . $i] <> '') {
                            if ($allParams['categoria_' . $i] == '-1') {
                                $where = $alerta
                                        ->getAdapter()->quoteInto('id = ?', $allParams['id_' . $i]);
                                $alerta->delete($where);
                            } else {
                                $data['categoria_id'] = $allParams['categoria_' . $i];
                                $where = $alerta
                                        ->getAdapter()->quoteInto('id = ?', $allParams['id_' . $i]);
                                $alerta->update($data, $where);
                            }
                        } else {
                            if ($allParams['categoria_' . $i] <> '-1') {
                                $data['categoria_id'] = $allParams['categoria_' . $i];
                                $data['suscriptor_id'] = $this->idSuscriptor;
                                $data['fecha_afiliacion'] = $date;
                                $alerta->insert($data);
                            }
                        }
                    }
                    $db->commit();
                    //// Updating session data
                    $this->getMessenger()->success($this->_messageSuccess);
                    $storage = Zend_Auth::getInstance()->getStorage()->read();
                    $storage['suscriptor']['enviar_alertas_email']
                        = $allParams['enviar_alertas_email'];
                    Zend_Auth::getInstance()->getStorage()->write($storage);
                    $this->_redirect(
                        Zend_Controller_Front::getInstance()
                        ->getRequest()->getRequestUri()
                    );
                } catch (Exception $e) {
                    $db->rollBack();
                    echo $e->getMessage();
                }
            }
        }
        $this->view->formCategorias = $formCategorias;
    }
    
    public function Captcha()
    {
        //$this->mediaUrl
        $baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
        $captcha = new Zend_Captcha_Image(
            array(
                'name' => 'captcha',
                'wordLen' => 5,
                'font' => 'C:\cs\src\public\static\font\VeraMono.ttf',
                'height' => 50,
                'width' => 120,
                'imgDir' => './static/images/captcha',
                'imgUrl' => $this->mediaUrl. '/images/captcha',
                'timeout' => 300
            )
        );
        
        $id = $captcha->generate();
        $captchaSession = new Zend_Session_Namespace('Zend_Form_Captcha_'.$id);
        
        $captchaIterator = $captchaSession->getIterator();
//        $_SESSION['codigo'] = $captchaIterator['word'];
        
        return $captcha->render();
    }

    public function contactoAction()
    {
        $this->view->headScript()->appendFile(
            $this->mediaUrl.'/js/buscador_beneficios.js'
        );
        $this->view->headScript()->appendFile(
            $this->mediaUrl.'/js/contacto-cuenta.js'
        );        
        
        $this->view->menu_post_sel = self::MENU_POST_MI_CUENTA;
        $this->view->menu_sel_side = self::MENU_POST_SIDE_CONTACTO;
        //var_dump($this->auth);
        $selected = ($this->_getParam('tipo', '')=='queja-reclamo'?'Queja / Reclamo':'');
        $formContacto = new Application_Form_MiCuentaContacto();
        $formContacto->setIdCategoria($selected);
        $this->view->ispost = false;
        //$_categoria = new Application_Model_Categoria();
        //$filterSlug = new App_Filter_Slug();
        if ($this->_request->isPost()) {
            $this->view->ispost = true;
            $values = $this->getRequest()->getPost();
            $formContacto->setDefaults($values);
            
            if($formContacto->isValid($values)):
                //validacion del captcha
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
                        //enviar correo
                        $this->view->ispost = false;
                        //$values['to'] = $this->auth['usuario']->email;
                        $values['to'] = $this->getConfig()->cuenta->mail->micuenta->contacto;
                        $values['codigo'] = $this->auth['suscriptor']['codigo_suscriptor'];
                        $values['nombres'] = $this->auth['suscriptor']['nombres'].' '.
                                            $this->auth['suscriptor']['apellido_paterno'].' '.
                                            $this->auth['suscriptor']['apellido_materno'];
                        //var_dump($values); exit;
                        $this->_helper->Mail->miCuentaContacto($values);
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
            
            //capturamos los datos del captcha
            /*$captcha = $this->getRequest()->getPost('imgcaptcha');
            $captchaId = $captcha['id'];
            $captchaInput = $captcha['input'];
            $captchaSession = new Zend_Session_Namespace('Zend_Form_Captcha_'.$captchaId);
            $captchaIterator = $captchaSession->getIterator();
            $captchaWord = $captchaIterator['word'];
            if($captchaInput == $captchaWord){
                //OK
                echo 'true';
            }else{
                //NOK
                echo 'false';
            }*/
            
            //$values['slug'] = $filterSlug->filter($values['nombre']);
            //$_categoria->insert($values);
            //$this->_redirect($this->view->url(array('action'=>'index')));
        }
        
        $this->view->form_contacto = $formContacto;
        //$this->view->captcha = $this->Captcha();
    }

    public function invitarBeneficiarioAction()
    {
        $this->view->headScript()->appendFile(
            $this->mediaUrl.'/js/buscador_beneficios.js'
        );
        
        $formBeneficiario = new Application_Form_RegistroBeneficiario();
        $formBeneficiario->validarEmailInvitado(null);
        if ($this->getRequest()->isPost() && $this->isAuth) {
//            $request = $this->getRequest();
            $allParams = $this->_getAllParams();
            $validInvitado = $formBeneficiario->isValid($allParams);
//            var_dump($formBeneficiario);
            if ($validInvitado) {
//                var_dump('oka');
                try {
                    $db = $this->getAdapter();
                    $db->beginTransaction();
                    $valuesInvitado = $formBeneficiario->getValues();
                    $valuesInvitado['sexo'] = $valuesInvitado['sexoMF'];
                    unset($valuesInvitado['sexoMF']);
                    $valuesInvitado['suscriptor_id'] = $this->idSuscriptor;
                    $date = date('Y-m-d H:i:s');
                    $lifetime 
                        = date('Y-m-d H:i:s', strtotime($date) + $this->config->app->tokenUser);
                    $token = Application_Model_Invitacion::generarToken($valuesInvitado, $lifetime);
                    $valuesInvitado['token_activacion'] = $token;
                    $this->_helper
                        ->mail->nuevaInvitacion(
                            array(
                                'to' => $valuesInvitado['email'], 
                                'user' => $valuesInvitado['email'], 
                                'urlToken' => $token, 
                                'nombre' => $valuesInvitado['nombres']==''? 
                                    'Hola':ucwords($valuesInvitado['nombres']), 
                                'subjectMessage' => 
                                    'Has sido invitado a disfrutas los 
                                     beneficios del ClubSuscriptor',
                                'anfitrion' => $this->auth['suscriptor']['nombres'],
                                'invitacion' => $valuesInvitado['invitacion']
                            )
                        );
                    $db->commit();
                    $this->getMessenger()->success('Invitación enviada con éxito');
                    $this->_redirect('mi-cuenta/mis-beneficiarios');
                } catch (Exception $e) {
                    $db->rollBack();
                    echo $e->getMessage();
                }
            }
        }
        $this->_forward('mis-beneficiarios');
    }

    public function validarInvitadoAction()
    {
        $this->view->headScript()->appendFile(
            $this->mediaUrl.'/js/buscador_beneficios.js'
        );
        
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $email = $this->_getParam('email');
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $authData = Zend_Auth::getInstance()->getStorage()->read();
            $id = $authData['usuario']->id;
        } else {
            $id = false;
        }
        $_suscriptor = new Application_Model_Suscriptor();
        $isValid = $_suscriptor->validarInvitado($email);
        $data = array(
            'status' => $isValid
        );
        $this->_response->appendBody(Zend_Json::encode($data));
    }
    
    public function eliminarBeneficiarioAction()
    {
        if ($this->_request->isPost()) {
            $this->view->headScript()->appendFile(
                $this->mediaUrl.'/js/buscador_beneficios.js'
            );

            $this->_helper->layout->disableLayout();
            $this->_helper->viewRenderer->setNoRender();
            $mensaje = "";
            //FIXME validar csfr
            if ($this->isAuth) {
                $invitado = $this->_getParam('inv_id');
                $suscriptor = new Application_Model_Suscriptor();
                if($suscriptor->eliminarBeneficiario($invitado))
                    $mensaje = "Se eliminó el beneficiario con éxito.";
                else
                    $mensaje = "Se ha producido un error. Inténtelo mas tarde";
            } else {
                $mensaje = "Se ha producido un error.";
            }
            $this->getMessenger()->success($mensaje);
            $this->_redirect('mi-cuenta/mis-beneficiarios');
        }
    }

    
    public function agregarCuentaFacebookAction()
    {
        $this->view->headScript()->appendFile(
            $this->mediaUrl.'/js/buscador_beneficios.js'
        );
        
        $code = $this->getRequest()->getParam('code', 0);
        if (empty($code)) {
            $this->_redirect('/');
        }
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $config = $this->getConfig();
        $appId = $config->apis->facebook->appid;
        $appSecret = $config->apis->facebook->appsecret;
        $url = $config->app->siteUrl
        . '/mi-cuenta/agregar-cuenta-facebook';
        $tokenUrl = "https://graph.facebook.com/oauth/access_token?"
        . "client_id=" . $appId . "&redirect_uri=" . urlencode($url)
        . "&client_secret=" . $appSecret . "&code=" . $_REQUEST["code"];

        $response = file_get_contents($tokenUrl);
        $params = null;
        parse_str($response, $params);

        $graphUrl = "https://graph.facebook.com/me?access_token=" 
        . $params['access_token'];

        $facebookUser = json_decode(file_get_contents($graphUrl));
        $data['usuario_id'] = $this->usuario->id;
        $data['rsid'] = $facebookUser->id;
        $data['rs'] = 'facebook';
        if (isset($facebookUser->username)) {
            $data['screenname'] = $facebookUser->username;
        } else {
            $data['screenname'] = $facebookUser->name;
        }
        $red = new Application_Model_CuentaRs();
        if ($red->existeFacebook($this->usuario->id) === false) {
            $red->insert($data);
        }
        $this->_redirect('/mi-cuenta/redes-sociales');
    }

    public function agregarCuentaGoogleAction()
    {
        $this->view->headScript()->appendFile(
            $this->mediaUrl.'/js/buscador_beneficios.js'
        );
        
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $dataGoogle = $this->getRequest()->getParams();
        $config = $this->getConfig();
        if (empty($dataGoogle) || $dataGoogle['openid_mode']=='cancel') {
            $this->getMessenger()->error('No se realizó la asociación de la Cuenta');
        } else {
            $data['usuario_id'] = $this->usuario->id;
            $data['rsid'] = str_replace(
                $config->apis->google->urlResponse, 
                "", 
                $dataGoogle['openid_claimed_id']
            );
            $data['rs'] = 'google';
            $data['screenname'] = $dataGoogle['openid_ext1_value_email'];
            $red = new Application_Model_CuentaRs();
            if ($red->existeGoogle($this->usuario->id) === false) {
                $red->insert($data);
            }
            
        }
        $this->_redirect('/mi-cuenta/redes-sociales');
    }

    public function eliminarCuentaFacebookAction()
    {
        $this->view->headScript()->appendFile(
            $this->mediaUrl.'/js/buscador_beneficios.js'
        );
        
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $usuario = $this->usuario;
        $red = new Application_Model_CuentaRs();
        $red->eliminarCuentaFacebookByUsuario($this->usuario->id);
        $this->_redirect('/mi-cuenta/redes-sociales');
    }

    public function eliminarCuentaGoogleAction()
    {
        $this->view->headScript()->appendFile(
            $this->mediaUrl.'/js/buscador_beneficios.js'
        );
        
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $usuario = $this->usuario;
        $red = new Application_Model_CuentaRs();
        $red->eliminarCuentaGoogleByUsuario($this->usuario->id);
        $this->_redirect('/mi-cuenta/redes-sociales');
    }

    public function vistaPreviaBeneficioAction()
    {
        $this->_helper->layout->disableLayout();
        //$this->_helper->viewRenderer->setNoRender();
        $obj = new Application_Model_Beneficio();
        $id = $this->_getParam('id', false);
        $beneficio = $obj->getBeneficioInfoById($id);
        if (!$beneficio) {
            $this->getMessenger()->error('Debe seleccionar un beneficio valido');
            //$this->_redirect('gestor/beneficios');
        } else {
            $config = $this->getConfig();
            $this->view->sufixlittle = $config->beneficios->logo->sufix->little;
            $this->view->beneficio = $beneficio;
        }
        //$this->render();
    }
}