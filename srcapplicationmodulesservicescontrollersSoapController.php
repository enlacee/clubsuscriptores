<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of SoapController
 *
 * @author FCJ
 */
class Services_SoapController extends Zend_Controller_Action
{
    
    protected $_wsRequestLog;
    protected $_wsResponseLog;
    
    public function init()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->_wsRequestLog = new Zend_Log(
            new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../logs/ws_soap_requests.log')
        );
        $this->_wsResponseLog = new Zend_Log(
            new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../logs/ws_soap_response.log')
        );
        parent::init();
    }

    public function indexAction()
    {
        
    }

    public function suscriptorAction()
    {
        $classService = "App_Services_Suscriptor";
        $classException = "App_Services_Exception";
        $config = Zend_Registry::get('config');
        $uri = $config->services->wsdl->suscriptor;

        if ('GET' == $_SERVER['REQUEST_METHOD']) {
            $wsdl = new Zend_Soap_AutoDiscover();
            $wsdl->setUri($uri);
            $wsdl->setClass($classService);
            $wsdl->handle();
        } else {
            $soap = new Zend_Soap_Server();
            $soap->setClass($classService);
            $soap->setUri($uri);
            $soap->registerFaultException(array($classException));
            $soap->handle();
            $this->_wsRequestLog->log($soap->getLastRequest(), Zend_Log::INFO);
            $this->_wsResponseLog->log($soap->getLastResponse(), Zend_Log::INFO);
        }
    }

    public function establecimientoAction()
    {
        $classService = "App_Services_Establecimiento";
        $classException = "App_Services_Exception"; 
        $config = Zend_Registry::get('config');
        $uri = $config->services->wsdl->establecimiento;

        if ('GET' == $_SERVER['REQUEST_METHOD']) {
            $wsdl = new Zend_Soap_AutoDiscover();
            $wsdl->setUri($uri);
            $wsdl->setClass($classService);
            $wsdl->handle();
        } else {
            $soap = new Zend_Soap_Server();
            $soap->setClass($classService);
            $soap->setUri($uri);
            $soap->registerFaultException(array($classException));
            $soap->handle();
            $this->_wsRequestLog->log($soap->getLastRequest(), Zend_Log::INFO);
            $this->_wsResponseLog->log($soap->getLastResponse(), Zend_Log::INFO);
        }
    }
    public function suscriptorvalidarAction()
    {
        $class_service = "App_Services_SuscriptorValidar";
        $classException = "App_Services_Exception";
        $config = Zend_Registry::get('config');
        $uri = $config->services->wsdl->suscriptorvalidar;

        if ('GET' == $_SERVER['REQUEST_METHOD']) {
            $wsdl = new Zend_Soap_AutoDiscover();
            $wsdl->setUri($uri);
            $wsdl->setClass($classService);
            $wsdl->handle();
        } else {
            $soap = new Zend_Soap_Server();
            $soap->setClass($classService);
            $soap->setUri($uri);
            $soap->registerFaultException(array($classException));
            $soap->handle();
            $this->_wsRequestLog->log($soap->getLastRequest(), Zend_Log::INFO);
            $this->_wsResponseLog->log($soap->getLastResponse(), Zend_Log::INFO);
        }
    }

}