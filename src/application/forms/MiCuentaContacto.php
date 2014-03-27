<?php

class Application_Form_MiCuentaContacto extends App_Form
{
    //Max Length
    private $_maxlengthTituloConsulta = '75';
    private $_maxlengthMensaje = '450';
    
    public function init()
    {
        parent::init();
        
        //$objcategorias = new Application_Model_Categoria();
        $cboCategoria = new Zend_Form_Element_Select('categoria');
        $cboCategoria->addMultiOption('', 'Seleccione una Categoria');
        $cboCategoria->addMultiOption('Sugerencia', 'Sugerencia');
        $cboCategoria->addMultiOption('Queja / Reclamo', 'Queja / Reclamo');
        $cboCategoria->addMultiOption('Consulta', 'Consulta');
        //$cboCategoria->addMultiOptions($objcategorias->getCategoriaSinGeneral());
        $cboCategoria->setRequired();
        $cboCategoria->errMsg = 'No ha seleccionado una categoria';
        $this->addElement($cboCategoria);
        
        $txtTituloConsulta = new Zend_Form_Element_Text('titulo_consulta');
        //$txtTituloConsulta->setLabel('Título de la Consulta:');
        $txtTituloConsulta->setRequired();
        //$txtTituloConsulta->addFilter(new Zend_Filter_StringTrim());
        //$txtTituloConsulta->addFilter(new Zend_Filter_StringToLower());
        $txtTituloConsulta->setAttrib('maxLength', $this->_maxlengthTituloConsulta);
        $txtTituloConsulta->addValidator(
            new Zend_Validate_StringLength(array('min'=>1,'max'=> $this->_maxlengthTituloConsulta))
        );
        
        //$txtTituloConsulta->addValidator(new Zend_Validate_NotEmpty());
        $txtTituloConsulta->errMsg = 'Se requiere un Titulo';
        $this->addElement($txtTituloConsulta);
        
        $txtareaMensaje = new Zend_Form_Element_Textarea('mensaje');
        //$txtareaMensaje->setLabel('Mensaje:');
        $txtareaMensaje->setAttrib('rows', 4);
        //$txtareaMensaje->setAttrib('cols', 18);
        $txtareaMensaje->setAttrib('maxLength', $this->_maxlengthMensaje);
        $txtareaMensaje->addValidator(
            new Zend_Validate_StringLength(array('min'=>1,'max'=>$this->_maxlengthMensaje))
        );
        $txtareaMensaje->setRequired();
        $txtareaMensaje->addValidator(new Zend_Validate_NotEmpty());
        $txtareaMensaje->errMsg = 'Se requiere un mensaje';
        $this->addElement($txtareaMensaje);
        
        $btnEnviar = new Zend_Form_Element_Submit('enviar');
        $btnEnviar->setLabel('');
        $this->addElement($btnEnviar);
        
        /*$imgcaptcha = new Zend_Form_Element_Captcha('imgcaptcha',array(
            'label' => 
                'Por favor, escribe el código de seguridad tal como se muestra en la imagen',
            'captcha' => array(
                'captcha' => 'Image',
                'wordLen' => 6,
                //Captcha timeout, 5 mins
                'timeout' => 300,
                'font' => './static/font/VeraMono.ttf',
                'imgDir' => './static/images/captcha',
                'imgUrl' => $this->_config->app->mediaUrl. '/images/captcha',
                'fsize' => 20,
                //'height'=> 60,
                //'width' => 250,
                'gcFreq' => 50,
                'expiration' => 300,
                //alt tag to keep SEO guys happy
                'imgAlt' => "Captcha Image - Please verify you're human",
                //error message
                'messages' => array(
                    'badCaptcha' => 'Codigo Incorrecto'
                )
            )
        ));
        
        $imgcaptcha->addDecorator('Label',array('class'=>'fnormal black Trebuchet'));
        $this->addElement($imgcaptcha);*/
        
        $publickey = $this->_config->recaptcha->publickey;
        $privatekey = $this->_config->recaptcha->privatekey;
        
        //Translate in your language
        $recaptchaItTranslation =
            array(
                'visual_challenge' => "Vídeo de verificación",
                'audio_challenge' => "Verificación de audio",
                'refresh_btn' => "Realizar una nueva verificación",
                'instructions_visual' => "Escriba las dos palabras",
                'instructions_audio' => "Escriba lo que se oye",
                'help_btn' => "Ayuda",
                'play_again' => "Repetir reprodución de audio",
                'cant_hear_this' => "Descargar audio como MP3",
                'incorrect_try_again' => "Incorrecto. Inténtalo de nuevo."
            );
        
        $recaptcha = new Zend_Service_ReCaptcha($publickey, $privatekey);
        //$recaptcha->setOption('custom_translations', $recaptchaItTranslation);
        //Change theme
        $recaptcha->setOption('theme', 'clean');
        $recaptcha->setOption('lang', 'es');        
        
        $captcha = new Zend_Form_Element_Captcha(
            'challenge', 
            array(
                'label' => 
                    'Por favor, escribe el código de seguridad tal como se muestra en la imagen',
                'captcha' => 'ReCaptcha',
                'captchaOptions' => array(
                    'captcha' => 'ReCaptcha',
                    'service' => $recaptcha
                )
            )
        );
        $captcha->setErrorMessages(array('incorrect-captcha-sol'=>'Codigo Incorrecto'));
        $captcha->addDecorator('Label', array('class'=>'fnormal black Trebuchet'));
        $this->addElement($captcha);
        
        $chkLeerCondi = new Zend_Form_Element_Checkbox('chkcondi');
        $chkLeerCondi->setCheckedValue('1');
        $chkLeerCondi->setUncheckedValue('0');
        $chkLeerCondi->setChecked(false);
        $chkLeerCondi->setRequired();
        $chkLeerCondi->errMsg = 'Debe Aceptar las condiciones';
        $this->addElement($chkLeerCondi);
        
        $this->setAction('');
        $this->setMethod('post');
    }
    
    public function setIdCategoria($id)
    {
        $element = $this->getElement('categoria');
        $element->setValue($id);
    }
}
