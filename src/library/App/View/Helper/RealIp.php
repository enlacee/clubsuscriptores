<?php

class App_View_Helper_RealIp extends Zend_View_Helper_HtmlElement
{

    function RealIP()
    {
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
            $clientIp =
                (!empty($_SERVER['REMOTE_ADDR']) ) ?
                $_SERVER['REMOTE_ADDR'] :
                ( (!empty($_ENV['REMOTE_ADDR']) ) ?
                    $_ENV['REMOTE_ADDR'] :
                    "unknown" );

            // los proxys van añadiendo al final de esta cabecera
            // las direcciones ip que van "ocultando". Para localizar la ip real
            // del usuario se comienza a mirar por el principio hasta encontrar
            // una dirección ip que no sea del rango privado. En caso de no
            // encontrarse ninguna se toma como valor el REMOTE_ADDR

            $entries = split('[, ]', $_SERVER['HTTP_X_FORWARDED_FOR']);

            reset($entries);
            while (list(, $entry) = each($entries)) {
                $entry = trim($entry);
                if (preg_match("/^([0-9]+\\.[0-9]+\\.[0-9]+\\.[0-9]+)/", $entry, $ipList)) {
                    // http://www.faqs.org/rfcs/rfc1918.html
                    $privateIp = array(
                        '/^0\\./',
                        '/^127\\.0\\.0\\.1/',
                        '/^192\\.168\\..*/',
                        '/^172\\.((1[6-9])|(2[0-9])|(3[0-1]))\\..*/',
                        '/^10\\..*/');

                    $foundIp = preg_replace($privateIp, $clientIp, $ipList[1]);

                    if ($clientIp != $foundIp) {
                        $clientIp = $foundIp;
                        break;
                    }
                }
            }
        } else {
            $clientIp =
                (!empty($_SERVER['REMOTE_ADDR']) ) ?
                $_SERVER['REMOTE_ADDR'] :
                ( (!empty($_ENV['REMOTE_ADDR']) ) ?
                    $_ENV['REMOTE_ADDR'] :
                    "unknown" );
        }

        return $clientIp;
    }

}