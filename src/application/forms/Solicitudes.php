<?php

class Application_Form_Solicitudes extends App_Form
{
    //Max Length
    private $_maxlengthNames = '50';
    private $_maxlengthApellidos = '70';
    private $_maxlengthApellidoPaterno = '70';
    private $_maxlengthMensaje = '450';
    private $_maxlengthNroDni = '8';
    private $_maxlengthNroCex = '15';
    private $_maxlengthNroRuc = '11';
    private $_maxlengthNroPas = '11';
    private $_maxlengthTelefonos = '20';
    private $_maxlengthEmail = '60';
    
    public function init()
    {
        parent::init();
        
        $txtNombres = new Zend_Form_Element_Text('nombres');
        $txtNombres->setRequired();
        //$txtNombres->addFilter(new Zend_Filter_StringTrim());
        //$txtNombres->addFilter(new Zend_Filter_StringToLower());
        $txtNombres->setAttrib('maxLength', $this->_maxlengthNames);
        $txtNombres->addValidator(
            new Zend_Validate_StringLength(array('min'=>1,'max'=> $this->_maxlengthNames))
        );
        $txtNombres->addValidator(
            new Zend_Validate_Alpha(true)
        );
        $txtNombres->errMsg = 'Se requieren tus Nombre correcto';
        $this->addElement($txtNombres);
        
//        $txtApellidos = new Zend_Form_Element_Text('apellidos');
//        $txtApellidos->setRequired();
//        $txtApellidos->setAttrib('maxLength', $this->_maxlengthApellidos);
//        $txtApellidos->addValidator(
//            new Zend_Validate_StringLength(array('min'=>1,'max'=> $this->_maxlengthApellidos))
//        );
//        $txtApellidos->errMsg = 'Se requieren tus Apellidos';
//        $this->addElement($txtApellidos);
        
        // Apellido
        $fApePat = new Zend_Form_Element_Text('apellido_paterno');
        $fApePat->setRequired();
        $fApePat->setAttrib('maxLength', $this->_maxlengthApellido);
        $fApePat->addValidator(
            new Zend_Validate_StringLength(array('min' => '1', 'max' => $this->_maxlengthApellido))
        );
        $fApePatVal = new Zend_Validate_NotEmpty();
        $fApePat->addValidator($fApePatVal);
        $fApePat->addValidator(
            new Zend_Validate_Alpha(true)
        );
        $fApePat->errMsg = '¡Se requiere su apellido paterno correcto!';
        $this->addElement($fApePat);
        $fApeMat = new Zend_Form_Element_Text('apellido_materno');
        $fApeMat->setRequired();
        $fApeMat->setAttrib('maxLength', $this->_maxlengthApellido);
        $fApeMat->addValidator(
            new Zend_Validate_StringLength(array('min' => '1', 'max' => $this->_maxlengthApellido))
        );
        $fApeMatVal = new Zend_Validate_NotEmpty();
        $fApeMat->addValidator($fApeMatVal);
        $fApeMat->addValidator(
            new Zend_Validate_Alpha(true)
        );
        $fApeMat->errMsg = '¡Se requiere su apellido materno correcto!';
        $this->addElement($fApeMat);        
        
        $txtTelefonos = new Zend_Form_Element_Text('telefonos');
        $txtTelefonos->setRequired();
        $txtTelefonos->setAttrib('maxLength', $this->_maxlengthTelefonos);
        $txtTelefonos->addValidator(
            new Zend_Validate_StringLength(
                array('min'=>1,'max'=> $this->_maxlengthTelefonos)
            )
        );
        $txtTelefonos->errMsg = 'Se requiere tu Nro Telefonico / Celular';
        $this->addElement($txtTelefonos);
        
        $txtEmail = new Zend_Form_Element_Text('email');
        $fEmailVal = new Zend_Validate_EmailAddress(
            array("allow" => Zend_Validate_Hostname::ALLOW_ALL)
        );
        $txtEmail->setRequired();
        $txtEmail->addFilter(new Zend_Filter_StringToLower());
        $txtEmail->addValidator($fEmailVal, true);
        $txtEmail->setAttrib('maxLength', $this->_maxlengthEmail);
        $txtEmail->errMsg = 'No parece ser un correo electrónico válido';
        $this->addElement($txtEmail);
        
        $cboDocumento = new Zend_Form_Element_Select('tipo_documento');
        //$cboDocumento->addMultiOptions(
        //Application_Model_Suscriptor::getTiposDocumentoSuscriptor()
        //);
        $cboDocumento->addMultiOption('DNI#'.$this->_maxlengthNroDni, 'DNI');
        $cboDocumento->addMultiOption('CEX#'.$this->_maxlengthNroCex, 'Carnet Extranjería');
        $cboDocumento->addMultiOption('RUC#'.$this->_maxlengthNroRuc, 'RUC');
        $cboDocumento->addMultiOption('PAS#'.$this->_maxlengthNroPas, 'Pasaporte');
        
        $cboDocumento->setRequired();
        $this->addElement($cboDocumento);
        
        $txtNroDocumento = new Zend_Form_Element_Text('nro_documento');
        $txtNroDocumento->setRequired();
        $txtNroDocumento->addValidator(new Zend_Validate_NotEmpty());
        $txtNroDocumento->addValidator(new Zend_Validate_Int());
        $txtNroDocumento->setAttrib('maxLength', $this->_maxlengthNroDoc);
        /*$txtNroDocumento->addValidator(
            new Zend_Validate_StringLength(array('min'=>8,'max'=> $this->_maxlengthNroDoc))
        );*/
        $txtNroDocumento->errMsg = 'Ingrese nro de documento válido';
        $this->addElement($txtNroDocumento);
        
        $txtareaMensaje = new Zend_Form_Element_Textarea('mensaje');
        $txtareaMensaje->setAttrib('rows', 6);
        //$txtareaMensaje->setAttrib('cols', 34);
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
        if ($this->_config->env2 == 'EC') { // para el Proxy de EC =)
            $recaptcha->getHttpClient()->setConfig(
                array(
                    'adapter' => 'Zend_Http_Client_Adapter_Proxy',
                    'proxy_host' => '172.19.0.4',
                    'proxy_port' => '9090',
                    'user' => '',
                    'password' => '',
                )
            );
        }
        //$recaptcha->setOption('custom_translations', $recaptchaItTranslation);
        //Change theme
        $recaptcha->setOption('theme', 'clean');
        $recaptcha->setOption('lang', 'es');
        //$recaptcha->setOption('custom_theme_widget', 'recaptcha_widget');
        
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
        
        $this->setAction('');
        $this->setMethod('post');
    }
}
