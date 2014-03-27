<?php

/**
 * Description of delta_0001
 *
 * @author DjTabo
 */
class Delta_0028 extends App_Migration_Delta
{
    protected $_autor = 'Favio Condori';
    protected $_desc = 'Agregando Data para Tipo de Establecimiento';

    public function up()
    {
        $sql = "INSERT INTO `tipo_establecimiento` (`id`, `nombre`, `descripcion`) VALUES
                    (1, 'Restaurantes', NULL),
                    (2, 'Centro Comerciales', NULL),
                    (3, 'Grifos', NULL),
                    (4, 'Spas', NULL),
                    (5, 'Cines', NULL),
                    (6, 'Museos', NULL),
                    (7, 'Bancos', NULL),
                    (8, 'Tiendas Musicales', NULL),
                    (9, 'Discotecas', NULL),
                    (10, 'Hoteles', NULL),
                    (11, 'Bibliotecas', NULL),
                    (12, 'Farmacias', NULL),
                    (13, 'Otros', NULL),
                    (14, 'Ticketera', NULL)";
        $this->_db->query($sql);
        return true;
    }

}
