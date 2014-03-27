<?php

class Application_Model_TempLucene extends App_Db_Table_Abstract
{
    protected $_name = "temp_lucene";

    public function getAllTemp()
    {
        $adapter = $this->getAdapter();
        $select = $this->select()
                  ->from($this->_name)
                  ->order("id ASC");
        $row = $adapter->fetchAll($select);
        return $row;
    }
    public function removeTemp($id)
    {
        $adapter = $this->getAdapter();
        $adapter->delete($this->_name, "id=".$id);
        return true;
    }
    public function truncateCondicionado()
    {
       $n = count($this->getAllTemp());
       if ($n==0) {
           $adapter = $this->getAdapter();
           $sql="TRUNCATE ".$this->_name;
           $adapter->query($sql);
       }
    }
}