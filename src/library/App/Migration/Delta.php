<?php

class App_Migration_Delta
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

    public function up()
    {
    }

    public function down()
    {
    }
    
    public function getDesc()
    {
        return $this->_desc;
    }
    
    public function getAuthor()
    {
        return $this->_autor;
    }

}