<?php

/**
 * Descripcion del formulario recuperar clave
 *
 * @author Jesus Fabian
 */
class Application_Form_Beneficio extends App_Form
{
    private $_maxChapita = '3';
//    public static $chapitaColor = array(
//        '#FF0000' => 'rojo',
//        '#00FF00' => 'verde',
//        '#0000FF' => 'azul',
//        '#FFFF00' => 'amarillo',
//        '#FF00FF' => 'fucsia'
//    );
    public static $chapitaColor = array(
        'rojo' => 'rojo',
        'verde' => 'verde',
        'azul' => 'azul',
        'amarillo' => 'amarillo'
    );
    private $_maxDescCorta = '120';
    private $_maxComo = '150';
    private $_maxDescripcion = '500';
    private $_maxTitulo = '45';
    private $_maxValor = '250';
    private $_maxCuando = '250';
    private $_maxDescripcionCupon = '1000';
    private $_maxTerminoCondicionWeb = '2000';
    private $_maxTerminoCondicionCupon = '1500';
    private $_maxDonde = '150';
    private $_maxEmail = '45';
    private $_maxTelefono = '45';
    private $_maxDescripPdfInfo = '40';
    public static $estadosBeneficio = array(
        0 => array(0 => 2, 1 => 1),
        1 => array(0 => 4, 1 => 3)
    );
    public static $fromEstadoBeneficio = array(
        0 => array('publicado' => 1, 'activo' => 1), //publicado
        1 => array('publicado' => 0, 'activo' => 1), //pendiente
        2 => array('publicado' => 0, 'activo' => 0), //borrador
        3 => array('publicado' => 1, 'activo' => 1), //publicado
        4 => array('publicado' => 1, 'activo' => 0)  //vencido
    );
    public static $destacados = array(
        0 => array(0 => 0, 1 => 2),
        1 => array(0 => 1)
    );
    public static $fromDestacado = array(
        0 => array('es_destacado' => 0, 'es_destacado_principal' => 0),
        1 => array('es_destacado' => 1, 'es_destacado_principal' => 0),
        2 => array('es_destacado' => 0, 'es_destacado_principal' => 1),
    );

