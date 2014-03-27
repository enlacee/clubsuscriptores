<?php

class Cronjob_Base implements Cronjob_CronInterface
{
    private $_time;
    private $_memory;
    public $log;
    
    public function __construct($args=null)
    {
        $this->_time   = microtime(true);
        $this->_memory = memory_get_usage();

        $writer = new Zend_Log_Writer_Stream(APPLICATION_PATH."/../logs/CronJobs.log");
        $formatter = new Zend_Log_Formatter_Xml();
        $writer->setFormatter($formatter);
        $this->log = new Zend_Log();
        $this->log->addWriter($writer);
    }

    public function run($callback="")
    {
        if ($callback=="") {
            $this->callBack();
        }
    }

    public function callBack()
    {
       $endTime = microtime(true);
       $endMemory = memory_get_usage();

       $this->_time   = $endTime - $this->_time;
       $this->_memory = $endMemory - $this->_memory;
    }

    public function getTimeDelay()
    {
        return $this->_time;
    }
    public function getMemoryUsageKb()
    {
        return number_format($this->_memory/1024);
    }

    public function urlActual()
    {
        $config = Zend_Registry::get("config");
        return $config->app->siteUrl;
    }
}
