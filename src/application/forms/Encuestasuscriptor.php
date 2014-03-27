<?php

class Application_Form_Encuestasuscriptor extends App_Form
{
    //Max Length
    private $_maxlengthPregunta = '60';
    private $_minlengthPregunta = '0';
    
    public function init()
    {
        parent::init();
        
        $clasificacion_beneficio_club = new Zend_Form_Element_Select('clasificacion_beneficio_club');        
        $this->addSelect($clasificacion_beneficio_club);        
        $clasificacion_beneficio_club->setRequired();
        $this->addElement($clasificacion_beneficio_club);
        
        $participacion_promocion_club = new Zend_Form_Element_Radio('participacion_promocion_club');
        $participacion_promocion_club->addMultiOption(1, "Si");
        $participacion_promocion_club->addMultiOption(0, "No");
        $participacion_promocion_club->setValue(1);
        $participacion_promocion_club->setSeparator('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
        $this->addElement($participacion_promocion_club);
        
        $noparticipo_porque = new Zend_Form_Element_Radio('noparticipo_porque');
        $noparticipo_porque->addMultiOption(1, "No le gusta participar en promociones");
        $noparticipo_porque->addMultiOption(2, "Ninguna promoción le pareció atractiva");
        $noparticipo_porque->addMultiOption(3, "Otros");
        $noparticipo_porque->setValue(1);        
        $this->addElement($noparticipo_porque);        
        
        
        $noparticipo_otros_comentario = new Zend_Form_Element_Text('noparticipo_otros_comentario');
        //$noparticipo_otros_comentario->setRequired();
        $noparticipo_otros_comentario->setAttrib('maxLength', $this->_maxlengthPregunta);
        $noparticipo_otros_comentario->addValidator(
            new Zend_Validate_StringLength(
                array('min'=> $this->_minlengthPregunta, 'max'=> $this->_maxlengthPregunta)
            )
        );
        
        //$noparticipo_otros_comentario->addValidator(new Zend_Validate_NotEmpty());
        //$noparticipo_otros_comentario->errMsg = 'Campo Requerido';
        $this->addElement($noparticipo_otros_comentario);
        
        $otro_beneficio_club = new Zend_Form_Element_Text('otro_beneficio_club');
        //$otro_beneficio_club->setRequired();
        $otro_beneficio_club->setAttrib('maxLength', $this->_maxlengthPregunta);
        $otro_beneficio_club->addValidator(
            new Zend_Validate_StringLength(
                array('min'=> $this->_minlengthPregunta, 'max'=> $this->_maxlengthPregunta)
            )
        );
        //$otro_beneficio_club->addValidator(new Zend_Validate_NotEmpty());
        //$otro_beneficio_club->errMsg = 'Campo Requerido';
        $this->addElement($otro_beneficio_club);
        
        
        //oportunamente_beneficios_ofrecidos
        
        $oportunamente_beneficios_ofrecidos = new Zend_Form_Element_Radio('oportunamente_beneficios_ofrecidos');
        $oportunamente_beneficios_ofrecidos->addMultiOption(1, "Si");
        $oportunamente_beneficios_ofrecidos->addMultiOption(0, "No");
       
        $oportunamente_beneficios_ofrecidos->setValue(1);
        $oportunamente_beneficios_ofrecidos->setSeparator('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
        $this->addElement($oportunamente_beneficios_ofrecidos);
        
        $oportunamente_beneficios_elegir = new Zend_Form_Element_Radio('oportunamente_beneficios_elegir');
        $oportunamente_beneficios_elegir->addMultiOption(1, "Correo electrónico (newsletter)");
        $oportunamente_beneficios_elegir->addMultiOption(2, "Diario (avisos y notas)");
        $oportunamente_beneficios_elegir->addMultiOption(3, "Página Web");
        $oportunamente_beneficios_elegir->addMultiOption(4, "Otros");       
        $oportunamente_beneficios_elegir->setValue(1);
        $this->addElement($oportunamente_beneficios_elegir);
        
        $interesado_propuesta_cv = new Zend_Form_Element_Select('interesado_propuesta_cv');        
        $this->addSelectClupVino($interesado_propuesta_cv);        
        $interesado_propuesta_cv->setRequired();
        $this->addElement($interesado_propuesta_cv);
        
        $donde_compra_cv = new Zend_Form_Element_Radio('donde_compra_cv');
        $donde_compra_cv->addMultiOption(1, "Supermercados");
        $donde_compra_cv->addMultiOption(2, "Tiendas Especializadas");
        $donde_compra_cv->addMultiOption(3, "Tiendas por internet");
        $donde_compra_cv->addMultiOption(4, "Otros");
        $donde_compra_cv->addMultiOption(5, "No compro regularmente");
        $donde_compra_cv->setValue(1);        
        $this->addElement($donde_compra_cv);
        
        $usualmente_otro_comentario_cv = new Zend_Form_Element_Text('usualmente_otro_comentario_cv');
        //$usualmente_otro_comentario_cv->setRequired();
        $usualmente_otro_comentario_cv->setAttrib('maxLength', $this->_maxlengthPregunta);
        $usualmente_otro_comentario_cv->addValidator(
            new Zend_Validate_StringLength(
                array('min'=> $this->_minlengthPregunta, 'max'=> $this->_maxlengthPregunta)
            )
        );
        //$usualmente_otro_comentario_cv->addValidator(new Zend_Validate_NotEmpty());
        //$usualmente_otro_comentario_cv->errMsg = 'Campo Requerido';
        $this->addElement($usualmente_otro_comentario_cv);
        
        $importacion_atributos_tipovino_cv = new Zend_Form_Element_Select('importacion_atributos_tipovino_cv');        
        $this->addSelectAtributos($importacion_atributos_tipovino_cv);
        $importacion_atributos_tipovino_cv->setRequired();
        $this->addElement($importacion_atributos_tipovino_cv);
        
        $importacion_atributos_marca_cv = new Zend_Form_Element_Select('importacion_atributos_marca_cv');        
        $this->addSelectAtributos($importacion_atributos_marca_cv);        
        $importacion_atributos_marca_cv->setRequired();
        $this->addElement($importacion_atributos_marca_cv);
        
        $importacion_atributos_origen_cv = new Zend_Form_Element_Select('importacion_atributos_origen_cv');        
        $this->addSelectAtributos($importacion_atributos_origen_cv);        
        $importacion_atributos_origen_cv->setRequired();
        $this->addElement($importacion_atributos_origen_cv);
        
        $importacion_atributos_prestigio_cv = new Zend_Form_Element_Select('importacion_atributos_prestigio_cv');        
        $this->addSelectAtributos($importacion_atributos_prestigio_cv);        
        $importacion_atributos_prestigio_cv->setRequired();
        $this->addElement($importacion_atributos_prestigio_cv);
        
        $importacion_atributos_eddadvino_cv = new Zend_Form_Element_Select('importacion_atributos_eddadvino_cv');        
        $this->addSelectAtributos($importacion_atributos_eddadvino_cv);        
        $importacion_atributos_eddadvino_cv->setRequired();
        $this->addElement($importacion_atributos_eddadvino_cv);
        
        $importacion_atributos_precio_cv = new Zend_Form_Element_Select('importacion_atributos_precio_cv');        
        $this->addSelectAtributos($importacion_atributos_precio_cv);        
        $importacion_atributos_precio_cv->setRequired();
        $this->addElement($importacion_atributos_precio_cv);
       
                
        $importacion_atributos_otros = new Zend_Form_Element_Checkbox('importacion_atributos_otros');
        $importacion_atributos_otros->setCheckedValue('1');
        $importacion_atributos_otros->setUncheckedValue('0');        
        $importacion_atributos_otros->setChecked(false);
        $this->addElement($importacion_atributos_otros);
        
        
        $importacion_atributos_otroscomentario_cv = new Zend_Form_Element_Text('importacion_atributos_otroscomentario_cv');
        //$otro_beneficio_club->setRequired();
        $importacion_atributos_otroscomentario_cv->setAttrib('maxLength', $this->_maxlengthPregunta);
        $importacion_atributos_otroscomentario_cv->addValidator(
            new Zend_Validate_StringLength(
                array('min'=> $this->_minlengthPregunta, 'max'=> $this->_maxlengthPregunta)
            )
        );
        //$usualmente_otro_comentario_cv->addValidator(new Zend_Validate_NotEmpty());
        //$usualmente_otro_comentario_cv->errMsg = 'Campo Requerido';
        $this->addElement($importacion_atributos_otroscomentario_cv);
        
        $dispuesto_pagar_cv = new Zend_Form_Element_Radio('dispuesto_pagar_cv');
        $dispuesto_pagar_cv->addMultiOption(1, "S/.50.00");
        $dispuesto_pagar_cv->addMultiOption(2, "De S/. 50.00 a S/. 100.00");
        $dispuesto_pagar_cv->addMultiOption(3, "De S/. 100.00 a S/. 200.00");
        $dispuesto_pagar_cv->addMultiOption(4, "De S/. 200.00 a S/. 500.00");
        $dispuesto_pagar_cv->addMultiOption(5, "Más de S/. 500.00");
        $dispuesto_pagar_cv->setValue(1);        
        $this->addElement($dispuesto_pagar_cv);
                
        $btnEnviar = new Zend_Form_Element_Submit('btnEnviar');
        $btnEnviar->setLabel('Enviar');
        $this->addElement($btnEnviar);
    }
    
    function addSelect($obj){        
        $obj->addMultiOption(1, '1. Nada Satisfactoria');
        $obj->addMultiOption(2, 2);
        $obj->addMultiOption(3, 3);
        $obj->addMultiOption(4, 4);
        $obj->addMultiOption(5, '5. Muy Satisfactoria');
    }
    
    function addSelectClupVino($obj){        
        $obj->addMultiOption(1, '1. Nada Interesado');
        $obj->addMultiOption(2, 2);
        $obj->addMultiOption(3, 3);
        $obj->addMultiOption(4, 4);
        $obj->addMultiOption(5, '5. Muy Interesado');
    }
        
    function addSelectAtributos($obj){        
        $obj->addMultiOption(1, '1. Nada Satisfecho');
        $obj->addMultiOption(2, 2);
        $obj->addMultiOption(3, 3);
        $obj->addMultiOption(4, 4);
        $obj->addMultiOption(5, '5. Muy Satisfecho');
    }
    
}
