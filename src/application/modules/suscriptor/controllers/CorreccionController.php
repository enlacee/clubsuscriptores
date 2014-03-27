<?php

class Suscriptor_CorreccionController extends App_Controller_Action
{
    protected $_dirPath="../public/elements/";
    
    protected $_articulos="articulos/";
    
    protected $_images="images/";
    protected $_beneficios="beneficios/";
    protected $_sociales="sociales/";
    protected $_thums="thums/";
    protected $_empty="empty";
    
    
    protected $_temp="temp/";
    
    function getmicrotime()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float) $usec + (float) $sec);
    }    
    
    public function init()
    {
        parent::init();
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $config = $this->getConfig();
        
        if(empty($config->correcion->estado)){$this->_redirect(SITE_URL);}
        
    }

    public function redmine19500Action()
    {
        $time_start = $this->getmicrotime();
        $mBeneficio = new Application_Model_Beneficio();
        $mBeneficioVersion = new Application_Model_BeneficioVersion();
        $mCupon = new Application_Model_Cupon();
        echo '<pre>'.PHP_EOL;
        foreach ($mBeneficio->fetchAll()->toArray() as $valuesBeneficio) {
            $idBeneficio = $valuesBeneficio['id'];
            $mCupon->setBeneficio_id($idBeneficio);
            $beneficioVersion = $mBeneficioVersion->fetchRow('beneficio_id='.$idBeneficio.' AND activo=1')->toArray();
            if ($valuesBeneficio['sin_stock'] == 1 ) {
                if($beneficioVersion['stock']>0){
                    $mBeneficioVersion->update( array('stock'=>0) , 'id='.$beneficioVersion['id']);
                    echo "BENEF1 ".$idBeneficio.' '.$valuesBeneficio['activo'].' '.$valuesBeneficio['publicado'].' '.$valuesBeneficio['elog'].' Stock: '.$beneficioVersion['stock'].'=>0'.PHP_EOL;
                }
                if($beneficioVersion['stock_actual']>0){
                    $mBeneficioVersion->update( array('stock_actual'=>0), 'id='.$beneficioVersion['id']);
                    echo "BVERS2 ".$idBeneficio.' '.$valuesBeneficio['activo'].' '.$valuesBeneficio['publicado'].' '.$valuesBeneficio['elog'].' StockActual: '.$beneficioVersion['stock_actual'].'=>0'.PHP_EOL;
                }
            } else {
                $cantCupos=$mCupon->getNroCuponesGenByBeneficio();
                $stock_actual = $beneficioVersion['stock']-$cantCupos;
                if($cantCupos > $beneficioVersion['stock']) { // caso SIT
                    $mBeneficioVersion->update( array('stock'=>$cantCupos) , 'id='.$beneficioVersion['id']);
                    echo "BENEF3 ".$idBeneficio.' '.$valuesBeneficio['activo'].' '.$valuesBeneficio['publicado'].' '.$valuesBeneficio['elog'].' Stock: '.$beneficioVersion['stock'].'=>'.$cantCupos.PHP_EOL;
                    $mBeneficioVersion->update(array('stock_actual' => 0), 'id=' . $beneficioVersion['id']);
                    echo "BVERS4 ".$idBeneficio.' '.$valuesBeneficio['activo'].' '.$valuesBeneficio['publicado'].' '.$valuesBeneficio['elog'].' StockActual: '.$beneficioVersion['stock_actual'].'=>0'.PHP_EOL;
                }elseif($stock_actual!=$beneficioVersion['stock_actual']){
                    $mBeneficioVersion->update(array('stock_actual' => $stock_actual), 'id=' . $beneficioVersion['id']);
                    echo "BVERS5 ".$idBeneficio.' '.$valuesBeneficio['activo'].' '.$valuesBeneficio['publicado'].' '.$valuesBeneficio['elog'].' StockActual: '.$beneficioVersion['stock_actual'].'=>'.$stock_actual.PHP_EOL;
                }
            }
        }
        echo round($this->getmicrotime() - $time_start,2).' s';
        echo '</pre>'.PHP_EOL;
    }
    
    public function imgCarpetaAction()
    {
        $images=  $this->_images;
        $beneficios=$this->_beneficios;
        $sociales=$this->_sociales;
        $thums=$this->_thums;
        $temp=$this->_temp;
        $empty=$this->_empty;
        
        $dirPath=$this->_dirPath;
        
        if(!is_dir($dirPath.$images)){ 
            @mkdir($dirPath.$images, 0777);
            @fopen($dirPath.$images.$empty, "w+"); 
//            @fwrite($dirPath.$images.$empty,""); 
            @fclose($dirPath.$images.$empty);
            @mkdir($dirPath.$images.$beneficios, 0777);
            @fopen($dirPath.$images.$beneficios.$empty, "w+"); 
//            @fwrite($dirPath.$images.$beneficios.$empty,""); 
            @fclose($dirPath.$images.$beneficios.$empty);
            @mkdir($dirPath.$images.$beneficios.$temp, 0777);
            @fopen($dirPath.$images.$beneficios.$temp.$empty, "w+"); 
//            @fwrite($dirPath.$images.$beneficios.$temp.$empty,""); 
            @fclose($dirPath.$images.$beneficios.$temp.$empty);
            @mkdir($dirPath.$images.$sociales, 0777);
            @fopen($dirPath.$images.$sociales.$empty, "w+"); 
//            @fwrite($dirPath.$images.$beneficios.$temp.$empty,""); 
            @fclose($dirPath.$images.$sociales.$empty);
            @mkdir($dirPath.$images.$sociales.$temp, 0777);
            @fopen($dirPath.$images.$sociales.$temp.$empty, "w+"); 
//            @fwrite($dirPath.$images.$beneficios.$temp.$empty,""); 
            @fclose($dirPath.$images.$sociales.$temp.$empty);
            @mkdir($dirPath.$images.$sociales.$thums, 0777);
            @fopen($dirPath.$images.$sociales.$thums.$empty, "w+"); 
//            @fwrite($dirPath.$images.$beneficios.$temp.$empty,""); 
            @fclose($dirPath.$images.$sociales.$thums.$empty);
            echo "Se creo $images $beneficios $sociales $thums "; 
        }else{ 
            echo "Ya existe ese directorio\n"; 
        }
        
    }
    
    
    public function imgBeAction()
    {
        $time_start = $this->getmicrotime();
        $m= new Application_Model_Beneficio();
        
        $directorio=$this->_dirPath.$this->_beneficios;
        $directorioNueva=$this->_dirPath.$this->_images.$this->_beneficios;
        $leer= opendir($directorio);
        
        echo '<pre>'.PHP_EOL;
//        if(!is_dir($directorio)){ 
            while ($archivo = readdir($leer)) {
                if($archivo == '.') echo ""; 
                elseif($archivo == '..') echo ""; 
                elseif(is_dir($directorio.$archivo)) echo "Carpeta ".$archivo.PHP_EOL;
                elseif (strpos($archivo, '.jpg',1) || strpos($archivo, '.png',1) || strpos($archivo, '.jpeg',1)) {

                    $retornaNombre=  $this->fnNombreBeneficio($archivo);

                    $val= Application_Model_Beneficio::getBeneficioByPath($retornaNombre["nombre"]);
                    if(empty($val)){
                        echo "_____1_".$archivo.PHP_EOL;
                    }else{
                        $newArchivo=$this->view->TextToUrl($val["titulo"])."-".$val["id"].$retornaNombre["ext"];
                        $newArchivoCP=$this->view->TextToUrl($val["titulo"]).$retornaNombre["dim"]."-".$val["id"];
                        if(@copy($directorio.$archivo, $directorioNueva.$newArchivoCP.$retornaNombre["ext"])){
                            $m->update(array("path_logo"=>$newArchivo), 
                                    $m->getAdapter()->quoteInto('id = ?', $val["id"]));
                        }else{
                            echo "_____2_".$archivo.PHP_EOL;
                        }

                    }

                } else {
                    echo "_____3_".$archivo.PHP_EOL;
                }
            }
//        } else {
//            echo "Error Carpeta Existente".PHP_EOL;
//        }
        closedir($leer);
        echo "___TERMINO___".round($this->getmicrotime() - $time_start,2).' s';
        echo '</pre>'.PHP_EOL;
        
    }
    
    function fnNombreBeneficio($archivo)
    {
//        $this->_helper->layout->disableLayout();
//        $this->_helper->viewRenderer->setNoRender();
        $minDim="_210x115";
        $medDim="_325x182";
        $maxDim="_660x371";
//        $dimension=array($minDim,$medDim,$maxDim);
        $ext="";$newArch="";$dim="";$dimResult="";
        if(strpos($archivo, '.jpg',1)) {
            $ext=".jpg";
        } elseif(strpos($archivo, '.jpeg',1)) {
            $ext=".jpeg";
        } elseif(strpos($archivo, '.png',1)) {
            $ext=".png";
        }
        $newArch = str_replace($ext, "", $archivo);
////        if(in_array($newArch, $dimension)){
        if (strstr($newArch, $minDim) != "") {
            $dim=$minDim;
        } elseif (strstr($newArch, $medDim) != "") {
            $dim=$medDim;
            $dimResult="-".substr($medDim,1);
        } elseif (strstr($newArch, $maxDim) != "") {
            $dim=$maxDim;
            $dimResult="-".substr($maxDim,1);
        }
        $newArch = str_replace($dim, "", $newArch);
////        }
        return array("nombre"=>$newArch.$ext,"ext"=>$ext,"dim"=>$dimResult);
    }
    
    public function imgVsAction()
    {
        $time_start = $this->getmicrotime();
        
        $m= new Application_Model_GaleriaImagenes();
        
        $directorio=$this->_dirPath.$this->_articulos;
        $directorioNuevo=$this->_dirPath.$this->_images.$this->_sociales;
        $leer= opendir($directorio);
        
        echo '<pre>'.PHP_EOL;
//        if(!is_dir($directorio)){ 
        
            
            while ($archivo = readdir($leer)) {
                if($archivo == '.' || $archivo == '..') echo ""; 
                elseif($archivo == 'temp') echo ""; 
                elseif(is_dir($directorio.$archivo)) {
                    
                    $leer_vs= opendir($directorio.$archivo);
                    while ($img_vs = readdir($leer_vs)) {
                        
                        if($img_vs == '.' || $img_vs == '..') echo ""; 
                        elseif (strpos($img_vs, '.jpg',1) || strpos($img_vs, '.png',1) || strpos($img_vs, '.jpeg',1)) {
//                            echo $img_vs.PHP_EOL;
                            $retornaNombre=  $this->fnNombreVs($img_vs, $this->_thums);
                            
                            $val= Application_Model_GaleriaImagenes::getImagenByPath($retornaNombre["nombre"]);
                            if(empty($val)){
                                echo "_____1_".$directorio.$archivo."/".$img_vs.PHP_EOL;
                            }else{
                                $newArchivo=$this->view->TextToUrl($val["titulo"])."-".$val["orden"]."-".$val["a_id"]
                                        .$retornaNombre["ext"];
//                                echo $directorio.$archivo."/".$img_vs.PHP_EOL;
//                                echo $directorioNuevo.$retornaNombre["dir"].$newArchivo;exit;
                                if(@copy($directorio.$archivo."/".$img_vs, 
                                        $directorioNuevo.$retornaNombre["dir"].$newArchivo)){
                                    $sa=$m->update(array("path_imagen"=>$newArchivo), 
                                            $m->getAdapter()->quoteInto('id = ?', $val["i_id"]));
//                                    if(!$sa) echo $directorio.$archivo."/".$img_vs." --> ".$newArchivo.PHP_EOL;                                    
                                        
                                }else{
                                    echo "_____2_".$directorio.$archivo."/".$img_vs.PHP_EOL;
                                }

                            }                            
                            
                        } else {
                            echo "_____3_".$directorio.$archivo.$img_vs.PHP_EOL;
                        }
                    }
                    closedir($leer_vs);
                    
                    
//                    echo "Carpeta ".$archivo.PHP_EOL;
//                elseif (strpos($archivo, '.jpg',1) || strpos($archivo, '.png',1) || strpos($archivo, '.jpeg',1)) {

//                    $retornaNombre=  $this->fnNombreAction($archivo);
//
//                    $val= Application_Model_Beneficio::getBeneficioByPath($retornaNombre["nombre"]);
//                    if(empty($val)){
//                        echo "_____1_".$archivo.PHP_EOL;
//                    }else{
//                        $newArchivo=$this->view->TextToUrl($val["titulo"])."-".$val["id"];
//                        $newArchivoCP=$this->view->TextToUrl($val["titulo"]).$retornaNombre["dim"]."-".$val["id"];
//                        if(@copy($directorio.$archivo, $directorioNueva.$newArchivoCP.$retornaNombre["ext"])){
////                            $m->update(array("img"=>$newArchivo), $m->getAdapter()->quoteInto('id = ?', $val["id"]));
//                        }else{
//                            echo "_____2_".$archivo.PHP_EOL;
//                        }
//
//                    }
//                    echo $archivo.PHP_EOL;
                } else {
//                    echo "_____3_".$archivo.PHP_EOL;
                }
            }
//        } else {
//            echo "Error Carpeta Existente".PHP_EOL;
//        }
        closedir($leer);
        echo "___TERMINO___".round($this->getmicrotime() - $time_start,2).' s';
        echo '</pre>'.PHP_EOL;
        
    }
    
    function fnNombreVs($archivo,$dir)
    {
        $small="Small";

        $ext="";$newArch="";$dim="";$dimResult="";
        if(strpos($archivo, '.jpg',1)) {
            $ext=".jpg";
        } elseif(strpos($archivo, '.jpeg',1)) {
            $ext=".jpeg";
        } elseif(strpos($archivo, '.png',1)) {
            $ext=".png";
        }
        $newArch = str_replace($ext, "", $archivo);

        if (strstr($newArch, $small) != "") {
            $dim=$small;
            $dimResult=$dir;
        } else {
            $dimResult=$dim="";            
        }
        $newArch = str_replace($dim, "", $newArch);
        
        return array("nombre"=>$newArch.$ext,"ext"=>$ext,"dir"=>$dimResult);
    }
    
    
}
