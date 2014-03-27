<?php

/**
 * Descripcion del formulario recuperar clave
 *
 * @author Favio Condori
 */
class Application_Form_DetalleBeneficio extends App_Form
{
    protected $_codigoMaxLength = 100;
    protected $_montoMaxLength = 8;
    protected $_cantidadMaxLength = 5;
    protected $_cantidadMaxMaxSuscriptor = 4;
    protected $_porcentajeDescuento = 2;
    protected $_descripcionMaxLength = 250;
        
    public function init()
    {
        $codigo = new Zend_Form_Element_Text('d_codigo');
        $codigo->setAttrib('maxLength', $this->_codigoMaxLength);
        $this->addElement($codigo);
        
//        $descuento = new Zend_Form_Element_Text('d_descuento');
//        $descuento->setAttrib('maxLength', $this->_montoMaxLength);
//        $num = new Zend_Validate_Float();
//        $descuento->addValidator($num);
//        $this->addElement($descuento);
        
        $descripcion = new Zend_Form_Element_Text('d_descripcion');
        $descripcion->setAttrib('maxLength', $this->_descripcionMaxLength);
        $descripcion->setAttrib('size', '14');
        $this->addElement($descripcion);
        
        $maximoXsuscriptor = new Zend_Form_Element_Text('d_maximo_por_suscriptor');
        $maximoXsuscriptor->setAttrib('maxLength', $this->_cantidadMaxMaxSuscriptor);
        $num = new Zend_Validate_Int();
        $maximoXsuscriptor->addValidator($num);
        $this->addElement($maximoXsuscriptor);
        
//        $tipoMoneda = new Zend_Form_Element_Select('d_tipo_moneda');
//        $tipoMoneda->addMultiOptions(
//            Application_Model_TipoMoneda::getTipoMoneda(array('activo' => 1), true)
//        );
//        $this->addElement($tipoMoneda);
        
        $cantidad = new Zend_Form_Element_Text('d_cantidad');
        $cantidad->setAttrib('maxLength', $this->_cantidadMaxLength);
        $int = new Zend_Validate_Int();
        $cantidad->addValidator($int);
        $this->addElement($cantidad);
        
//        $stock = new Zend_Form_Element_Text('d_stock_actual');
//        $int = new Zend_Validate_Int();
//        $stock->addValidator($int);
//        $this->addElement($stock);
    }
    
    public function add_TipoRedencion($opcion=null)
    {
        if ($opcion==1) {
            $porcentajeDescuento = new Zend_Form_Element_Text('d_porcentaje_descuento');
            $porcentajeDescuento->setAttrib('maxLength', $this->_porcentajeDescuento);
            $num = new Zend_Validate_Int();
            $porcentajeDescuento->addValidator($num);
            $this->addElement($porcentajeDescuento);
        } else {
            $precioRegular = new Zend_Form_Element_Text('d_precio_regular');
            $precioRegular->setAttrib('maxLength', $this->_montoMaxLength);
            $num = new Zend_Validate_Float();
            $precioRegular->addValidator($num);
            $this->addElement($precioRegular);

            $precioSuscriptor = new Zend_Form_Element_Text('d_precio_suscriptor');
            $precioSuscriptor->setAttrib('maxLength', $this->_montoMaxLength);
            $num = new Zend_Validate_Float();
            $precioSuscriptor->addValidator($num);
            $this->addElement($precioSuscriptor);
            
            $descuento = new Zend_Form_Element_Text('d_descuento');
            $descuento->setAttrib('readonly', 'readonly');
            $descuento->setAttrib('maxLength', $this->_montoMaxLength);
            $num = new Zend_Validate_Float();
            $descuento->addValidator($num);
            $this->addElement($descuento);
        }
    }
    
    public function add_hiddenId()
    {
        $e = new Zend_Form_Element_Hidden('d_hidden');
        $this->addElement($e);        
    }
    
    public function add_cupon()
    {
        $e = new Zend_Form_Element_Hidden('d_cupon');
        $this->addElement($e);        
    }
}
