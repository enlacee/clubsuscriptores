<?php

class Delta_0010 extends App_Migration_Delta
{
    protected $_autor = 'Paul Taboada';
    protected $_desc = 'Tabla que guarda los datos de los usuarios diferentes de suscriptor';

    public function up()
    {
        $sql = "DROP TABLE IF EXISTS `administrador`;";
        $this->_db->query($sql);
        $sql = "CREATE TABLE IF NOT EXISTS administrador (
                id INT NOT NULL AUTO_INCREMENT ,
                usuario_id INT NOT NULL ,
                nombres VARCHAR(45),
                apellidos VARCHAR(75),
                sexo enum('M','F') DEFAULT 'M',
                fecha_nacimiento DATE,
                tipo_documento enum('DNI','Carnet Extranjería') DEFAULT 'DNI',
                numero_documento VARCHAR(45),
                telefono VARCHAR(45),
                PRIMARY KEY ( id ) ,
                INDEX fk_administrador ( usuario_id ASC ) ,
                CONSTRAINT fk_usuario_administrador FOREIGN KEY ( usuario_id ) REFERENCES usuario (
                id
                ) ON DELETE NO ACTION ON UPDATE NO ACTION
                )";
        $this->_db->query($sql);
        return true;
    }

}
