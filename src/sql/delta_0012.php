<?php

class Delta_0012 extends App_Migration_Delta
{
    protected $_autor = 'Paul Taboada';
    protected $_desc = 'script de inserts';

    public function up()
    {
        $sql = "INSERT INTO `usuario` (`id`, `email`, `pswd`, `salt`, `rol`, `token_activacion`, `token_expiracion`, `activo`, `ultimo_login`, `fecha_registro`, `fecha_actualizacion`) VALUES
          (1, 'eco.suscriptores@gmail.com', 'sha1\$50239\$bfaf5c837225f155d5449577b8d776b6f01f2677', '', 'admin', NULL, NULL, 1, NULL, '2011-10-13 00:00:00', '2011-10-13 00:00:00');";
        $this->_db->query($sql);

        $sql = "INSERT INTO `administrador` (`id`, `usuario_id`, `nombres`, `apellidos`, `sexo`, `fecha_nacimiento`, `tipo_documento`, `numero_documento`, `telefono`) VALUES
          (1, 1, 'El Comercio', 'Club Suscriptores', 'M', '1976-01-01', 'DNI', '20568951', '5432687')";
        $this->_db->query($sql);

        /*
          $sql = "INSERT INTO `t_actsuscriptor` (`id`, `cod_entesuscriptor`, `des_apepaterno`, `des_apematerno`, `des_nombres`, `des_razonsocial`, `fch_nacimiento`, `cod_tipdocid`, `des_numdocid`, `tip_sexoente`, `tip_estadocivil`, `des_instruccion`, `des_email`, `des_numtelf01`, `des_numtelf02`, `des_numtelf03`, `des_numfax`, `des_profesion`, `des_ocupacion`, `des_empresa`, `des_cargo`, `des_club`, `des_direccion_benef`, `est_actualizacion`, `fch_actualizacion`, `fch_actualizacion_dt`, `num_familia`, `cod_entesuscriptor_padre`, `des_interes`, `relacion`, `referido`, `cod_suscripcion`, `id_beneficiario`, `estado_beneficiario`, `estado_reparto`, `tipo_actualizacion`, `est_sus`, `fch_registro`) VALUES
          (1, 'E-SOL20111', 'Vargas', 'Montenegro', 'Alberto Alfredo', '', '10/12/1985', 'DNI', '04236256', 'M', 'S', NULL, NULL, '452-6164', '556-3218', '454-2254', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'IN', NULL, 'AC', NULL),
          (2, 'E-SOL20112', 'Valverde', 'Galvez', 'Beto Esteban', '', '05/05/1985', 'DNI', '04652320', 'M', 'S', NULL, NULL, '725-2563', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'AC', NULL, 'IN', NULL),
          (3, 'E-SOL20113', 'Perez', 'Palomino', 'Maria Rosario', '', '02/11/1985', 'DNI', '05369852', 'F', 'S', NULL, NULL, '653-2524', '465-5236', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'AC', NULL, 'AC', NULL),
          (4, 'E-SOL20114', 'Contreras', 'Silva', 'Andrea Fabian', '', '09/08/1985', 'DNI', '05632894', 'F', 'S', NULL, NULL, '556-1985', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'IN', NULL, 'IN', NULL),
          (5, 'E-SOL20115', 'Alba', 'Castillo', 'Jesus Andrés', '', '12/01/1985', 'DNI', '06523984', 'M', 'S', NULL, NULL, '789-5630', '446-3120', '552-1523', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'AC', NULL, 'AC', NULL),
          (6, 'E-SOL20108', 'Perez', 'Castro', 'Juan', '', '10/12/1985', 'DNI', '04236212', 'M', 'S', NULL, NULL, '452-6164', '556-3218', '454-2254', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'AC', NULL, 'AC', NULL),
          (7, 'E-SOL20107', 'Gonzales', 'Aguayo', 'Erick', '', '18/04/1985', 'DNI', '07263356', 'M', 'S', NULL, NULL, '452-6164', '556-3218', '454-2254', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'AC', NULL, 'AC', NULL);";
          $this->_db->query($sql);

          $sql = "INSERT INTO `usuario` (`id`, `email`, `pswd`, `salt`, `rol`, `token_activacion`, `token_expiracion`, `activo`, `ultimo_login`, `fecha_registro`, `fecha_actualizacion`) VALUES
          (1, 'eco.suscriptores@gmail.com', 'sha1\$50239\$bfaf5c837225f155d5449577b8d776b6f01f2677', '', 'gestor', NULL, NULL, 1, NULL, '2011-10-13 00:00:00', '2011-10-13 00:00:00'),
          (2, 'ptaboada@e-solutions.com.pe', 'sha1\$50239\$bfaf5c837225f155d5449577b8d776b6f01f2677', NULL, 'establecimiento', NULL, NULL, 1, NULL, '2011-10-13 00:00:00', '2011-10-18 00:00:00'),
          (3, 'fcondori@e-solutions.com.pe', 'sha1\$50239\$bfaf5c837225f155d5449577b8d776b6f01f2677', NULL, 'admin', NULL, NULL, 1, NULL, '2011-10-13 00:00:00', '2011-10-18 00:00:00'),
          (4, 'entrepixeles@hotmail.com', 'sha1\$50239\$bfaf5c837225f155d5449577b8d776b6f01f2677', NULL, 'suscriptor', NULL, NULL, 1, NULL, '2011-10-13 00:00:00', '2011-10-18 00:00:00');";
          $this->_db->query($sql);

          $sql = "INSERT INTO `administrador` (`id`, `usuario_id`, `nombres`, `apellidos`, `sexo`, `fecha_nacimiento`, `tipo_documento`, `numero_documento`, `telefono`) VALUES
          (1, 1, 'El Comercio', 'Club Suscriptores', 'M', '1976-01-01', 'DNI', '20568951', '5432687'),
          (2, 2, 'Paul Carlos', 'Taboada', 'M', '1989-06-19', 'DNI', '01412155', '7776164'),
          (3, 3, 'Favio E.', 'Condori Juárez', 'M', '1989-01-20', 'DNI', '05656665', '949160775'),
          (4, 4, 'Joan', 'Peramás Ceras', 'M', '1989-11-18', 'DNI', '02632544', '956232323');";
          $this->_db->query($sql);

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
          (12, 'Farmacias', NULL);";
          $this->_db->query($sql);
         */
        $sql = "INSERT INTO `tipo_beneficio` (`id`, `nombre`, `descripcion`, `abreviado`, `slug`, `activo`) VALUES
            (1, 'Beneficios permanentes', NULL, 'Beneficio', 'beneficio', 1),
            (2, 'Promociones', NULL, 'Promo', 'promo', 1),
            (3, 'Cierra puertas', NULL, 'C. Puertas', 'cierra-puertas', 1),
            (4, 'Ticket', NULL, 'Ticket', 'ticket', 1),
            (5, 'Sorteos', NULL, 'Sorteo', 'sorteo', 1);";
        $this->_db->query($sql);
        /*
          $sql = "INSERT INTO `encuesta` (`id`, `creado_por`, `actualizado_por`, `nombre`, `numero_opciones`, `numero_respuestas`, `activo`, `pregunta`, `fecha_inicio`, `fecha_fin`, `fecha_registro`, `fecha_actualizacion`) VALUES
          (1, 1, 1, 'Sistema Operativo', 5, 0, 1, '¿Que sistema operativo usa con mayor frecuencia?', '2011-11-23 00:00:00', '2011-12-30 00:00:00', '2011-11-23 00:00:00', NULL),
          (2, 1, 1, 'Regulación entradas estadio', 2, 0, 0, '¿Esta de acuerdo con la regulación de entradas a los estadios?', '2011-11-23 00:00:00', '2011-12-15 00:00:00', '2011-11-23 00:00:00', NULL),
          (3, 1, 1, 'Tarjeta de crédito', 4, 0, 0, '	¿Que tarjeta de crédito usa?', '2011-11-23 00:00:00', '2011-12-20 00:00:00', '2011-11-23 00:00:00', NULL),
          (4, 1, 1, 'Navegador - Browser', 5, 0, 0, '¿Que navegador usa?', '2011-11-08 00:00:00', '2011-12-25 00:00:00', '2011-11-23 00:00:00', NULL);";
          $this->_db->query($sql);


          $sql = "INSERT INTO `opcion_encuesta` (`id`, `encuesta_id`, `opcion`) VALUES
          (1, 1, 'Windows 7'),
          (2, 1, 'Snow Leopard'),
          (3, 1, 'Ubuntu'),
          (4, 1, 'Solaris'),
          (5, 1, 'OS/400'),
          (6, 2, 'Si, estoy de acuerdo'),
          (7, 2, 'No, no estoy de acuerdo'),
          (8, 3, 'Visa'),
          (9, 3, 'American Express'),
          (10, 3, 'MasterCard'),
          (11, 3, 'Diners Club'),
          (12, 4, 'Firefox'),
          (13, 4, 'Chrome'),
          (14, 4, 'Safari'),
          (15, 4, 'Opera'),
          (16, 4, 'Nescape');";
          $this->_db->query($sql);


          $sql = "INSERT INTO `articulo` (`id`, `creado_por`, `actualizado_por`, `slug`, `titulo`, `contenido`, `portada`, `veces_visto`, `activo`, `fecha_inicio_vigencia`, `fecha_fin_vigencia`, `fecha_registro`, `fecha_actualizacion`) VALUES
          (1, 1, 1, 'club-suscriptores-disfrutan-inauguracion-madam-tusan', 'Club de Suscriptores disfrutan la Inauguración de Madam Tusan', 'El restaurante Madam Tusan no es cualquier otro chifa. Este ofrece platos chinos matizados con productos peruanos.', 0, 20, 1, '2011-10-01 00:00:00', '2011-12-30 00:00:00', '2011-10-14 00:00:00', '2011-10-14 00:00:00'),
          (2, 1, 1, 'pre-estreno-capitan-america', 'Pre-estreno de Capitán América fue todo un éxito', 'Los suscriptores de El Comercio, disfrutaron totalmente gratis del Pre-estreno del Capitán América.', 1, 15, 1, '2011-07-27 00:00:00', '2011-12-30 00:00:00', '2011-07-27 00:00:00', '2011-11-01 00:00:00'),
          (3, 1, 1, 'la-casona-del-valle', 'La Casona del Valle', 'Con gran entusiasmo los suscriptores participaron de un rico almuerzo en La Casona del Valle.', 0, 10, 1, '2011-07-16 00:00:00', '2011-12-28 00:00:00', '2011-07-16 00:00:00', '2011-11-04 00:00:00'),
          (4, 1, 1, 'avant-premier-de-harry-potter', 'Avant Premier de Harry Potter', 'Emocionados padres e hijos asistieron a el Estreno de la última película de Harry Potter.', 0, 5, 1, '2011-07-14 00:00:00', '2012-01-28 00:00:00', '2011-07-14 00:00:00', '2011-11-05 00:00:00'),
          (5, 1, 1, 'inaguracion-de-madam-tusan', 'Inaguración de Madam Tusan', 'Gastón Acurio invito a nuestros suscriptores a la inauguración de Madam Tusan, el nuevo restaurante de comida china-fusión.', 0, 2, 0, '2011-06-03 00:00:00', '2012-12-30 00:00:00', '2011-06-03 00:00:00', '2011-08-30 00:00:00');";
          $this->_db->query($sql);


          $sql = "INSERT INTO `galeria_imagenes`
          (`id`, `articulo_id`, `titulo`, `path_imagen`, `descripcion`, `orden`, `es_principal`) VALUES

          (1, 1, 'Imagen 1', 'madamTusan1.jpg', NULL, 1, 0),
          (2, 1, 'Imagen 2', 'madamTusan2.jpg', NULL, 2, 0),
          (3, 1, 'Imagen 3', 'madamTusan3.jpg', NULL, 3, 0),
          (4, 1, 'Imagen 4', 'madamTusan4.jpg', NULL, 4, 0),
          (5, 1, 'Imagen 5', 'madamTusan5.jpg', NULL, 5, 0),
          (6, 1, 'Imagen 6', 'madamTusan6.jpg', NULL, 6, 1),
          (7, 1, 'Imagen 7', 'madamTusan7.jpg', NULL, 7, 0),
          (8, 1, 'Imagen 8', 'madamTusan8.jpg', NULL, 8, 0),
          (9, 1, 'Imagen 9', 'madamTusan9.jpg', NULL, 9, 0),

          (10, 2, 'El Capitán América', 'captain_America.jpg', NULL, 1, 1),
          (11, 2, 'Muñecos Capitán América', 'captain_America1.jpg', NULL, 2, 0),
          (12, 2, 'Polo Capitán América', 'captain_America2.jpg', NULL, 3, 0),
          (13, 2, 'Juguetes Capitán América', 'captain_America3.jpg', NULL, 4, 0),
          (14, 2, 'Pintura en Pared del Capitán América', 'captain_America4.jpg', NULL, 5, 0),
          (15, 2, 'Recordando la Muerte del Capitán América', 'captain_America5.jpg', NULL, 6, 0),

          (16, 3, 'Dueños', 'casonaDelValle.jpg', NULL, 3, 0),
          (17, 3, 'Bathroom', 'casonaDelValle1.jpg', NULL, 2, 0),
          (18, 3, 'Dinning room', 'casonaDelValle2.jpg', NULL, 1, 0),
          (19, 3, 'La Fachada', 'casonaDelValle3.jpg', NULL, 4, 0),
          (20, 3, 'El paisaje', 'casonaDelValle4.jpg', NULL, 5, 0),
          (21, 3, 'La principal', 'casonaDelValle5.jpg', NULL, 6, 1),
          (22, 3, 'Bedroom', 'casonaDelValle6.jpg', NULL, 7, 0),

          (23, 4, 'Los fans', 'harryPotter.jpg', NULL, 5, 0),
          (24, 4, 'Los actores', 'harryPotter2.jpg', NULL, 4, 0),
          (25, 4, 'Los personajes', 'harryPotter3.jpg', NULL, 3, 0),
          (26, 4, 'Actores Principales', 'harryPotter4.jpg', NULL, 2, 0),
          (27, 4, 'Harry Potter the movie', 'harryPotter5.jpg', NULL, 1, 0),
          (28, 4, 'La noche de Gala', 'harryPotter6.jpg', NULL, 6, 0),
          (29, 4, 'La actuación', 'harryPotter7.jpg', NULL, 7, 1),

          (30, 5, 'Gastón en Mistura', 'inauguraMadamTusan.jpg', NULL, 1, 0),
          (31, 5, 'Comedor Principal', 'inauguraMadamTusan1.jpg', NULL, 2, 1),
          (32, 5, 'Adornos', 'inauguraMadamTusan2.jpg', NULL, 3, 0),
          (33, 5, 'Decoraciones', 'inauguraMadamTusan3.jpg', NULL, 4, 0),
          (34, 5, 'Logotipo', 'inauguraMadamTusan4.jpg', NULL, 5, 0),
          (35, 5, 'Reunion de Inauguracion', 'inauguraMadamTusan5.jpg', NULL, 6, 0),
          (36, 5, 'Gaston Acurio', 'inauguraMadamTusan6.jpg', NULL, 7, 0),
          (37, 5, 'Hermoso Restaurant', 'inauguraMadamTusan7.jpg', NULL, 8, 0),

          (38, 1, 'Imagen 10', 'madamTusan10.jpg', NULL, 10, 0),
          (39, 3, NULL, 'casonaDelValle7.jpg', NULL, 8, 0),
          (40, 3, NULL, 'casonaDelValle8.jpg', NULL, 9, 0),
          (41, 4, NULL, 'harryPotter1.jpg', NULL, 8, 0),
          (42, 4, NULL, 'harryPotter8.jpg', NULL, 9, 0);";
          $this->_db->query($sql);

          $sql = "INSERT INTO `categoria`
          (`id`, `creado_por`, `actualizado_por`, `nombre`, `descripcion`, `activo`, `slug`, `fecha_registro`, `fecha_actualizacion`) VALUES
          (1, 1, 1, 'Deportes', NULL, 1, '', '2011-10-27 00:00:00', '2011-10-27 00:00:00'),
          (2, 1, 1, 'Electrogar', NULL, 1, '', '2011-10-27 00:00:00', '2011-10-27 00:00:00'),
          (3, 1, 1, 'Entretenimiento', NULL, 1, '', '2011-10-27 00:00:00', '2011-10-27 00:00:00'),
          (4, 1, 1, 'Gastronomía', NULL, 1, '', '2011-10-27 00:00:00', '2011-10-27 00:00:00'),
          (5, 1, 1, 'Hogar', NULL, 1, '', '2011-10-27 00:00:00', '2011-10-27 00:00:00'),
          (6, 1, 1, 'Niños', NULL, 1, '', '2011-10-27 00:00:00', '2011-10-27 00:00:00'),
          (7, 1, 1, 'Moda', NULL, 1, '', '2011-10-27 00:00:00', '2011-10-27 00:00:00'),
          (8, 1, 1, 'Oficina', NULL, 1, '', '2011-10-27 00:00:00', '2011-10-27 00:00:00'),
          (9, 1, 1, 'Salud y Belleza', NULL, 1, '', '2011-10-27 00:00:00', '2011-10-27 00:00:00'),
          (10, 1, 1, 'Servicios', NULL, 1, '', '2011-10-27 00:00:00', '2011-10-27 00:00:00');";
          $this->_db->query($sql);

          $sql = "INSERT INTO `establecimiento` (`id`, `tipo_establecimiento_id`, `creado_por`, `actualizado_por`, `nombre`, `presentacion`, `RUC`, `direccion`, `contacto`, `telefono_contacto`, `email_contacto`, `numero_usuarios`, `activo`, `fecha_registro`, `fecha_actualizacion`, `path_imagen`) VALUES
          (1, 1, 1, 1, 'Papa Johns', 'Papa Johns Pizza - Los expertos en la preparación de pizzas de la mejor calidad, con los mejores ingredientes.', '20181815152', 'Calle Machaypuito 115 San Isidro - Lima, Perú', 'Jhons Micks Lounge', '511 20294-00', 'selecciondetalentos@papajohns.com.pe', 1, 1, '2011-11-17 00:00:00', '2011-11-17 00:00:00', 'papa_johns.jpg'),
          (2, 5, 1, 1, 'Cine Planet', 'Cadena de cines líder en el Perú. Cartelera en Perú, estrenos de películas, películas en cartelera, listín cinematográfico.', '20542121212', 'Av. Angamos Este 2684', 'Dennis Valdivieso', '624 9500', 'contactanos@cineplanet.com.pe', NULL, 1, '2011-11-23 00:00:00', '2011-11-23 00:00:00', 'CinePlanetlittle.png'),
          (3, 4, 1, 1, 'Amarige', 'Corte Damas y Caballeros, Tratamientos Capilares y Extensiones', '20563269852', 'Avenida Primavera, 609 - Urb. Chacarilla del Estanque', 'Gisela Valcarcel', '319-0090', 'contactenos@amarige.com.pe', NULL, 1, '2011-11-15 00:00:00', '2011-11-15 00:00:00', 'amarige1.png'),
          (4, 1, 1, 1, 'Friday''s', 'En Friday''s encontrarás lo mejor en costillas, ribs, wings, hamburguesas, fajitas, quesadillas, happy hour, almuerzo ejecutivo, tragos y mucho más.', '20152220052', 'Óvalo Gutiérrez Miraflores', 'Alfredo Gonzales', '610-6900', 'contactenos@fridays.com.pe', NULL, 1, '2011-11-15 00:00:00', '2011-11-18 00:00:00', 'tgi_fridays_logo.png'),
          (5, 8, 1, 1, 'ArtMusic', 'Si eres aficionado o un profesional, te podemos ayudar a encontrar el  instrumento musical que necesites.', '20525454555', 'Jr. Moquegua 880 Int. 115 - Lima', 'Pedro Malpartida', '433-0115', 'contactanos@artmusic.com.pe', NULL, 1, '2011-11-16 00:00:00', '2011-11-16 00:00:00', 'art_music.png'),
          (6, 2, 1, 1, 'Ripley', 'Disfruta de nuestra tienda virtual y compra más fácil, rápido y seguro.', '20956464545', 'Cal. Las Begonias Nro. 577 Res. San Isidro (Frente a Edificio Brescia) Lima Lima San Isidro', 'Fiorella Cayo', '6115757', 'contactenos@ripley.com.pe', NULL, 1, '2011-11-17 00:00:00', '2011-11-17 00:00:00', 'ripley_logo.jpg'),
          (7, 10, 1, 1, 'Mancora', 'Es uno de los hoteles más acogedores que se encuentran en Máncora, ahora con nuevos ambientes que permitirán que su estadía sea aún más placentera.', '20548522112', 'Kilómetro 1214 de la Antigua Panamericana Norte al sur del balneario de Máncora', 'Solman Gonzales', '(51-1) 326-1731      ', 'playabonita156@terra.com.pe', NULL, 1, '2011-11-18 00:00:00', '2011-11-20 00:00:00', 'playa-bonita-mancora-bungalows-logo.png'),
          (8, 1, 1, 1, 'Señor Limón', 'Ofrecemos conceptos únicos e integrales de alimentos, bebidas y entretenimiento que sobrepasen las expectativas de nuestros invitados.', '20586232422', 'Av. Javier Prado Este 5335, Camacho La Molina', 'Solman Gonzales', '715-5320', 'reservas@seniorlimon.com.pe', NULL, 1, '2011-11-19 00:00:00', '2011-11-19 00:00:00', 'senior_limon.jpg'),
          (9, 9, 1, 1, 'Niza', 'Se realizan todo tipo de eventos corporativos y empresariales, aniversarios y fiestas profondos (local, orquestas, shows, etc).', '20563248925', 'Av. Arequipa 1501 Lince ', 'Giancarlo Bustamante', '265 3112 ', 'Discotecaniza@hotmail.com', NULL, 1, '2011-11-16 00:00:00', '2011-11-19 00:00:00', 'disco-niza.jpg'),
          (10, 12, 1, 1, 'GNC', 'GNC te ofrece un mundo de soluciones que ayudarán a que tú y tu familia vivan mejor.', '20659865656', 'Calle Los Cipreses 201 Urb. Orrantia', 'Julio Florian', '991203110', 'contactanos@gnc.com.pe', NULL, 1, '2011-11-10 00:00:00', '2011-11-20 00:00:00', 'gnc_live_well.jpg'),
          (11, 4, 1, 1, 'D''Zory', 'Tratamientos faciales, corporales y terapeuticos. Visitenos.', '20154638956', 'Av. Santa Cruz 345-Miraflores', 'Zoraida Vargas', '511 6399301', 'spadzory@gmail.com', NULL, 1, '2011-11-11 00:00:00', '2011-11-13 00:00:00', 'SPA_LOGO_WS.jpg');";
          $this->_db->query($sql);

          $sql = "INSERT INTO `establecimiento_usuario` (`id`, `establecimiento_id`, `usuario_id`) VALUES
          (1, 9, 2);";
          $this->_db->query($sql);

          $sql = "INSERT INTO `beneficio` (`id`, `creado_por`, `actualizado_por`, `establecimiento_id`, `tipo_beneficio_id`, `titulo`, `descripcion`, `descripcion_corta`, `valor`, `cuando`, `direccion`, `email_info`, `telefono_info`, `path_logo`, `maximo_por_subscriptor`, `sin_limite_por_suscriptor`, `sin_stock`, `es_destacado`, `es_destacado_principal`, `activo`, `publicado`, `veces_visto`, `fecha_registro`, `chapita`, `chapita_color`, `slug`, `ncuponesconsumidos`) VALUES
          (1, 1, 1, 4, 2, 'Masajes Calientes', 'Renuévate con unos estupendos masajes, relajate antes de empezar con una nueva semana laboral.', 'Relajate antes de empezar de nuevo con una semana laboral.', 'Descuento exclusivo para los suscriptores del Club El Comercio.', NULL, NULL, NULL, NULL, 'fotodestacada.png', 5, 0, 0, 1, 1, 1, 1, 0, '2011-10-10 00:00:00', '2x1', 'rojo', 'masajes-calientes', NULL),
          (2, 1, 1, 1, 2, 'After Office', 'Ven a diverteté con tus amigos después de salir de tu oficina.', 'Ven a divertirte con tus amigos despues del trabajo.', 'Descuento exclusivo para los suscriptores del Club El Comercio.', 'Viernes 5 de agosto hasta Domingo 7 de agosto, de 8am a 10pm.', 'Av. Arequipa 4044. A la altura de texto referencial.', 'info@amarige.pe ', '222-4025', 'fridays.png', 5, 0, 0, 1, 0, 0, 0, 0, '2011-10-10 00:00:00', '3x2', 'rojo', 'after-office', NULL),
          (3, 1, 1, 2, 2, 'Oferta de Navidad', 'El mejor pavo navideño, para que lo disfrutes con los seres que más amas en esta noche buena.', 'El mejor pavo navideño, para que lo disfrutes al máximo.', 'Descuento exclusivo para los suscriptores del Club El Comercio.', NULL, NULL, NULL, NULL, 'papajohns.png', 4, 0, 0, 1, 0, 0, 1, 0, '2011-10-12 00:00:00', '2x1', 'rojo', 'new-beneficio', NULL),
          (4, 1, 1, 3, 2, 'Vitrinas relucientes', 'It''s all rigth, comming soon.', 'It''s all rigth, comming soon.', 'Descuento exclusivo para los suscriptores del Club El Comercio.', NULL, NULL, NULL, NULL, 'marriot.png', 4, 0, 0, 1, 0, 1, 0, 0, '2011-10-13 00:00:00', '3x2', 'rojo', 'new-beneficio-two', NULL),
          (5, 1, 1, 5, 2, '50% dscto en música', 'Escucha de la mejor música, obten descuentos en los generos de rock, baladas, heavy metal, punk, pop en nuestras tiendas.', 'Escucha de la mejor música, obten descuentos de las mejores bandas de tu gusto.', 'Descuento exclusivo para los suscriptores del Club El Comercio.', NULL, NULL, NULL, NULL, 'suena_dscto_musica.jpg', 6, 0, 0, 1, 0, 0, 1, 0, '2011-10-18 00:00:00', '50%', 'rojo', '50-porciento-dscto-musica', NULL),
          (6, 1, 1, 6, 2, '15% en ropa', 'Renuévate, viste a la moda y no te pierdas las nuevas ofertas que tenemos para ti, te sorprenderas.', 'Renuévate, viste a la moda y no te pierdas las nuevas ofertas que tenemos para ti.', 'Descuento exclusivo para los suscriptores del Club El Comercio.', 'mircoles 19 de octubre hasta viernes 30 de diciembre ', NULL, NULL, NULL, 'ropa_ripley.jpg', 4, 0, 0, 0, 0, 1, 0, 0, '2011-10-18 00:00:00', '15%', 'rojo', '15-porciento-ropa', NULL),
          (7, 1, 1, 7, 2, '2x1 en hotel mancora', 'Ven a relájate con tus seres queridos o con una persona especial, pasa una noche romántica aqui en Mancora, con una grandiosa vista al mar, y disfruta de todos nuestros servicios.', 'Ven a relajárte con tus familiares o con una persona especial, pasa una noche romántica aqui en Mancora.', 'Descuento exclusivo para los suscriptores del Club El Comercio.', NULL, NULL, NULL, NULL, 'hotel_mancora_2x1.jpg', 3, 0, 0, 0, 0, 1, 1, 0, '2011-10-17 00:00:00', '2x1', 'rojo', '2-x-1-hotel-mancora', NULL),
          (8, 1, 1, 8, 2, '34% en comida criolla', 'No te lo pierdas, tu restaurante preferido te trae la mas exquisita comida criolla para toda la familia, te chuparas los dedos.', 'Aprovecha!, Te traemos la mas exquisita comida criolla para toda tu familia.', 'Descuento exclusivo para los suscriptores del Club El Comercio.', NULL, NULL, NULL, NULL, 'restaurant_pancha_34.jpg', 5, 0, 0, 0, 0, 1, 1, 0, '2011-10-17 00:00:00', '34%', 'rojo', '34-porciento-restaurante-pancha', NULL),
          (9, 1, 1, 9, 2, '3x1 en licores', 'Diviérte y comparte con tus amigos la mejor fiesta, obten los mejores descuentos en las mejores bebidas en nuestras discotecas.', 'Diviértete y comparte con tus amigos de la mejor fiesta, obten los mejores descuentos en las mejores bebidas.', 'Descuento exclusivo para los suscriptores del Club El Comercio.', NULL, NULL, NULL, NULL, 'licores_niza.jpg', 4, 0, 0, 0, 0, 1, 1, 0, '2011-10-18 00:00:00', '3x1', 'verde', '3-x-1-licores-niza', NULL),
          (10, 1, 1, 10, 2, '70% en vitaminas GNC', 'Te sientes cansado, estresado, sin animos. Te recomendamos vitaminas para enegizar tu mente y cuerpo.', 'Te sientes cansado, estresado, sin animos. Te recomendamos vitaminas para enegizar tu mente y cuerpo.', 'Descuento exclusivo para los suscriptores del Club El Comercio.', NULL, NULL, NULL, NULL, 'dscto_vitaminas_gmc.jpg', 6, 0, 0, 0, 0, 1, 1, 0, '2011-10-15 00:00:00', '70%', 'azul', '70-dscto-vitamimas-gmc', NULL),
          (11, 1, 1, 11, 2, '2% en belleza', 'Quieres lucir explendida y elegante en reuniones lujosas. Ven que tenemos para ti lo último en peinados.', 'Aprovecha la oportunidad de lucir explendida en reuniones importantes con los diversos peinados a la moda.', 'Descuento exclusivo para los suscriptores del Club El Comercio.', 'jueves 01 de septiembre hasta viernes 30 de diciembre ', NULL, NULL, NULL, 'belleza_spadzory.jpg', 6, 0, 0, 1, 0, 1, 1, 0, '2011-10-19 00:00:00', '2%', 'rojo', '2-porciento-belleza-spadzory', NULL),
          (12, 1, 1, 6, 2, '20% en deporte', 'Disfruta de la indumentaria de tus equipos favoritos para el mundial Brasil 2014.', 'Disfruta de la indumentaria de tus equipos favoritos para el mundial Brasil 2014.', 'Descuento exclusivo para los suscriptores del Club El Comercio.', '', NULL, NULL, NULL, 'deporte_ripley.jpg', 9, 0, 0, 1, 0, 1, 1, 0, '2011-10-19 00:00:00', '20%', 'verde', '20-porciento-deporte-ripley', NULL),
          (13, 1, 1, 11, 2, '50% en masajes', 'Relájate, desestrezate y rompe la rutina, aprovecha este momento. Masajes que ayudaran a calmar tu cuerpo.', 'Relájate, desestrézate y rompe la rutina, aprovecha este momento.', 'Descuento exclusivo para los suscriptores.', '', NULL, NULL, NULL, 'masajes_spa.jpg', 5, 0, 0, 0, 0, 1, 1, 0, '2011-11-11 00:00:00', '50%', 'rojo', '50-porciento-masajes', NULL),
          (14, 1, 1, 11, 3, 'Los mejores tratamientos', 'No te puedes perder esta oportunidad, ven con tu familia y enterate de las grandes ofertas.', 'No te puedes perder esta oportunidad, ven con tu familia ahora.', 'Descuento válido para suscriptores del comercio.', 'Los sabados y domingos', NULL, NULL, NULL, 'tratamientosspa.jpg', 4, 0, 0, 0, 0, 1, 1, 0, '2011-11-11 00:00:00', '', 'azul', 'los-mejores-tratamientos-spa-dzory-cierra-puertas', NULL),
          (15, 1, 1, 11, 3, 'Lo mejor en productos de belleza', 'Te damos los mejores precios de las mejores marcas del mercado en productos cosmeticos mundiales.', 'Te damos los mejores precios de las mejores marcas del mercado.', 'Ofertas exclusivas para clientes del Spa - Registrate!', 'Los Fin de Semana - Mes de diciembre', NULL, NULL, NULL, 'productosSpa_com.jpg', 7, 0, 0, 0, 0, 1, 0, 0, '2011-11-11 00:00:00', NULL, 'amarillo', 'los-mejor-en-productos-de-belleza-spa', NULL),
          (16, 1, 1, 11, 4, 'Exposición de Belleza con Maritza Lujan 50%', 'Una mujer emprendedora y profesional en el rubro quiere brindarte sus experiencias y conocimientos, no pierdas esta gran oportunidad de actualizarte.', 'Una mujer emprendedora y profesional quiere brindarte sus experiencias.', 'Descuentos de 50% a los suscriptores del comercio.', '25 de Diciembre 2:00. Auditorio San Agustin', NULL, NULL, NULL, 'exposicion_spa.jpg', 6, 0, 0, 0, 0, 1, 1, 0, '2011-11-11 00:00:00', '50%', 'verde', 'exposicion-de-belleza-con-maritza-lujan-cincuenta-porciento', NULL),
          (17, 1, 1, 6, 3, 'Cierrapuertas Daihatsu', 'Gran cierra puertas daihatsu stock del 2008', 'Gran cierra puertas daihatsu stock del 2008', 'Solo clientes asociados al Club de Suscriptores.', 'Sabado 16  Domingo 17 de 8am a 7pm', 'Av. La Marina 3000', 'daihatsu@ripley.com', '5612550', 'daihatsu.jpg', 3, 1, 1, 0, 0, 1, 1, 0, '2011-11-15 13:22:50', '10%', 'azul', 'ripley-cierrapuertas-daihatsu', NULL),
          (18, 1, 1, 11, 3, 'Novios 2011', 'Primer cierra Puertas solo para novios. Aproveches esta unica oferta', 'Primer cierra Puertas solo para novios', 'Solo para socios del club suscriptor', 'Domingo 3 de abril de 7pm a 8pm', 'Frente al C.C. La Fontana', 'novios@webmaster.com.pe', '47847754', 'flyer-todo-300x196.jpg', 2, 0, NULL, NULL, 0, 1, 1, 0, '2011-11-15 13:29:38', '2x1', 'rojo', 'novios-2011', NULL),
          (19, 1, 1, 6, 3, 'Fin de Temporada', 'Por fin de temporada, obten hasta un 50% de descuento en nuestros productos y seras la envidia de tus amigas.', 'Por fin de temporada 50% de descuento.', 'Solo para socios del club suscriptor', 'Este 26 y 27 de noviembre de 11am a 8pm', 'Av. 28 de Julio 1131 - Miraflores', 'info@chacha.com.pe', '94875454', 'cierrapuertaschacha.jpg', 6, 1, NULL, NULL, 0, 0, 0, 0, '2011-11-15 13:35:19', NULL, 'verde', 'fin-de-temporada', NULL),
          (20, 1, 1, 6, 1, 'Liquidacion Camionetas 2008', 'Gran liquidacion de camionetas de todas las marcas solo en distribuidoras Daihatsu.', 'Liquidacion de camionetas anio 2008', 'Solo para suscriptores de el comercio', 'Domindo 25 de noviembre de 9am a 5pm', 'Av La Marina 12313', NULL, NULL, 'camionetas_1882.jpg', 4, 0, 1, 0, 0, 1, 1, 0, '2011-11-17 16:51:36', NULL, 'azul', 'beneficios-permanentes-ripley-liquidacion-camionetas-2008', NULL),
          (21, 1, 1, 1, 1, 'Pizza 2x1 los martes', 'Todos los martes aprovecha nuestra promocion 2x1 y disfruta de nuestas deliciosas pizzas en sus variados sabores.', 'Todos los martes deliciosas pizzas a 2x1. Solo en Papa Johns.', 'Promocion exclusiva para los suscriptores de el comercion.', 'Todos los martes.', 'Av. Comandante Espinar 123 - Miraflores', '511 20294-00', '945754754', '2x1-pizza-costa-rica.jpg', 6, 1, 1, 0, 0, 1, 0, 0, '2011-11-17 17:34:08', NULL, 'amarillo', 'beneficios-permanentes-papa-johns-pizza-2x1-los-martes', NULL),
          (22, 1, 1, 1, 2, 'Pizzas 2x1', 'Disfruta de nuestras deliciosas pizzas por partida doble, ahora todos los dias. Las mejores pizzas ahora podras disfrutarlas con nuestra promocion 2x1', 'Disfruta de nuestras deliciosas pizzas por partida doble, ahora todos los dias', 'Solo para suscriptores de El Comercio', 'Todos los dias de 10am a 12pm.', 'En todos nuestros locales.', '511 20294-00', 'selecciondetalentos@papajohns.com.pe', 'Promo-1820-6e766ccd87156f2de647c4780aebf2b2_80.jpg', 4, 1, 1, 0, 0, 1, 1, 0, '2011-11-18 18:32:32', '2x1', 'rojo', 'beneficios-permanentes-papa-johns-pizzas-2x1', NULL),
          (23, 1, 1, 2, 4, 'Pre-estreno The Avenger', 'Disfruta del pre-estreno de The Avengers con esta promocion 2x1 durante toda la semana y a mitad de precio despues del estreno', 'Disfruta del pre-estreno de The Avengers con esta promocion 2x1', 'Solo para suscriptores de El Comercio', 'Desde este jueves 1 de diciembre hasta el fin de la cartelera', 'Ov Gutierrez - Alcazar', NULL, NULL, 'Promo-1017-4bac653e7c870289be6455d26dd1dab7_80.jpeg', 5, 0, 0, 0, 0, 1, 1, 0, '2011-11-18 21:01:52', '2x1', 'azul', 'ticket-cine-planet-pre-estreno-the-avenger', NULL),
          (24, 1, 1, 6, 2, 'Polera Edicion Assasin Creed', 'Exclusivas poleras edicion assasin creed, del juego de consola mas popular del momento. No te pierdas este exclusivo diseño.', 'Poleras edicion assasin creed. Solo en Ripley', 'Para suscriptores de El Comercio', '', 'En todas nuestras tiendas.', 'assasincreed@ripley.com.pe', '93884938', 'Promo-8467-9532c53f7390c9fde86a8bfbe2e23df2_80.jpg', 2, 0, 0, 0, 0, 0, 1, 0, '2011-11-20 12:59:35', 'Exc', 'rojo', 'promociones-ripley-polera-edicion-assasin-creed', NULL);";
          $this->_db->query($sql);


          $sql = "INSERT INTO `categoria_beneficio` (`id`, `categoria_id`, `beneficio_id`) VALUES
          (1, 9, 1),
          (2, 3, 2),
          (3, 8, 2),
          (5, 4, 3),
          (6, 5, 4),
          (7, 8, 4),
          (9, 3, 5),
          (10, 7, 6),
          (11, 5, 7),
          (12, 4, 8),
          (13, 3, 9),
          (14, 9, 10),
          (15, 9, 11),
          (16, 1, 12),
          (17, 9, 13),
          (18, 9, 14),
          (19, 10, 14),
          (20, 9, 15),
          (21, 8, 16),
          (22, 9, 16),
          (23, 2, 17),
          (24, 7, 18),
          (25, 7, 19),
          (26, 2, 20),
          (27, 4, 21),
          (28, 4, 22),
          (29, 3, 23),
          (30, 1, 24),
          (31, 7, 24);";
          $this->_db->query($sql);

          $sql = "INSERT INTO `beneficio_version` (`id`, `beneficio_id`, `stock`, `stock_actual`, `fecha_inicio_vigencia`, `fecha_fin_vigencia`, `fecha_registro`, `activo`) VALUES
          (1, 1, 60, 60, '2011-09-15 00:00:00', '2011-12-30 00:00:00', '2011-09-15 00:00:00', 1),
          (2, 2, 50, 50, '2011-09-01 00:00:00', '2011-12-30 00:00:00', '2011-09-01 00:00:00', 1),
          (3, 3, 40, 40, '2011-09-30 00:00:00', '2011-12-28 00:00:00', '0000-00-00 00:00:00', 1),
          (4, 4, 80, 80, '2011-09-25 00:00:00', '2011-12-30 00:00:00', '2011-09-30 00:00:00', 1),
          (5, 5, 100, 100, '2011-10-18 00:00:00', '2011-12-30 00:00:00', '2011-11-14 00:00:00', 1),
          (6, 6, 118, 118, '2011-10-19 00:00:00', '2011-12-30 00:00:00', '2011-10-19 00:00:00', 1),
          (7, 7, 30, 30, '2011-10-01 00:00:00', '2012-03-30 00:00:00', '2011-10-01 00:00:00', 1),
          (8, 8, 40, 40, '2011-11-01 00:00:00', '2011-12-30 00:00:00', '2011-11-01 00:00:00', 1),
          (9, 9, 50, 50, '2011-10-01 00:00:00', '2011-12-30 00:00:00', '2011-10-01 00:00:00', 1),
          (10, 10, 80, 80, '2011-09-01 00:00:00', '2011-12-28 00:00:00', '2011-09-01 00:00:00', 1),
          (11, 11, 80, 80, '2011-09-01 00:00:00', '2011-12-30 00:00:00', '2011-09-01 00:00:00', 1),
          (12, 12, 30, 30, '2011-10-01 00:00:00', '2011-12-30 00:00:00', '2011-10-01 00:00:00', 1),
          (13, 13, 70, 70, '2011-11-11 00:00:00', '2011-12-30 00:00:00', '2011-11-11 00:00:00', 1),
          (14, 13, 50, 0, '2011-11-11 00:00:00', '2011-12-30 00:00:00', '2011-11-11 00:00:00', 0),
          (15, 14, 0, 0, '2011-11-11 00:00:00', '2011-12-30 00:00:00', '2011-11-11 00:00:00', 1),
          (16, 15, 0, 0, '2011-11-11 00:00:00', '2011-12-30 00:00:00', '2011-11-11 00:00:00', 1),
          (17, 16, 0, 0, '2011-11-11 00:00:00', '2011-12-30 00:00:00', '2011-11-11 00:00:00', 1),
          (18, 17, 0, 0, '2011-11-11 00:00:00', '2011-12-30 00:00:00', '2011-11-11 00:00:00', 1),
          (19, 18, NULL, NULL, '2011-11-13 13:39:03', '2011-12-30 13:39:08', '2011-11-15 13:39:13', 1),
          (20, 19, NULL, NULL, '2011-11-01 13:39:26', '2011-12-30 13:39:32', '2011-11-15 13:39:34', 1),
          (21, 20, NULL, NULL, '2011-11-01 13:39:52', '2011-12-30 13:39:55', '2011-11-14 13:39:57', 1),
          (22, 21, 0, 0, '2011-11-18 16:51:36', '2011-12-30 16:51:36', NULL, 1),
          (23, 22, 78, 78, '2011-11-17 00:00:00', '2012-12-30 17:34:08', NULL, 1),
          (24, 23, 0, 0, '2011-11-15 00:00:00', '2012-01-30 00:00:00', '2011-11-18 12:38:00', 1),
          (25, 24, 150, 150, '2011-11-25 00:00:00', '2011-11-30 00:00:00', '2011-11-18 12:45:53', 1);";
          $this->_db->query($sql);

          $sql = "INSERT INTO `suscriptor` (`id`, `usuario_id`, `suscriptor_padre_id`, `nombres`, `apellidos`, `sexo`, `fecha_nacimiento`, `tipo_documento`, `numero_documento`, `codigo_suscriptor`, `telefono`, `slug`, `enviar_alertas_email`, `activo`, `es_invitado`, `fecha_invitacion`, `fecha_registro`, `fecha_actualizacion`, `es_suscriptor`, `email_contacto`) VALUES
          (1, 4, NULL, 'Joan', 'Peramás', 'M', '1920-08-13', 'DNI', '41253678', '', '254354354', 'joa-peramas-c81e728d', 1, 1, 0, NULL, '2011-11-14 10:40:47', '2011-11-14 10:40:47', 1, '');";
          $this->_db->query($sql); */
        return true;
    }

}