    public function init()
    {
        parent::init();
        
        $eEstaId = new Zend_Form_Element_Select('establecimiento_id');
        $eEstaId->addMultiOption('0', '-- Seleccione un Establecimiento --');
        $eEstaId->addMultiOptions(
            Application_Model_Establecimiento::getEstablecimientos(array('estado' => 1), true)
        );
        $eEstaId->setRequired(true);
        $eEstaId->addValidator(
            new Zend_Validate_Callback('Application_Model_Establecimiento::esEstablecimientoActivo')
        );
        $eEstaId->errMsg = '¡Debes seleccionar un Establecimiento válido!';
        $this->addElement($eEstaId);

        $eTipoBeneId = new Zend_Form_Element_Select('tipo_beneficio_id');
        $eTipoBeneId->addMultiOption('0', '-- Seleccione el Tipo de Beneficio --');
        $eTipoBeneId->addMultiOptions(
            Application_Model_TipoBeneficio::getTiposBeneficio(true, true)
        );
        $eTipoBeneId->addValidator(
            new Zend_Validate_Callback('Application_Model_TipoBeneficio::esTipoBeneficioActivo')
        );
        $eTipoBeneId->setRequired(true);
        $eTipoBeneId->errMsg = '¡Debes seleccionar un Tipo de Beneficio válido!';
        $this->addElement($eTipoBeneId);

        $eAnuncianteId = new Zend_Form_Element_Select('anunciante_id');
        $eAnuncianteId->addMultiOption('0', '-- Seleccione un Anunciante --');
        $eAnuncianteId->addMultiOptions(
            Application_Model_Anunciante::getAnunciantes(array("estado"=>1), true)
        );
//        $eAnuncianteId->addValidator(
//            new Zend_Validate_Callback('Application_Model_Anunciante::getAnunciantes')
//        );
        $eAnuncianteId->setRequired(true);
        $eAnuncianteId->errMsg = '¡Debes seleccionar un Anunciante válido!';
        $this->addElement($eAnuncianteId);
        
        $eTitulo = new Zend_Form_Element_Text('titulo');
        $eTitulo->addValidator(new Zend_Validate_NotEmpty());
        $eTitulo->setAttrib('maxlength', $this->_maxTitulo);
        $eTitulo->addValidator(
            new Zend_Validate_StringLength(array('max' => $this->_maxTitulo))
        );
        $eTitulo->setRequired(true);
        $eTitulo->errMsg = '¡Se requiere un nombre para el beneficio!';
        $this->addElement($eTitulo);

        $eLogo = new Zend_Form_Element_File('path_logo');
        $eLogo->setDestination($this->_config->paths->elementsPromoRoot);
        $eLogo->addValidator(
            new Zend_Validate_File_Size(array('max' => $this->_config->app->maxSizeFile))
        )->setMaxFileSize(2097152);//3145728);
        $eLogo->addValidator('Extension', false, 'jpg,jpeg,png,gif');
        $this->addElement($eLogo);

        $eChapita = new Zend_Form_Element_Text('chapita');
//        $eChapita->setRequired(true);
        $eChapita->setAttrib('maxlength', $this->_maxChapita);
        $eChapita->addValidator(
            new Zend_Validate_StringLength(array('max' => $this->_maxChapita))
        );
        $eChapitaString = new Zend_Validate_Callback(
            array('callback' => array($this, 'validarStringChapita'))
        );
        $eChapita->addValidator($eChapitaString);
        $eChapita->errMsg = '¡Se requiere contenido válido para la chapita!';
        $this->addElement($eChapita);

        $eChapitaColor = new Zend_Form_Element_Hidden('chapita_color');
//        $eChapitaColor->addMultiOption('0', '-- Seleccione un Color --');
//        $eChapitaColor->addMultiOptions(Application_Form_Beneficio::$chapitaColor);
        $this->addElement($eChapitaColor);

        $eCategoriaDisp = new Zend_Form_Element_Multiselect('categorias_disponibles');
        $this->addElement($eCategoriaDisp);

        $eCategorias = new Zend_Form_Element_Multiselect('categorias_seleccionadas');
        $eCategorias->setRequired(true);
        $eCategorias->errMsg = '¡Se requiere que seleccione al menos una categoría!';
        $this->addElement($eCategorias);
        
        $eiframeH = new Zend_Form_Element_Hidden('iframeH');
        $eiframeH->setRequired(false);
        $this->addElement($eiframeH);
        
        $eDescripcion = new Zend_Form_Element_Textarea('descripcion');
        $eDescripcion->setAttrib('maxlength', $this->_maxDescripcion);
        $eDescripcion->addValidator(
            new Zend_Validate_StringLength(array('max' => $this->_maxDescripcion))
        );
        $eDescripcion->addValidator(new Zend_Validate_NotEmpty());
        $eDescripcion->setRequired(true);
        $eDescripcion->errMsg = '¡Se requiere la descripción del beneficio!';
        $this->addElement($eDescripcion);
        
        $eComo = new Zend_Form_Element_Textarea('como');
        $eComo->setAttrib('maxlength', $this->_maxComo);
        $eComo->addValidator(
            new Zend_Validate_StringLength(array('max' => $this->_maxComo))
        );
        $this->addElement($eComo);
        
        $eDescripcionCorta = new Zend_Form_Element_Textarea('descripcion_corta');
        $eDescripcionCorta->setAttrib('maxlength', $this->_maxDescCorta);
        $eDescripcionCorta->addValidator(
            new Zend_Validate_StringLength(array('max' => $this->_maxDescCorta))
        );
        $eDescripcionCorta->addValidator(new Zend_Validate_NotEmpty());
        $eDescripcionCorta->setRequired(true);
        $eDescripcionCorta->errMsg = '¡Se requiere un resumen de la descripción del beneficio!';
        $this->addElement($eDescripcionCorta);

        $eMaximoPorSuscriptor = new Zend_Form_Element_Text('maximo_por_subscriptor');
        $this->addElement($eMaximoPorSuscriptor);

//        $eIlimitado = new Zend_Form_Element_Checkbox('sin_limite_por_suscriptor');
//        $this->addElement($eIlimitado);
        $eIlimitado = new Zend_Form_Element_Radio('sin_limite_por_suscriptor');
        $eIlimitado->setLabel('')
                   ->addMultiOptions(array('1' => ' Ilimitado','0' => ' Max. general','2' => ' Max. detalle'))
                    ->setValue('0')
                   ->setSeparator('');
        $this->addElement($eIlimitado);
        
        $eTipoRedencion = new Zend_Form_Element_Radio('tipo_redencion');
        $eTipoRedencion->setLabel('')
                   ->addMultiOptions(array('0' => ' Cantidad ','1' => ' Porcentaje '))
                    ->setValue('0')
                   ->setSeparator('');
        $this->addElement($eTipoRedencion);

        $eValor = new Zend_Form_Element_Textarea('valor');
        $eValor->setAttrib('maxlength', $this->_maxValor);
        $this->addElement($eValor);

        $eCuando = new Zend_Form_Element_Textarea('cuando');
        $eCuando->setAttrib('maxlength', $this->_maxCuando);
        $this->addElement($eCuando);

        $eDireccion = new Zend_Form_Element_Textarea('direccion');
        $eDireccion->setAttrib('maxlength', $this->_maxDonde);
        $this->addElement($eDireccion);

        $eEmailInfo = new Zend_Form_Element_Text('email_info');
        $eEmailInfo->setAttrib('maxlength', $this->_maxEmail);
        $this->addElement($eEmailInfo);
        $eEmailInfoEsta = new Zend_Form_Element_Checkbox('email_info_establecimiento');
        $this->addElement($eEmailInfoEsta);
        
        $eTelefonoInfo = new Zend_Form_Element_Text('telefono_info');
        $eTelefonoInfo->setAttrib('maxlength', $this->_maxTelefono);
        $this->addElement($eTelefonoInfo);
        $eTelefonoInfoEsta = new Zend_Form_Element_Checkbox('telefono_info_establecimiento');
        $this->addElement($eTelefonoInfoEsta);
        
        $tipoMoneda = new Zend_Form_Element_Select('tipo_moneda_id');
        $tipoMoneda->addMultiOptions(
            Application_Model_TipoMoneda::getTipoMoneda(array('activo' => 1), true)
        );
        $this->addElement($tipoMoneda);
        
        $eSinStock = new Zend_Form_Element_Radio('sin_stock');
        $eSinStock->setLabel('')
                   ->addMultiOptions(array('1' => ' Sin Stock','0' => ' Stock general','2' => ' Stock por detalle'))
                    ->setValue('0')
                   ->setSeparator('');
        $this->addElement($eSinStock);
        
        $eDestacadoBanner = new Zend_Form_Element_Checkbox('es_destacado_banner');
        $this->addElement($eDestacadoBanner);

        $eGenerarCupon = new Zend_Form_Element_Checkbox('generar_cupon');
        $this->addElement($eGenerarCupon);
        
        $eDescripcionCupon = new Zend_Form_Element_Textarea('descripcion_cupon');
        $eDescripcionCupon->setAttrib('maxlength', $this->_maxDescripcionCupon);
        $eDescripcionCupon->addValidator(
            new Zend_Validate_StringLength(array('max' => $this->_maxDescripcionCupon))
        );
        $eDescripcionCupon->errMsg = '¡Se requiere una descripcion maximo '.
                $this->_maxDescripcionCupon.' caracteres!';
        $this->addElement($eDescripcionCupon);
        
        $eTerminoCondicionWeb = new Zend_Form_Element_Textarea('terminos_condiciones_web');
        $eTerminoCondicionWeb->setAttrib('maxlength', $this->_maxTerminoCondicionWeb);
        $eTerminoCondicionWeb->addValidator(
            new Zend_Validate_StringLength(array('max' => $this->_maxTerminoCondicionWeb))
        );
        $eTerminoCondicionWeb->errMsg = '¡Se requiere una descripcion maximo '.
                $this->_maxTerminoCondicionWeb.' caracteres!';
        $this->addElement($eTerminoCondicionWeb);
        
        $eTerminoCondicionCupon = new Zend_Form_Element_Textarea('terminos_condiciones_cupon');
        $eTerminoCondicionCupon->setAttrib('maxlength', $this->_maxTerminoCondicionCupon);
        $eTerminoCondicionCupon->addValidator(
            new Zend_Validate_StringLength(array('max' => $this->_maxTerminoCondicionCupon))
        );
        $eTerminoCondicionCupon->errMsg = '¡Se requiere una descripcion maximo '.
                $this->_maxTerminoCondicionCupon.' caracteres!';
        $this->addElement($eTerminoCondicionCupon);

        $eDestacado = new Zend_Form_Element_Select('destacado');
        $eDestacado->addMultiOption('0', 'No destacado');
        $eDestacado->addMultiOption('1', 'Descatado');
        $eDestacado->addMultiOption('2', 'Destacado Principal');
        $eDestacado->setRequired(true);
        $eDestacado->errMsg = '¡Se requiere que seleccione el tipo de destaque!';
        $this->addElement($eDestacado);
        
        $eSorteoResultado = new Zend_Form_Element_File('pdf_resultado');
        $eSorteoResultado->addValidator('Extension', false, 'pdf');
        $this->addElement($eSorteoResultado);
        
        $eFileName = new Zend_Form_Element_Text('pdf_name');
//        $eFileName->addValidator('Extension', false, 'pdf');
        $eFileName->errMsg = '¡Se requiere que ingrese el archivo PDF con los resultados!';
        $this->addElement($eFileName);
        
        $eInformacionPdf = new Zend_Form_Element_File('pdf_info');
        $eInformacionPdf->addValidator('Extension', false, 'pdf');
        $this->addElement($eInformacionPdf);
        
        $eFileInfoName = new Zend_Form_Element_Text('pdf_info_name');
        $eFileInfoName->errMsg = '¡Se requiere que ingrese el archivo PDF informacion!';
        $this->addElement($eFileInfoName);
        
        $eFileInfoDescrip = new Zend_Form_Element_Text('pdf_info_descrip');
        $eFileInfoDescrip->setAttrib('maxlength', $this->_maxDescripPdfInfo);
        $eFileInfoDescrip->errMsg = '¡Se requiere que ingrese el nombre PDF informacion!';
        $this->addElement($eFileInfoDescrip);
        
//        $eTipoRedencion = new Zend_Form_Element_Hidden('tipo_redencion');
//        $this->addElement($eTipoRedencion);
        
        $eInfo = new Zend_Form_Element_Textarea('informacion_adicional');
        $this->addElement($eInfo);
        
//        $this->addSubForm($form, $name)
    }

