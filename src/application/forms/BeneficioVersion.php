<?php
/**
 * Descripcion del formulario recuperar clave
 *
 * @author Jesus Fabian
 */
class Application_Form_BeneficioVersion extends App_Form
{
    public function init()
    {
        parent::init();

        $elementStock = new Zend_Form_Element_Text('stock');
        $this->addElement($elementStock);

        $elementIniVigencia = new Zend_Form_Element_Text('fecha_inicio_vigencia');
        $this->addElement($elementIniVigencia);

        $elementFinVigencia = new Zend_Form_Element_Text('fecha_fin_vigencia');
        $this->addElement($elementFinVigencia);

        $elementIniPublicacion = new Zend_Form_Element_Text('fecha_inicio_publicacion');
        $this->addElement($elementIniPublicacion);

        $elementFinPublicacion = new Zend_Form_Element_Text('fecha_fin_publicacion');
        $this->addElement($elementFinPublicacion);
    }
}