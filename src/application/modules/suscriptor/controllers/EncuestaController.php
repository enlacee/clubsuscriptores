<?php

class Suscriptor_EncuestaController extends App_Controller_Action_Suscriptor {

    protected $_suscriptor;
    protected $_usuario;
    protected $_url;
    protected $_noSuscriptor;
    protected $_pswd;

    public function init() {
        parent::init();

        $this->_suscriptor = new Application_Model_Suscriptor();
        $this->_usuario = new Application_Model_Usuario();
        $this->_url = '/mi-cuenta';
        $this->_noSuscriptor = '/mi-cuenta/mis-datos-personales';
        $this->idSuscriptor = null;
        Zend_Layout::getMvcInstance()->assign(
                'bodyAttr', array('id' => 'perfilReg', 'class' => 'noMenu')
        );
    }
   
    public function indexAction() {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_redirect('/');            
        }
        
        $idSuscriptor=$this->auth["suscriptor"]["id"];
        
        $tablaEncuestaSuscriptor=new Application_Model_EncuestaSuscriptor();       
        $resp=$tablaEncuestaSuscriptor->getEncuestaRealizada($idSuscriptor);
        
        if((int)$resp>0){
            $this->_redirect($this->_url);                       
        }          
        
        $this->view->headTitle()->prepend('Encuesta');
        $this->view->headMeta()->setName("description", "Encuesta de suscriptores");
        $this->view->headMeta()->setName("keywords", "encuesta,suscriptores, beneficios de el comercio, 
            Club De Suscriptores El Comercio Perú");
        
       
        $this->view->headScript()->appendFile(
                $this->mediaUrl . '/js/encuesta.js'
        );
        
        $frmEncuesta=  new Application_Form_Encuestasuscriptor();
        $this->view->frmEncuesta=$frmEncuesta;
        
        $frmInteresBeneficioclup =  new Application_Form_InteresBeneficioClup();
        $this->view->frmInteresBeneficioClup=$frmInteresBeneficioclup;
        
        $frmSatisfechoBeneficiosClup =  new Application_Form_SatisfechoBeneficiosClup();
        $this->view->frmSatisfechoBeneficiosClup=$frmSatisfechoBeneficiosClup;
                
        if ($this->_request->isPost()) {            
            $allParams = $this->_getAllParams();                                
            if($frmEncuesta->isValid($allParams)){
                if($frmInteresBeneficioclup->isValid($allParams)){
                    if($frmSatisfechoBeneficiosClup->isValid($allParams)){
                            
                            $encuestaSuscriptor=$frmEncuesta->getValues();
                            $interesBeneficioclup=$frmInteresBeneficioclup->getValues();
                            $satisfechoBeneficiosClup=$frmSatisfechoBeneficiosClup->getValues();
                            
                            $encuestaSuscriptor['suscriptor_id']=$idSuscriptor;

                            if((int)$encuestaSuscriptor['participacion_promocion_club']==0){
                                    if((int)$encuestaSuscriptor['noparticipo_porque']==1){                        
                                        $encuestaSuscriptor['no_participa_promociones']=1;
                                    }elseif((int)$encuestaSuscriptor['noparticipo_porque']==2){                        
                                        $encuestaSuscriptor['ninguna_promocion']=1;
                                    }else{//3
                                        $encuestaSuscriptor['otro']=1;
                                        
                                        if(trim($encuestaSuscriptor['noparticipo_otros_comentario'])=='-- Indique aqui su motivo --'){
                                            $encuestaSuscriptor['noparticipo_otros_comentario']='';
                                        }
                                        
                                        $encuestaSuscriptor['otros_comentario']=$encuestaSuscriptor['noparticipo_otros_comentario'];
                                    }                            
                            }
                                                        
                            unset($encuestaSuscriptor['noparticipo_otros_comentario']);//colocado nuevo                            
                            unset($encuestaSuscriptor['noparticipo_porque']);
                            
                            if(trim($encuestaSuscriptor['otro_beneficio_club'])=='-- Escriba aqui los beneficios que le gustaría recibir --'){
                                $encuestaSuscriptor['otro_beneficio_club']='';
                            }
                            

                            if((int)$encuestaSuscriptor['oportunamente_beneficios_ofrecidos']==1){
                                if((int)$encuestaSuscriptor['oportunamente_beneficios_elegir']==1){
                                       $encuestaSuscriptor['oportunamente_correo_electronico']=1; 
                                }elseif((int)$encuestaSuscriptor['oportunamente_beneficios_elegir']==2){
                                       $encuestaSuscriptor['oportunamente_diario']=1; 
                                }elseif((int)$encuestaSuscriptor['oportunamente_beneficios_elegir']==3){
                                        $encuestaSuscriptor['oportunamente_paginaweb']=1;
                                }else{
                                        $encuestaSuscriptor['oportunamente_otros']=1;
                                }                                
                            }
                            
                            unset($encuestaSuscriptor['oportunamente_beneficios_elegir']);        

                            if((int)$encuestaSuscriptor['donde_compra_cv']==1){
                                $encuestaSuscriptor['usualmente_supermercados_cv']=1;
                            }elseif((int)$encuestaSuscriptor['donde_compra_cv']==2){
                                $encuestaSuscriptor['usualmente_tiendasespecializadas_cv']=1;
                            }elseif((int)$encuestaSuscriptor['donde_compra_cv']==3){
                                $encuestaSuscriptor['usualmente_tiendas_internet_cv']=1;
                            }elseif((int)$encuestaSuscriptor['donde_compra_cv']==4){
                                $encuestaSuscriptor['usualmente_otro_cv']=1;                                
                            }else{
                                $encuestaSuscriptor['usualmente_nocompro_cv']=1;
                            }
                            
                            if(trim($encuestaSuscriptor['usualmente_otro_comentario_cv'])=='-- Indique los lugares de compra --'){
                                $encuestaSuscriptor['usualmente_otro_comentario_cv']='';
                            }
                            
                            unset($encuestaSuscriptor['donde_compra_cv']);
                            
                            if($encuestaSuscriptor['importacion_atributos_otros']==0){
                                $encuestaSuscriptor['importacion_atributos_otroscomentario_cv']='';
                            }else{
                                    if(trim($encuestaSuscriptor['importacion_atributos_otroscomentario_cv'])=='-- Indique los lugares de compra --'){                                    
                                        $encuestaSuscriptor['importacion_atributos_otroscomentario_cv']='';
                                    }
                            }
                            
                            if((int)$encuestaSuscriptor['dispuesto_pagar_cv']==1){
                                $encuestaSuscriptor['dispuesto_pagar_50_cv']=1;                    
                            }elseif((int)$encuestaSuscriptor['dispuesto_pagar_cv']==2){
                                $encuestaSuscriptor['dispuesto_pagar_50_100_cv']=1;                    
                            }elseif((int)$encuestaSuscriptor['dispuesto_pagar_cv']==3){
                                $encuestaSuscriptor['dispuesto_pagar_100_200_cv']=1;                    
                            }elseif((int)$encuestaSuscriptor['dispuesto_pagar_cv']==4){
                                $encuestaSuscriptor['dispuesto_pagar_200_500_cv']=1;                    
                            }else{
                                $encuestaSuscriptor['dispuesto_pagar_500_cv']=1;
                            }

                            unset($encuestaSuscriptor['dispuesto_pagar_cv']);                

                            $encuestaSuscriptor['fecharegistro']=date('Y-m-d H:i:s');
                            
                            try {
                                $db = $this->getAdapter();
                                $db->beginTransaction();
                                
                                //---------------------------------------------------------
                                    if($tablaEncuestaSuscriptor->addEncuesta($encuestaSuscriptor)){
                                            $dataEncuestaSuscriptor=$tablaEncuestaSuscriptor->getIdEncuesta($this->auth["suscriptor"]["id"]);

                                            if($dataEncuestaSuscriptor){
                                                $getDataEncuestaSuscriptor = $dataEncuestaSuscriptor->toArray();                                        

                                                $interesBeneficioclup['idencuesta']=$getDataEncuestaSuscriptor['idencuesta'];

                                                $interesBeneficioclup['conciertos']=$interesBeneficioclup['beneficio_conciertos'];
                                                unset($interesBeneficioclup['beneficio_conciertos']);           

                                                $interesBeneficioclup['teatro']=$interesBeneficioclup['beneficio_teatro'];
                                                unset($interesBeneficioclup['beneficio_teatro']);

                                                $interesBeneficioclup['opera']=$interesBeneficioclup['beneficio_opera'];
                                                unset($interesBeneficioclup['beneficio_opera']);

                                                $interesBeneficioclup['ballet']=$interesBeneficioclup['beneficio_ballet'];
                                                unset($interesBeneficioclup['beneficio_ballet']);

                                                $interesBeneficioclup['shows_ninos']=$interesBeneficioclup['beneficio_shows_ninos'];                                
                                                unset($interesBeneficioclup['beneficio_shows_ninos']);

                                                $interesBeneficioclup['deportes']=$interesBeneficioclup['beneficio_deportes'];                                
                                                unset($interesBeneficioclup['beneficio_deportes']);

                                                $interesBeneficioclup['avantpremiere']=$interesBeneficioclup['beneficio_avantpremiere'];                                
                                                unset($interesBeneficioclup['beneficio_avantpremiere']);

                                                $interesBeneficioclup['cines']=$interesBeneficioclup['beneficio_cines'];                                
                                                unset($interesBeneficioclup['beneficio_cines']);

                                                $interesBeneficioclup['seminarios_cursos']=$interesBeneficioclup['beneficio_seminarios_cursos'];
                                                unset($interesBeneficioclup['beneficio_seminarios_cursos']);

                                                $interesBeneficioclup['ferias']=$interesBeneficioclup['beneficio_ferias'];
                                                unset($interesBeneficioclup['beneficio_ferias']);

                                                $interesBeneficioclup['paquetes_turisticos']=$interesBeneficioclup['beneficio_paquetes_turisticos'];
                                                unset($interesBeneficioclup['beneficio_paquetes_turisticos']);

                                                $interesBeneficioclup['boletos_aereos_nacionales']=$interesBeneficioclup['beneficio_boletos_aereos_nacionales'];
                                                unset($interesBeneficioclup['beneficio_boletos_aereos_nacionales']);

                                                $interesBeneficioclup['cierra_puertas']=$interesBeneficioclup['beneficio_cierra_puertas'];
                                                unset($interesBeneficioclup['beneficio_cierra_puertas']);

                                                $interesBeneficioclup['vinos']=$interesBeneficioclup['beneficio_vinos'];
                                                unset($interesBeneficioclup['beneficio_vinos']);

                                                $interesBeneficioclup['estado']=1;
                                                $interesBeneficioclup['fecharegistro']=date('Y-m-d H:i:s');

                                                $tablaInteresBeneficioClup = new Application_Model_InteresBeneficioClup();

                                                $tablaInteresBeneficioClup->addInteresBeneficioClup($interesBeneficioclup);

                                                if((int)$encuestaSuscriptor['participacion_promocion_club']==1){
                                                        $satisfechoBeneficiosClup['idencuesta']=$getDataEncuestaSuscriptor['idencuesta'];                                
                                                        $satisfechoBeneficiosClup['conciertos']=$satisfechoBeneficiosClup['beneficioclup_conciertos'];
                                                        unset($satisfechoBeneficiosClup['beneficioclup_conciertos']);           

                                                        $satisfechoBeneficiosClup['teatro']=$satisfechoBeneficiosClup['beneficioclup_teatro'];
                                                        unset($satisfechoBeneficiosClup['beneficioclup_teatro']);

                                                        $satisfechoBeneficiosClup['opera']=$satisfechoBeneficiosClup['beneficioclup_opera'];
                                                        unset($satisfechoBeneficiosClup['beneficioclup_opera']);

                                                        $satisfechoBeneficiosClup['ballet']=$satisfechoBeneficiosClup['beneficioclup_ballet'];
                                                        unset($satisfechoBeneficiosClup['beneficioclup_ballet']);

                                                        $satisfechoBeneficiosClup['shows_ninos']=$satisfechoBeneficiosClup['beneficioclup_shows_ninos'];                                
                                                        unset($satisfechoBeneficiosClup['beneficioclup_shows_ninos']);

                                                        $satisfechoBeneficiosClup['deportes']=$satisfechoBeneficiosClup['beneficioclup_deportes'];                                
                                                        unset($satisfechoBeneficiosClup['beneficioclup_deportes']);

                                                        $satisfechoBeneficiosClup['avantpremiere']=$satisfechoBeneficiosClup['beneficioclup_avantpremiere'];                                
                                                        unset($satisfechoBeneficiosClup['beneficioclup_avantpremiere']);

                                                        $satisfechoBeneficiosClup['cines']=$satisfechoBeneficiosClup['beneficioclup_cines'];                                
                                                        unset($satisfechoBeneficiosClup['beneficioclup_cines']);

                                                        $satisfechoBeneficiosClup['seminarios_cursos']=$satisfechoBeneficiosClup['beneficioclup_seminarios_cursos'];
                                                        unset($satisfechoBeneficiosClup['beneficioclup_seminarios_cursos']);

                                                        $satisfechoBeneficiosClup['ferias']=$satisfechoBeneficiosClup['beneficioclup_ferias'];
                                                        unset($satisfechoBeneficiosClup['beneficioclup_ferias']);

                                                        $satisfechoBeneficiosClup['paquetes_turisticos']=$satisfechoBeneficiosClup['beneficioclup_paquetes_turisticos'];
                                                        unset($satisfechoBeneficiosClup['beneficioclup_paquetes_turisticos']);

                                                        $satisfechoBeneficiosClup['boletos_aereos_nacionales']=$satisfechoBeneficiosClup['beneficioclup_boletos_aereos_nacionales'];
                                                        unset($satisfechoBeneficiosClup['beneficioclup_boletos_aereos_nacionales']);

                                                        $satisfechoBeneficiosClup['cierra_puertas']=$satisfechoBeneficiosClup['beneficioclup_cierra_puertas'];
                                                        unset($satisfechoBeneficiosClup['beneficioclup_cierra_puertas']);

                                                        $satisfechoBeneficiosClup['vinos']=$satisfechoBeneficiosClup['beneficioclup_vinos'];
                                                        unset($satisfechoBeneficiosClup['beneficioclup_vinos']);

                                                        $satisfechoBeneficiosClup['estado']=1;
                                                        $satisfechoBeneficiosClup['fecharegistro']=date('Y-m-d H:i:s');                                

                                                        $tablaSatisfechoBeneficiosClup= new Application_Model_SatisfechoBeneficiosClup();
                                                        $tablaSatisfechoBeneficiosClup->addSatisfechoBeneficioClup($satisfechoBeneficiosClup);                                            
                                                }                                               
                                            }
                                    }
                                //---------------------------------------------------------                                 
                                
                                $db->commit();
                                
                                $this->_helper->mail->confirmacionEncuesta(
                                        array(
                                            'to' => $this->auth['usuario']->email,
                                            'user' => $this->auth['usuario']->email,
                                            'urlMisdatos' => Zend_Registry::get('config')->app->siteUrl.'/mi-cuenta/mis-datos-personales',
                                            'urlEncuesta' => Zend_Registry::get('config')->app->siteUrl.'/suscriptor/encuesta',
                                            'urlImagenes' => Zend_Registry::get('config')->app->mediaUrl.'/images/emailing',
                                            'urlPortal' => Zend_Registry::get('config')->app->siteUrl.'/home/index',
                                            'fr' => date('Y-m-d H:i:s'),
                                            //'slug' => $arraySuscriptor['slug'],
                                            'nombre' => ucwords($this->auth['suscriptor']['nombres']),
                                            'subjectMessage' => 'Gracias'
                                        )
                                );
                                
                                $this->_redirect($this->_url."/index/en/1");
                                
                            } catch (Exception $exc) {
                                $db->rollBack();
                                $this->getMessenger()->error($exc->getMessage());                                
                            }                             
                    }
                }
            }
            
        }
    }

}