    /**
     * Carga los options de las categorias en el formulario
     * @param type $e Nombre del control categorias_disponibles o categorias_seleccionadas
     * @param type $ids Data
     */
    public function fillCategorias($e, $ids = array(), $in = true)
    {
        $categorias
            = Application_Model_Categoria::getCategorias(true, array('activo' => 1), $in, $ids);
        $this->getElement($e)->addMultiOptions($categorias);
    }

    public function initVigente()
    {
        $eEstado = new Zend_Form_Element_Select('estado');
        $eEstado->addMultiOption('1', 'Vigente');
        $eEstado->addMultiOption('0', 'No vigente');
        $this->addElement($eEstado);
    }

    public function fillOpcionEstados($actual = 2)
    {
        switch ($actual) {
            case 1: // Pendiente
                $eEstado = new Zend_Form_Element_Select('estado');
                $eEstado->setLabel('Estado');
                $eEstado->setAttribs(array('id' => 'estado', 'class' => ' left '));
                $eEstado->setRequired(true);
                $eEstado->errMsg = '¡Se seleccione un estado para el beneficio!';
                $eEstado->addMultiOption('1', 'Pendiente');
                $eEstado->addMultiOption('2', 'Borrador');
                $eEstado->addMultiOption('3', 'Publicar');
                $this->addElement($eEstado);
                break;
            case 2: // Borrador
                $eEstado = new Zend_Form_Element_Select('estado');
                $eEstado->setLabel('Estado');
                $eEstado->setRequired(true);
                $eEstado->errMsg = '¡Se seleccione un estado para el beneficio!';
                $eEstado->setAttribs(array('id' => 'estado', 'class' => ' left '));
                $eEstado->addMultiOption('1', 'Pendiente');
                $eEstado->addMultiOption('2', 'Borrador');
                $eEstado->addMultiOption('3', 'Publicar');
                $this->addElement($eEstado);
                break;
            case 3: // Publicado
                $eEstado = new Zend_Form_Element_Checkbox('estado');
                $eEstado->setLabel('Vencer Beneficio');
                $eEstado->setAttribs(array('id' => 'estado', 'class' => 'ml20 mT5'));
                $eEstado->addDecorator(
                    'Label', array('class' => 'ml20 mT5', 'id' => 'estado', 'for' => 'estado')
                );
                $eEstado->setCheckedValue(4);
                $this->addElement($eEstado);
                break;
            case 4: // Vencido
//                $eEstado = new Zend_Form_Element_Text('estado');
//                $eEstado->setAttribs(array('id' => 'estado', 'class' => 'inputEB left editBene'));
//                $eEstado->setLabel('Estado');
//                $eEstado->setValue('Vencido - No se puede editar el beneficio');
//                $eEstado->helper = 'formNote';
//                $this->addElement($eEstado);
                $eEstado = new Zend_Form_Element_Select('estado');
                $eEstado->setLabel('Estado');
                $eEstado->setRequired(true);
                $eEstado->errMsg = '¡Se seleccione un estado para el beneficio!';
                $eEstado->setAttribs(array('id' => 'estado', 'class' => ' left '));
                $eEstado->addMultiOption('4', 'Vencido');
                $eEstado->addMultiOption('1', 'Pendiente');
                $eEstado->addMultiOption('2', 'Borrador');
                $eEstado->addMultiOption('3', 'Publicar');
                $this->addElement($eEstado);
                break;
        }
    }
    
