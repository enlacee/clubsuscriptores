<?php

class Delta_0087 extends App_Migration_Delta
{
    protected $_autor = 'Anderson Poccorpachi';
    protected $_desc = 'Cambiar campo sin_limite_por_suscriptor ampliando a valores 0 1 2 a la tabla beneficio';
    
    public function up()
    {
        $sql = "ALTER TABLE `beneficio` CHANGE `sin_limite_por_suscriptor` `sin_limite_por_suscriptor`".
                " enum('1','0','2' ) DEFAULT '0'".
                " COMMENT 'este campo sera para cupos max por suscriptor: ilimitado, max general y max detalle ';";
        $this->_db->query($sql);
        return true;
    }
}
