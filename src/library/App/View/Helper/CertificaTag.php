<?php

/**
 * Description of Attribs
 *
 * @author eanaya
 */
class App_View_Helper_CertificaTag extends Zend_View_Helper_HtmlElement
{

    public function CertificaTag($modulo = "", $controlador = "", $accion = "")
    {
        $certifica = "";
        
        switch ($modulo) {
            case "suscriptor":
                switch ($controlador) {
                    case "home": 
                        switch ($accion) {
                            case "index": 
                                $certifica = "/online/portada/";
                                break;
                            case "servicios":
                                $certifica = "/online/servicios-al-suscriptor/";
                                break;
                            case "contacto":
                                $certifica = "/online/$accion/";
                                break;
                            case "suscribete":
                                $certifica = "/online/$accion/";
                                break;
                            case "preguntas-frecuentes":
                                $certifica = "/online/$accion/";
                                break;
                        }
                        break;
                    case "beneficio":
                        switch ($accion) {
                            case "index":
                                $certifica = "/online/catalogo/";
                                break;
                            case "ver":
                                $certifica = "/online/catalogo-detalle/";
                                break;
                        }
                        break; 
                    case "vida-social":
                        $certifica = "/online/vida-social/";
                        break;
                    case "registro":
                        $certifica = "/online/registro/";
                        break;
                    case "error":
                        $certifica = "/online/pagina404/";
                        break;
                }
                break;
        }
        
        if ($certifica == "") {
            $certifica = "/online/otros";
        }
        
        //var_dump($certifica);
       
        return PHP_EOL.
        '<!-- Start Certifica Tag -->' . PHP_EOL .
        '<script src="http://b.scorecardresearch.com/c2/6906602/cs.js#' .
        'sitio_id=235332&path=' .  $certifica . '"></script>' . PHP_EOL .
        '<!-- End Certifica Tag -->' . PHP_EOL ;
    }

}