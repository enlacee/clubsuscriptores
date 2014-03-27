<?php

class Application_Form_SatisfechoBeneficiosClup extends App_Form
{    
    public function init()
    {
        parent::init();    
        //-----------------------------------INICIO BENEFICIOS CLUP---------------------------------
        $beneficioclup_conciertos = new Zend_Form_Element_Select('beneficioclup_conciertos');        
        $this->addSelect($beneficioclup_conciertos);        
        $beneficioclup_conciertos->setRequired();
        $this->addElement($beneficioclup_conciertos);
        
        $beneficioclup_teatro = new Zend_Form_Element_Select('beneficioclup_teatro');        
        $this->addSelect($beneficioclup_teatro);        
        $beneficioclup_teatro->setRequired();
        $this->addElement($beneficioclup_teatro);
        
        $beneficioclup_opera = new Zend_Form_Element_Select('beneficioclup_opera');        
        $this->addSelect($beneficioclup_opera);        
        $beneficioclup_opera->setRequired();
        $this->addElement($beneficioclup_opera);
        
        $beneficioclup_ballet = new Zend_Form_Element_Select('beneficioclup_ballet');        
        $this->addSelect($beneficioclup_ballet);        
        $beneficioclup_ballet->setRequired();
        $this->addElement($beneficioclup_ballet);
        
        $beneficioclup_shows_ninos = new Zend_Form_Element_Select('beneficioclup_shows_ninos');        
        $this->addSelect($beneficioclup_shows_ninos);        
        $beneficioclup_shows_ninos->setRequired();
        $this->addElement($beneficioclup_shows_ninos);
        
        $beneficioclup_deportes = new Zend_Form_Element_Select('beneficioclup_deportes');        
        $this->addSelect($beneficioclup_deportes);        
        $beneficioclup_deportes->setRequired();
        $this->addElement($beneficioclup_deportes);
        
        $beneficioclup_avantpremiere = new Zend_Form_Element_Select('beneficioclup_avantpremiere');        
        $this->addSelect($beneficioclup_avantpremiere);        
        $beneficioclup_avantpremiere->setRequired();
        $this->addElement($beneficioclup_avantpremiere);
        
        $beneficioclup_cines = new Zend_Form_Element_Select('beneficioclup_cines');        
        $this->addSelect($beneficioclup_cines);        
        $beneficioclup_cines->setRequired();
        $this->addElement($beneficioclup_cines);
        
        $beneficioclup_seminarios_cursos = new Zend_Form_Element_Select('beneficioclup_seminarios_cursos');        
        $this->addSelect($beneficioclup_seminarios_cursos);        
        $beneficioclup_seminarios_cursos->setRequired();
        $this->addElement($beneficioclup_seminarios_cursos);
        
        $beneficioclup_ferias = new Zend_Form_Element_Select('beneficioclup_ferias');        
        $this->addSelect($beneficioclup_ferias);        
        $beneficioclup_ferias->setRequired();
        $this->addElement($beneficioclup_ferias);
        
        $beneficioclup_paquetes_turisticos = new Zend_Form_Element_Select('beneficioclup_paquetes_turisticos');        
        $this->addSelect($beneficioclup_paquetes_turisticos);        
        $beneficioclup_paquetes_turisticos->setRequired();
        $this->addElement($beneficioclup_paquetes_turisticos);
        
        $beneficioclup_boletos_aereos_nacionales = new Zend_Form_Element_Select('beneficioclup_boletos_aereos_nacionales');        
        $this->addSelect($beneficioclup_boletos_aereos_nacionales);        
        $beneficioclup_boletos_aereos_nacionales->setRequired();
        $this->addElement($beneficioclup_boletos_aereos_nacionales);
        
        $beneficioclup_cierra_puertas = new Zend_Form_Element_Select('beneficioclup_cierra_puertas');        
        $this->addSelect($beneficioclup_cierra_puertas);        
        $beneficioclup_cierra_puertas->setRequired();
        $this->addElement($beneficioclup_cierra_puertas);
        
        $beneficioclup_vinos = new Zend_Form_Element_Select('beneficioclup_vinos');        
        $this->addSelect($beneficioclup_vinos);        
        $beneficioclup_vinos->setRequired();
        $this->addElement($beneficioclup_vinos);
        //-------------------------------------FIN BENEFICIOS CLUP-------------------------------
    }
    
    function addSelect($obj){        
        $obj->addMultiOption(1, '1. Nada Satisfecho');
        $obj->addMultiOption(2, 2);
        $obj->addMultiOption(3, 3);
        $obj->addMultiOption(4, 4);
        $obj->addMultiOption(5, '5. Muy Satisfecho');
    }    
    
}
