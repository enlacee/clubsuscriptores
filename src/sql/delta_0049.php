<?php

/**
 * Description of delta_0001
 *
 * @author FCJ
 */
class Delta_0049 extends App_Migration_Delta
{
    protected $_autor = 'Favio Condori';
    protected $_desc = 'Data para tabla de distrito_entrega';
    
    public function up()
    {
        $sql = "INSERT INTO distrito_entrega (cciudis, dciudis, cod_ubigeo, sregact) VALUES
(2, 'SANTIAGO DE SURCO', 1501, 'S'), 
(3, 'LA VICTORIA', 1501, 'S'), 
(4, 'ANCON', 1501, 'S'), 
(5, 'ATE', 1501, 'S'), 
(6, 'BARRANCO', 1501, 'S'), 
(7, 'BREÑA', 1501, 'S'), 
(8, 'CARABAYLLO', 1501, 'S'), 
(9, 'COMAS', 1501, 'S'), 
(10, 'CHACLACAYO', 1501, 'S'), 
(11, 'CHORRILLOS', 1501, 'S'), 
(12, 'EL AGUSTINO', 1501, 'S'), 
(13, 'JESUS MARIA', 1501, 'S'), 
(14, 'LA MOLINA', 1501, 'S'), 
(15, 'LINCE', 1501, 'S'), 
(16, 'LURIGANCHO', 1501, 'S'), 
(17, 'LURIN', 1501, 'S'), 
(18, 'MAGDALENA DEL MAR', 1501, 'S'), 
(19, 'MIRAFLORES', 1501, 'S'), 
(20, 'PACHACAMAC', 1501, 'S'), 
(21, 'PUCUSANA', 1501, 'S'), 
(22, 'PUEBLO LIBRE', 1501, 'S'), 
(23, 'PUENTE PIEDRA', 1501, 'S'), 
(24, 'PUNTA NEGRA', 1501, 'S'), 
(25, 'PUNTA HERMOSA', 1501, 'S'), 
(26, 'RIMAC', 1501, 'S'), 
(36, 'SAN JUAN DE LURIGANCHO', 1501, 'S'), 
(37, 'SANTA MARIA DEL MAR', 1501, 'S'), 
(38, 'SANTA ROSA', 1501, 'S'), 
(40, 'CIENEGUILLA', 1501, 'S'), 
(41, 'CALLAO', 701, 'S'), 
(42, 'BELLAVISTA', 701, 'S'), 
(43, 'CARMEN DE LA LEGUA', 701, 'S'), 
(44, 'LA PERLA', 701, 'S'), 
(45, 'LA PUNTA', 701, 'S'), 
(46, 'SAN BORJA', 1501, 'S'), 
(50, 'VILLA EL SALVADOR', 1501, 'S'), 
(51, 'LOS OLIVOS', 1501, 'S'), 
(52, 'SANTA ANITA', 1501, 'S'), 
(53, 'BARRANCA', 1502, 'S'), 
(27, 'SAN BARTOLO', 1501, 'S'), 
(29, 'INDEPENDENCIA', 1501, 'S'), 
(30, 'SAN JUAN DE MIRAFLORES', 1501, 'S'), 
(31, 'SAN LUIS', 1501, 'S'), 
(32, 'SAN MARTIN DE PORRES', 1501, 'S'), 
(33, 'SAN MIGUEL', 1501, 'S'), 
(34, 'SURQUILLO', 1501, 'S'), 
(63, 'MALA', 1505, 'S'), 
(65, 'MATUCANA', 1507, 'S'), 
(71, 'P.J.ROSALES', NULL, 'S'), 
(76, 'CAIMA', NULL, 'S'), 
(78, 'CAÑETE', 1505, 'S'), 
(81, 'CASAPALCA', NULL, 'S'), 
(87, 'PAPA LEON XIII', NULL, 'S'), 
(88, 'PARAMONGA', 1502, 'S'), 
(97, 'SAN MATEO', 1507, 'S'), 
(101, 'SUPE PUEBLO', NULL, 'S'), 
(102, 'SUPE PUERTO', 1502, 'S'), 
(113, 'CHANCAY', 1506, 'S'), 
(115, 'CHILCA', 1505, 'S'), 
(119, 'HUACHO', 1508, 'S'), 
(123, 'HUARAL', 1506, 'S'), 
(125, 'ZAPALLAL', NULL, 'S'), 
(39, 'VENTANILLA', 701, 'S'), 
(1, 'LIMA', 1501, 'S'), 
(140, 'HUAROCHIRI', 1507, 'S'), 
(141, 'ASIA', 1505, 'S'), 
(142, 'PLAYA', NULL, 'S'), 
(2330, 'HUACHIPA', NULL, 'S'), 
(2342, 'PANAMA', NULL, 'S'), 
(4639, 'SUPE', NULL, 'S'), 
(4640, 'PATIVILCA', NULL, 'S'), 
(2448, 'CAJATAMBO', 1503, 'S'), 
(2337, 'CANTA', 1504, 'S'), 
(2449, 'HUAURA', 1508, 'S'), 
(2450, 'OYON', 1509, 'S'), 
(2501, 'PACHANGARA', 1509, 'S'), 
(28, 'SAN ISIDRO', 1501, 'S'), 
(2496, 'SANTA EULALIA', 1507, 'S'), 
(35, 'VILLA MARIA DEL TRIUNFO', 1501, 'S');";
        $this->_db->query($sql);
        return true;
    }
}
