<?php

class Application_Form_InteresBeneficioClup extends App_Form
{    
    public function init()
    {
        parent::init();        
        //-----------------------------------INICIO BENEFICIOS---------------------------------
        $beneficio_conciertos = new Zend_Form_Element_Select('beneficio_conciertos');        
        $this->addSelect($beneficio_conciertos);        
        $beneficio_conciertos->setRequired();
        $this->addElement($beneficio_conciertos);
        
        $beneficio_teatro = new Zend_Form_Element_Select('beneficio_teatro');        
        $this->addSelect($beneficio_teatro);        
        $beneficio_teatro->setRequired();
        $this->addElement($beneficio_teatro);
        
        $beneficio_opera = new Zend_Form_Element_Select('beneficio_opera');        
        $this->addSelect($beneficio_opera);        
        $beneficio_opera->setRequired();
        $this->addElement($beneficio_opera);
        
        $beneficio_ballet = new Zend_Form_Element_Select('beneficio_ballet');        
        $this->addSelect($beneficio_ballet);        
        $beneficio_ballet->setRequired();
        $this->addElement($beneficio_ballet);
        
        $beneficio_shows_ninos = new Zend_Form_Element_Select('beneficio_shows_ninos');        
        $this->addSelect($beneficio_shows_ninos);        
        $beneficio_shows_ninos->setRequired();
        $this->addElement($beneficio_shows_ninos);
        
        $beneficio_deportes = new Zend_Form_Element_Select('beneficio_deportes');        
        $this->addSelect($beneficio_deportes);        
        $beneficio_deportes->setRequired();
        $this->addElement($beneficio_deportes);
        
        $beneficio_avantpremiere = new Zend_Form_Element_Select('beneficio_avantpremiere');        
        $this->addSelect($beneficio_avantpremiere);        
        $beneficio_avantpremiere->setRequired();
        $this->addElement($beneficio_avantpremiere);
        
        $beneficio_cines = new Zend_Form_Element_Select('beneficio_cines');        
        $this->addSelect($beneficio_cines);        
        $beneficio_cines->setRequired();
        $this->addElement($beneficio_cines);
        
        $beneficio_seminarios_cursos = new Zend_Form_Element_Select('beneficio_seminarios_cursos');        
        $this->addSelect($beneficio_seminarios_cursos);        
        $beneficio_seminarios_cursos->setRequired();
        $this->addElement($beneficio_seminarios_cursos);
        
        $beneficio_ferias = new Zend_Form_Element_Select('beneficio_ferias');        
        $this->addSelect($beneficio_ferias);        
        $beneficio_ferias->setRequired();
        $this->addElement($beneficio_ferias);
        
        $beneficio_paquetes_turisticos = new Zend_Form_Element_Select('beneficio_paquetes_turisticos');        
        $this->addSelect($beneficio_paquetes_turisticos);        
        $beneficio_paquetes_turisticos->setRequired();
        $this->addElement($beneficio_paquetes_turisticos);
        
        $beneficio_boletos_aereos_nacionales = new Zend_Form_Element_Select('beneficio_boletos_aereos_nacionales');        
        $this->addSelect($beneficio_boletos_aereos_nacionales);        
        $beneficio_boletos_aereos_nacionales->setRequired();
        $this->addElement($beneficio_boletos_aereos_nacionales);
        
        $beneficio_cierra_puertas = new Zend_Form_Element_Select('beneficio_cierra_puertas');        
        $this->addSelect($beneficio_cierra_puertas);        
        $beneficio_cierra_puertas->setRequired();
        $this->addElement($beneficio_cierra_puertas);
        
        $beneficio_vinos = new Zend_Form_Element_Select('beneficio_vinos');        
        $this->addSelect($beneficio_vinos);        
        $beneficio_vinos->setRequired();
        $this->addElement($beneficio_vinos);
        //-------------------------------------FIN BENEFICIOS-------------------------------        
    }
    
    function addSelect($obj){        
        $obj->addMultiOption(1, '1. Nada Interesado');
        $obj->addMultiOption(2, 2);
        $obj->addMultiOption(3, 3);
        $obj->addMultiOption(4, 4);
        $obj->addMultiOption(5, '5. Muy Interesado');
    }
}
