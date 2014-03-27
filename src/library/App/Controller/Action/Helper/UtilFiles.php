<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UtilFiles
 *
 * @author Solman Vaisman
 */
class App_Controller_Action_Helper_UtilFiles extends Zend_Controller_Action_Helper_Abstract
{

    
    public function _creaPathImg($ext, $ancho, $alto, $calidad, $nombre = '')
    {
        $salt = 'cs';
        $microTime = microtime();
        $rename = $nombre == '' ? rand(1000, 9999) : $nombre . "-" .
            md5($microTime . $salt) . "_" . $ext;
        $nombrefinal = $rename . "_" . $ancho . "x" . $alto . "_q" .
            $calidad . ".";
        return $nombrefinal;
    }
    public function _creaPathImgEstablecimiento($ext, $ancho, $alto, $calidad)
    {
        $salt = 'cs';
        $microTime = microtime();
        $rename = rand(1000, 9999) . "-" .
            md5($microTime . $salt) . "_" . $ext;
        $nombrefinal = $rename . "_" . $ancho . "x" . $alto . "_q" .
            $calidad . "." . $ext;
        return $nombrefinal;
    }

    /*
     * Superfuncion que devuelve un arreglo de tres posiciones con los nombres de las
     * imagenes subidas en distintos tamaños, siempre y cuando el tercer parametro
     * sea "image" si fuese otra cosa lo que hara es subir un curriculo y devuelve
     * el nombre generado de ese curriculo.
     */
    public function _renameFile(Zend_Form $form, $path, $auth="promo", $arr = array())
    {
        $file = $form->$path->getFileName();
        $nuevoNombre = '';
        if ($file != null) {
            $microTime = microtime();
            $salt = 'cs';
            $nombreOriginal = pathinfo($file);
            $rename = "";
            $config = Zend_Registry::get("config");

            if ($auth == "promo") {
                $rename = "Promo-" . rand(1000, 9999) . "-" .
                        md5($microTime . $salt);

                $calidad = $config->logobeneficio->calidadcompresion;
                $nuevoNombre = $rename . "_" . $calidad . "." .$nombreOriginal['extension'];
                try {
                    $form->$path->addFilter('Rename', $nuevoNombre);
                    $form->$path->receive();
                } catch(Exception $e) {
                    echo $e->getMessage();
                }
//                echo $nuevoNombre;
                
                //para destacados
                $ancho = $config->logobeneficio->tamano->destacado->w;
                $alto = $config->logobeneficio->tamano->destacado->h;

//                $nombreDestacados = 
//                    $rename . "_" . $calidad . "_" . 
//                    $ancho . "x" . $alto . "." .  
//                    $nombreOriginal['extension'];
                $nombreDestacados = $arr["slug"] . "-". $ancho . "x" . $alto . "-" . $arr["id"] . 
                                        "." . $nombreOriginal['extension'];
                
                $this->_redimensionar(
                    $config->paths->elementsPromoRoot . $nuevoNombre,
                    $config->paths->elementsPromoRoot . $nombreDestacados,
                    $calidad, $ancho, $alto
                );

                //para catalogo
                $ancho = $config->logobeneficio->tamano->catalogo->w;
                $alto = $config->logobeneficio->tamano->catalogo->h;
                
//                $nombreCatalogo =
//                    $rename . "_" . $calidad . "_" . 
//                    $ancho . "x" . $alto . "." .  
//                    $nombreOriginal['extension'];
                $nombreCatalogo = $arr["slug"] . "-". $ancho . "x" . $alto . "-" . $arr["id"] . 
                                    "." . $nombreOriginal['extension'];
                
                $this->_redimensionar(
                    $config->paths->elementsPromoRoot . $nuevoNombre,
                    $config->paths->elementsPromoRoot . "/" . $nombreCatalogo,
                    $calidad, $ancho, $alto
                );
                
                //para beneficios relacionados
                $ancho = $config->logobeneficio->tamano->relacionados->w;
                $alto = $config->logobeneficio->tamano->relacionados->h;
//                $nombreRelacionados =
//                    $rename . "_" . $calidad . "_" . 
//                    $ancho . "x" . $alto . "." . 
//                    $nombreOriginal['extension'];
                $nombreRelacionados =  $arr["slug"] . "-". $arr["id"] . 
                                        "." . $nombreOriginal['extension'];
                
                $this->_redimensionar(
                    $config->paths->elementsPromoRoot . $nuevoNombre,
                    $config->paths->elementsPromoRoot . "/" . $nombreRelacionados,
                    $calidad, $ancho, $alto
                );
                unlink($config->paths->elementsPromoRoot.$nuevoNombre);
//                return $nuevoNombre;
                return $nombreRelacionados;
                
            } elseif ($auth == "establecimiento") {
                
                $rename = "Establecimiento-" . rand(1000, 9999) . "-" .
                        md5($microTime . $salt);

                $calidad = $config->logoestablecimiento->calidadcompresion;
                $nuevoNombre = $rename . "_" . $calidad . "." .$nombreOriginal['extension'];
                try {
                    $form->$path->addFilter('Rename', $nuevoNombre);
                    $form->$path->receive();
                } catch(Exception $e) {
                    echo $e->getMessage();
                }
//                echo $nuevoNombre;
                
                //para e
                $ancho = $config->logoestablecimiento->tamano->establecimiento->w;
                $alto = $config->logoestablecimiento->tamano->establecimiento->h;

                $nombreDimencionado = 
                    $rename . "_" . $calidad . "_" . 
                    $ancho . "x" . $alto . "." .  
                    $nombreOriginal['extension'];
                
                $this->_redimensionar(
                    $config->paths->elementsEstablecimientoRoot . $nuevoNombre,
                    $config->paths->elementsEstablecimientoRoot . $nombreDimencionado,
                    $calidad, $ancho, $alto
                );

                unlink($config->paths->elementsEstablecimientoRoot.$nuevoNombre);
                return $nombreDimencionado;
            }
        } else {
            $nuevoNombre = '';
        }
        return $nuevoNombre;
    }

    public function _devuelveExtension($filename)
    {
        $filename = strtolower($filename);
        $exts = @split("[/\\.]", $filename);
        $n = count($exts) - 1;
        $exts = $exts[$n];
        return $exts;
    }

    /* ----------------------------------------
     * Funcion que redimensiona una imagen
     * --------------------------------------- */

    public function _redimensionar(
        $imgOriginal, $imgNueva, $imgNuevaCalidad, $imgNuevaAnchura, $imgNuevaAltura
    )
    {
        $img = new ZendImage();
        $img->loadImage($imgOriginal);
        if ($img->width > $img->height)
            $img->resize($imgNuevaAnchura, 'width');
        else
            $img->resize($imgNuevaAltura, 'height');
        $img->save($imgNueva);
    }

}
