<?php

/**
 * Description of CsrfProtect
 *
 * @author Solman
 */
class Plugin_CsrfProtect extends Zend_Controller_Plugin_Abstract
{
    public $session = null;
    protected $_keyName = 'csrf';
    protected $_expiryTime = 7200; //5 min
    protected $_previousToken = '';

    public function __construct(array $params = array())
    {
        if (isset($params['expiryTime']))
            $this->setExpiryTime($params['expiryTime']);

        if (isset($params['keyName']))
            $this->setKeyName($params['keyName']);

        $this->session = new Zend_Session_Namespace('CsrfProtect');
        $this->_initializeTokens();
    }

    public function setExpiryTime($seconds)
    {
        $this->_expiryTime = $seconds;
        return $this;
    }

    public function setKeyName($name)
    {
        $this->_keyName = $name;
        return $this;
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $this->_initializeTokens();
        $config = Zend_Registry::get('config');
        $urls = $config->csfrProtection->toArray();

        $m = $request->getParam('module');
        $c = $request->getParam('controller');
        $a = $request->getParam('action');

        //Logica para CSFR PROTECT
        if (($request->isPost() === true) && $this->checkAccess($m, $c, $a, $urls)) {
            $value = $request->getParam($this->_keyName);
            if (empty($value) || !$this->isValidToken($value)) {
                $writer = new Zend_Log_Writer_Stream(APPLICATION_PATH . "/../logs/CsrfProtect.log");
                $formatter = new Zend_Log_Formatter_Xml();
                $writer->setFormatter($formatter);
                $log = new Zend_Log();
                $log->addWriter($writer);

                if (empty($value)) {
                    $log->info(
                        'Posible CSRF Attack - token no recibido | HORA:' .
                        date("d-m-Y H:i:s") . ' IP:' . $this->getRealIP() . "->" . $this->session->key
                    );
                    exit;
                }
                if (!$this->isValidToken($value)) {
                    $log->info(
                        'Posible CSRF Attack  - tokens no coinciden | HORA:' .
                        date("d-m-Y H:i:s") . ' IP:' . $this->getRealIP() . "->" . $this->session->key
                    );
                    exit;
                }
                $log->info(empty($value) . "-" . !$this->isValidToken($value));
            } else {
                $this->session->__unset("key");
            }
            $this->_initializeTokens();
        }
    }

    public function isValidToken($value)
    {
        if ($value != $this->session->key)
            return false;

        return true;
    }

    public function getToken()
    {
        return $this->_token;
    }

    /**
     * Proteccion para Formularios
     */
    public function dispatchLoopShutdown()
    {
        $config = Zend_Registry::get('config');
        $urls = $config->csfrProtection->toArray();

        $request = $this->getRequest();
        $m = $request->getParam('module');
        $c = $request->getParam('controller');
        $a = $request->getParam('action');

        $token = $this->getToken();
        $response = $this->getResponse();

        if ($this->checkAccess($m, $c, $a, $urls)) {
            $element = sprintf(
                '<input type="hidden" name="%s" value="%s" />', $this->_keyName, $token
            );
            $body = $response->getBody();

            //Encontramos todos los Forms y agregamos csrf protection a los elementos
            $body = preg_replace('/<form[^>]*>/i', '$0' . $element, $body);
            $response->setBody($body);
        }
    }

    public function _initializeTokens()
    {
        $this->_hash = new Zend_Form_Element_Hash('csrf_hash', array('salt' => 'exitsalt'));
        $this->_hash->setTimeout(3600);
        $this->_hash->initCsrfToken();
        $csrfhash = $this->_hash->getValue();

        if (!$this->session->__isset("key"))
            $this->session->key = $csrfhash;

        if ($this->_expiryTime > 0)
            $this->session->setExpirationSeconds($this->_expiryTime);

        $this->_token = $this->session->key;
    }

    protected function getRealIP()
    {

        if (@$_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
            $clientip =
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

            $entries = split('[, ]', @$_SERVER['HTTP_X_FORWARDED_FOR']);

            reset($entries);
            while (list(, $entry) = each($entries)) {
                $entry = trim($entry);
                if (preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $entry, $iplist)) {
                    $privateip = array(
                        '/^0\./',
                        '/^127\.0\.0\.1/',
                        '/^192\.168\..*/',
                        '/^172\.((1[6-9])|(2[0-9])|(3[0-1]))\..*/',
                        '/^10\..*/');
                    $foundip = preg_replace($privateip, $clientip, $iplist[1]);
                    if ($clientip != $foundip) {
                        $clientip = $foundip;
                        break;
                    }
                }
            }
        } else {
            $clientip =
                (!empty($_SERVER['REMOTE_ADDR']) ) ?
                $_SERVER['REMOTE_ADDR'] :
                ( (!empty($_ENV['REMOTE_ADDR']) ) ?
                    $_ENV['REMOTE_ADDR'] :
                    "unknown" );
        }
        return $clientip;
    }

    public function checkAccess($module, $controller, $action, $controllersAndActions)
    {
        if (array_key_exists($module, $controllersAndActions)
            && $controllersAndActions[$module] != null
            && is_array($controllersAndActions[$module])
        ) {
            if (array_key_exists($controller, $controllersAndActions[$module])
                && $controllersAndActions[$module][$controller] != null
                && is_array($controllersAndActions[$module][$controller])
            ) {
                if ($controllersAndActions[$module][$controller][0] == "ALL") {
                    return true;
                }

                foreach ($controllersAndActions[$module][$controller] as $publicAction) {
                    if ($action == $publicAction) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

}
