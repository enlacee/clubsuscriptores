<?php

class App_View_Helper_HtmlToIframe extends Zend_View_Helper_HtmlElement
{

    public function HtmlToIframe($html, $ruta)
    {
        function strip_script($string)
        {
            $final = "";
            $n = strlen($string);
            for ($i=0;$i<$n;$i+=30000) {
                $cadena = substr($string, $i, 30000);
                $cadena = preg_replace("/<script[^>]*>.*<*script[^>]*>/i", "", $cadena);
                $cadena = preg_replace(
                    "/<.*onAbort|onBlur|onChange|onClick|onDblClick|onDragDrop|onError|onFocus".
                    "|onKeyDown|onKeyPress|onKeyUp|onLoad|onMouseDown|onMouseMove|onMouseOut".
                    "|onMouseOver|onMouseUp|onMove|onReset|onResize|onSelect|onSubmit|onUnload/i", "", $cadena
                );
                $final.=$cadena;
            }
            return $final;
        }
        function buildHTMLPage($contenido, $archivo, $modo = "w+")
        {
            ob_start();
            echo strip_script($contenido);
            $htmlcontenido = ob_get_contents();
            ob_end_clean();
            $fp = fopen($archivo, $modo);
            fwrite($fp, $htmlcontenido);
            fclose($fp);
            return $archivo;
        }
        buildHTMLPage($html, $ruta, "w");
    }
}