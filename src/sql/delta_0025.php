<?php

/**
 * Description of delta_0025
 *
 * @author DjTabo
 */
class Delta_0025 extends App_Migration_Delta
{
    protected $_autor = 'Paul Taboada';
    protected $_desc = 'Nuevos registros para tipo de beneficio sorteo';
    
    public function up()
    {
        /*
        $sql = "INSERT INTO `tipo_beneficio` (`id`, `nombre`, `descripcion`, `abreviado`, `slug`, `activo`) VALUES
                (5, 'Sorteos', NULL, 'Sorteo', 'sorteo', 1);";
        $this->_db->query($sql);
        
        $sql = "INSERT INTO `establecimiento` (`id`, `tipo_establecimiento_id`, `creado_por`, `actualizado_por`, `nombre`, `presentacion`, `RUC`, `direccion`, `contacto`, `telefono_contacto`, `email_contacto`, `numero_usuarios`, `numero_beneficio`, `activo`, `fecha_registro`, `fecha_actualizacion`, `path_imagen`) VALUES
                (12, 1, 1, 1, 'Bembos', 'Bembos', '20506700079', 'Rubens 177 San Borja', 'Juan Lopez', '2257789', 'contacto@bembos.com.pe', 1, NULL, 1, '2011-11-30 03:22:03', '2011-11-30 03:22:06', NULL);";
        $this->_db->query($sql);
        
        $sql = "INSERT INTO `categoria` (`id`, `creado_por`, `actualizado_por`, `nombre`, `descripcion`, `activo`, `slug`, `fecha_registro`, `fecha_actualizacion`) VALUES
                (11, 1, 1, 'Sorteos disponibles', 'Sorteos disponibles', 1, 'sorteos', '2011-12-05 17:44:18', '2011-12-05 17:44:22'),
                (12, 1, 1, 'Sorteos resultados', 'Sorteos resultados', 1, 'resultados', '2011-12-05 17:45:06', '2011-12-05 17:45:09');";
        $this->_db->query($sql);
        
        $sql = "INSERT INTO `beneficio` (`id`, `creado_por`, `actualizado_por`, `publicado_por`, `retirado_por`, `establecimiento_id`, `tipo_beneficio_id`, `titulo`, `descripcion`, `descripcion_corta`, `valor`, `cuando`, `direccion`, `email_info`, `telefono_info`, `informacion_adicional`, `path_logo`, `maximo_por_subscriptor`, `sin_limite_por_suscriptor`, `sin_stock`, `es_destacado`, `es_destacado_principal`, `activo`, `publicado`, `generar_cupon`, `veces_visto`, `fecha_registro`, `fecha_actualizacion`, `fecha_publicacion`, `fecha_retiro`, `chapita`, `chapita_color`, `slug`, `ncuponesconsumidos`) VALUES
                (25, 1, 1, NULL, NULL, 12, 5, 'Bembos te regala un viaje a Cancún', 'Bembos te regala 2 pasajes a Cancún con estadía pagada en el Hotel Decameron (All inclusive).\r\nAprovecha esta única oportunidad', 'Bembos te invita a Cancún... 2 pasajes a Cancún vía Copa Airlines', '', 'Sorteo 21/12/2011', 'El ganador acercarse a Bembos en Camino Real', 'sorteos@bembos.com.pe', '45788899', 'Teléfono de referencia:  324-5878', 'Promo-7516-8ab1af13e83739abf0ed3d1366058216_80.jpg', 1, 0, 1, 0, 0, 1, 1, 1, 16, '2011-12-05 17:20:51', NULL, NULL, NULL, '', 'rojo', 'sorteos-bembos-bembos-te-regala-un-viaje-a-cancun', NULL),
                (26, 1, 1, NULL, NULL, 1, 5, 'Papa Johns te lleva a Machu Picchu', 'Papa Johns te lleva a disfrutar de Machu Picchu La Maravilla del Mundo.  Participa en nuestro sorteo y gana 2 estadías con todo pagado en un lujoso hotel.', 'Machu Picchu a tu alcance, Gana 2 estadías completas en el Hotel Machu Picchu, al aldo de la ciudadela Inca,', '', 'El 22/12/2011', 'Reclamar premio en Papa Johns de San Borja', 'selecciondetalentos@papajohns.com.pe', '511 20294-00', '', 'Promo-7964-fc31d5f4e775c0127468808bbafae3d3_80.jpg', 1, 0, 1, 0, 0, 1, 1, 1, 6, '2011-12-05 18:07:03', NULL, NULL, NULL, '', '', 'sorteos-papa-johns-papa-johns-te-lleva-a-machu-picchu', NULL);";
        $this->_db->query($sql);
        
        $sql = "INSERT INTO `categoria_beneficio` (`id`, `categoria_id`, `beneficio_id`) VALUES
                (32, 11, 25),
                (33, 11, 26);";
        $this->_db->query($sql);
        
        $sql = "INSERT INTO `beneficio_version` (`id`, `beneficio_id`, `creado_por`, `stock`, `stock_actual`, `fecha_inicio_vigencia`, `fecha_fin_vigencia`, `fecha_registro`, `activo`) VALUES
                (26, 25, 0, NULL, 0, '2011-12-05 00:00:00', '2011-12-21 00:00:00', '2011-12-05 17:20:51', 1),
                (27, 26, 0, NULL, 0, '2011-12-05 00:00:00', '2011-12-22 00:00:00', '2011-12-05 18:07:03', 0),
                (28, 26, 0, 0, 0, '2011-12-05 00:00:00', '2011-12-22 00:00:00', '2011-12-06 02:04:58', 0),
                (29, 26, 0, 0, 0, '2011-12-05 00:00:00', '2011-12-22 00:00:00', '2011-12-06 02:05:46', 1);";
        $this->_db->query($sql);
         */
        return true;
    }
}
