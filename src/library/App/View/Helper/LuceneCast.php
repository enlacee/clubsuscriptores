<?php

/**
 * Description of Util
 *
 * @author svaisman
 */
class App_View_Helper_LuceneCast extends Zend_View_Helper_HtmlElement
{
    // las palabras de 2 caracteres ya son filtradas
    protected $_replaceList = array(
        'original'  => 'áéíóúÁÉÍÓÚ',
        'reemplazo' => 'aeiouaeiou'
    );

    public function LuceneCast($str)
    {
        $luceneStr = $str;
        //var_dump($luceneStr);
        for ($i = 0; $i < mb_strlen($this->_replaceList['original']); $i++) {
            $or = mb_substr($this->_replaceList['original'], $i, 1);
            $re = mb_substr($this->_replaceList['reemplazo'], $i, 1);
            
            $luceneStr = mb_ereg_replace($or, $re, $luceneStr);
        }
        $luceneStr = mb_strtolower($luceneStr);
        //var_dump($luceneStr);
        //exit;
        return $luceneStr;
    }
}