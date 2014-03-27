<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    public function run()
    {
        parent::run();
    }

    public function _initConfig()
    {
        $config = new Zend_Config($this->getOptions(), true);
        $config->merge(new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini'));
        $config->merge(new Zend_Config_Ini(APPLICATION_PATH . '/configs/app.ini'));
        $config->merge(new Zend_Config_Ini(APPLICATION_PATH . '/configs/cache.ini'));
        $config->merge(new Zend_Config_Ini(APPLICATION_PATH . '/configs/cs.ini'));
        $config->merge(new Zend_Config_Ini(APPLICATION_PATH . '/configs/private.ini'));
        $config->merge(new Zend_Config_Ini(APPLICATION_PATH . '/configs/forms.ini'));
        $config->merge(new Zend_Config_Ini(APPLICATION_PATH . '/configs/security.ini'));
        $config->setReadOnly();
        Zend_Registry::set('config', $config);

        /*$con = new Zend_Config_Writer_Ini();
        $x = new Zend_Config_Ini(APPLICATION_PATH . '/configs/app.ini');
        $arreglo = $x->toArray();
        $arreglo["app"]["title"] = "123";
        $con->write(APPLICATION_PATH . '/configs/app.ini', new Zend_Config($arreglo) );
        */
        $objSessionNamespace = new Zend_Session_Namespace('Zend_Auth');
        $objSessionNamespace->setExpirationSeconds($config->app->lifeTimeZendAuth);
    }

    public function _initViewsHelpers()
    {
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        $view = $layout->getView();
        $view->addHelperPath('App/View/Helper', 'App_View_Helper');
        $config = Zend_Registry::get('config');
        $view->doctype(Zend_View_Helper_Doctype::XHTML1_TRANSITIONAL);
        $view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=utf-8');
//        $view->headMeta()->appendName("robots", "noindex, nofollow");
        $view->headMeta()->appendName(
            "description", "Suscriptores, " . $config->app->title
        );
        $view->headMeta()->appendName(
            "keywords", "Club Suscriptor, El Comercio, Ofertas, Beneficios"
        );
        $view->headMeta()->appendName("Author", "ClubSuscriptores.pe");
        $view->headMeta()->appendName("Content-Language", "es");
        $view->headMeta()->appendName("geo.placename", "Lima, Perú");

        $view->headTitle('Club De Suscriptores El Comercio Perú')->setSeparator(' - ');
        //$view->headLink()->appendStylesheet($view->S($config->app->mediaUrl . '/css/main.css', 'all'));
        $view->headLink()->appendStylesheet($view->S($config->app->mediaUrl . '/css/default.css'), 'all');
        $view->headLink()->appendStylesheet($view->S($config->app->mediaUrl . '/css/layout.css'), 'all');
        $view->headLink()->appendStylesheet($view->S($config->app->mediaUrl . '/css/class.css'), 'all');

        $view->headLink()->appendStylesheet(
            $view->S($config->app->mediaUrl . '/css/ie.css'), 'all', 'lte IE 8'
        );
        $view->headLink(
            array(
                'rel' => 'shortcut icon',
                'href' => $config->app->mediaUrl . '/images/favicon.ico'
            )
        );        
//        $view->headScript()->appendFile($config->app->mediaUrl . '/js/jquery.js');
        $view->headScript()->offsetSetFile(0, $config->app->mediaUrl . '/js/jquery.js');
//        $view->headScript()->appendFile($config->app->mediaUrl . '/js/main.js');	
        $view->headScript()->offsetSetFile(1, $config->app->mediaUrl . '/js/main.js');

        Zend_Layout::getMvcInstance()->assign(
            'active', App_Controller_Action_Suscriptor::MENU_NAME_INICIO
        );

        $js = sprintf(
            "var urls = {
            	mediaUrl : '%s', 
            	elementsUrl : '%s', 
            	siteUrl : '%s', 
            	fDayCurrent : %s,
            	fMonthCurrent : %s, 
            	fYearCurrent : %s, 
            	fMinDate : %s, 
            	googleApi : '%s'
            }", 
            $config->app->mediaUrl, 
            $config->app->elementsUrl, 
            $config->app->siteUrl, 
            date('j'), date('n'), date('Y'), '1900', 
            $config->apis->google->appid
        );
        //Definiendo Constante para Partials
        define('MEDIA_URL', $config->app->mediaUrl);
        define('ELEMENTS_URL', $config->app->elementsUrl);
        define('ELEMENTS_ROOT', $config->paths->elementsRoot);
        define('SITE_URL', $config->app->siteUrl);
        define('HOST_URL', $config->app->hostname);
        $view->addHelperPath('App/View/Helper', 'App_View_Helper');
        $view->headScript()->appendScript($js);
        $view->headScript()->appendFile($config->app->mediaUrl . '/js/slides.min.jquery.js');//by jan
    }

    public function _initRoutes()
    {
        $this->bootstrap('frontController');
        $router = $this->getResource('frontController')->getRouter();
        $routeConfig = new Zend_Config_Ini(APPLICATION_PATH.'/configs/routes.ini');
        $router->addConfig($routeConfig);
    }

    public function _initRegistries()
    {
        $config = Zend_Registry::get('config');

        $this->_executeResource('cachemanager');
        $cacheManager = $this->getResource('cachemanager');
        Zend_Registry::set('cache', $cacheManager->getCache($config->app->cache));

        //$this->_executeResource('db');
        $this->_executeResource('multidb');
        //$adapter = $this->getResource('db');
        $adapter = $this->getPluginResource('multidb')->getDb('db');
        Zend_Registry::set('db', $adapter);
        $adapterDos = $this->getPluginResource('multidb')->getDb('db2');//se agrego
        Zend_Registry::set('db2', $adapterDos);//se agrego
        
        $this->_executeResource('log');
        $log = $this->getResource('log');
        Zend_Registry::set('log', $log);
    }

    public function _initLibrerias()
    {
        define("DOMPDF_ENABLE_REMOTE", true);
        //define("DOMPDF_ENABLE_PHP", true);
        require_once( APPLICATION_PATH . "/../library/Dompdf/dompdf_config.inc.php");
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->pushAutoloader("DOMPDF_autoload");
        
        // TCPDF
        require_once( APPLICATION_PATH . "/../library/Tcpdf/config/lang/spa.php");
        require_once( APPLICATION_PATH . "/../library/Tcpdf/tcpdf.php");

        require_once( APPLICATION_PATH . "/../library/ZendImage/zendimage.php");
        require_once( APPLICATION_PATH . "/../library/ZendLucene/zendlucene.php");
        require_once( APPLICATION_PATH . "/../library/Wkhtmltopdf/wkhtmltopdf.php");
       
    }

}

