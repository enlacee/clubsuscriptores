<?php

class Delta_0098 extends App_Migration_Delta
{
    protected $_autor = 'Anderson Poccorpachi';
        protected $_desc = 'Agregar el columna path_logo_bkp,path_imagen_bkp a la tabla beneficio y galeria_imagenes';
    
    public function up()
    {
        $sql = "ALTER TABLE `beneficio` ADD COLUMN path_logo_bkp VARCHAR(200) DEFAULT NULL";
        $this->_db->query($sql);
        $sql = "update `beneficio` set path_logo_bkp=path_logo";
        $this->_db->query($sql);
        $sql = "update `beneficio` set path_logo=null";
        $this->_db->query($sql);
        $sql = "ALTER TABLE `galeria_imagenes` ADD COLUMN path_imagen_bkp VARCHAR(200) DEFAULT NULL";
        $this->_db->query($sql);
        $sql = "update `galeria_imagenes` set path_imagen_bkp=path_imagen";
        $this->_db->query($sql);
        $sql = "update `galeria_imagenes` set path_imagen=null";
        $this->_db->query($sql);
        return true;
    }
}