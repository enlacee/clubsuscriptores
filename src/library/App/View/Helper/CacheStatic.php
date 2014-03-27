<?php

class App_View_Helper_CacheStatic extends Zend_View_Helper_HtmlElement
{
    public function CacheStatic($headScript, $excepciones)
    {
        $config = Zend_Registry::get("config");
        
        //mejora Ander
        $lc_file = APPLICATION_PATH.'/../last_commit';
        if (is_readable($lc_file)){
            $vqs = trim(file_get_contents($lc_file));
        } else {
            $static = !empty($config->confpaginas->staticVersion)?1:$config->confpaginas->staticVersion;
            $vqs    = date('Ymd').$static;
        }
        
        foreach($headScript as $item):
            if (count($item->attributes)>0) {
                $valida = 0;
                for ($i=0;$i<count($excepciones);$i++) {
                    if (count(explode($excepciones[$i], $item->attributes["src"]))==2) {
                            $valida=1; break;
                    }
                }
                if ($valida==0) $item->attributes["src"] = $item->attributes["src"]."?".$vqs;
                        //$config->confpaginas->staticVersion;
            }
        endforeach;
    }
}