    public static function verificarAtivoXFechas($fechaIni,$fechaFin)
    {
        $return = 0;
        $fechaActual = date('Ymd');
//        $fechaActual=20120409;
        list($diaI, $mesI, $anioI) = explode("/", $fechaIni);
        list($diaF, $mesF, $anioF) = explode("/", $fechaFin);
        if (($anioI . $mesI . $diaI) <= $fechaActual && ($anioF . $mesF . $diaF) >= $fechaActual)
            $return = 1;
        return $return;
    }
    
    public function setDestacado($value = 0)
    {
        $this->getElement('destacado')->setValue($value);
    }

    public function setEstado($value = 2)
    {
        if ($value != 3)
            $this->getElement('estado')->setValue($value);
    }

    public function setChapitaColor($color = 'rojo')
    {
        $this->getElement('chapita_color')->setValue($color);
    }

    public function validarStringChapita($string)
    {
        return preg_match('/^[0-9]{1}[x][0-9]{1}$|^[0-9]{1,2}[%]$/', $string);
    }
    
    public function uploadPDF($req = true)
    {
        if($req)
            $this->getElement('pdf_name')->addValidator(new Zend_Validate_NotEmpty());
        $this->getElement('pdf_name')->setRequired($req);
//        $this->getElement('pdf_name')->errMsg = 
    }

}
