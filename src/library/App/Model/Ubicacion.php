<?php

class App_Model_Ubicacion
{
    protected $_db;
    protected $_log;
    protected $_autor;
    protected $_desc;

    public function __construct($db, Zend_Log $log = null)
    {
        $this->_db = $db;
        $this->_log = $log;
    }

    public static function listarUrl()
    {
        $lc_file = APPLICATION_PATH.'/../last_date';
        $vqs = (is_readable($lc_file))?trim(file_get_contents($lc_file)):date('Y-m-d\TH:i:s');
        
        //$url["changefreq"]="hourly|weekly";
        $url["default"]=array("lastmod"=>$vqs);
        $url["catalogo"]=  array("lastmod"=>$vqs);
        $url["vida_social"]=  array("lastmod"=>$vqs);
        $url["contacto"]=  array("lastmod"=>$vqs);
        $url["registro"]=  array("lastmod"=>$vqs);
        $url["suscribete"]=  array("lastmod"=>$vqs);
        
        //var_dump($url);exit;
        $url["preguntas_frecuentes"]=  array("lastmod"=>$vqs);
        return $url;
    }

}