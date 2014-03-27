<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of Suscriptor
 *
 * @author PROYPORT
 */
class Application_Model_SuscriptorValidar
{
    public function datosSuscriptor($tipo, $numero, $considerarBeneficiario = false)
    {
        //$db = Zend_Db_Table::getDefaultAdapter();
        $db = Zend_Registry::get('db2');
        $select = $db->select();
        $select->from(
            array('s' => 't_actsuscriptor'), 
            array(
                'Cod_Suscriptor' => 'cod_entesuscriptor', 
                'EsSuscriptor' => new Zend_Db_Expr(
                    "CASE WHEN est_sus = 'AC' and estado_reparto = 'AC' " . 
                    "and cod_entesuscriptor IS NOT NULL THEN '1' ELSE '0' END"
                ), 
                'Nombres' => 'des_nombres',
                'cod_entesuscriptor'=> 'cod_entesuscriptor',
                'Apellidos' => new Zend_Db_Expr("des_apepaterno+' '+des_apematerno")
//                ,'des_email','des_email_act'
            )
        )
        ->where('(s.cod_tipdocid = ?', $tipo)
        ->where('s.des_numdocid = ?', $numero)
        ->where('s.est_sus = ?', 'AC')
        ->where('s.estado_reparto = ?', 'AC');
        
        if($considerarBeneficiario){
            $select->where('s.cod_entesuscriptor IS NOT NULL)');
            $select->orWhere('(s.cod_tipdocid = ?', $tipo)
                   ->where('s.des_numdocid = ?', $numero)
                   ->Where('s.cod_entesuscriptor IS NULL and id_beneficiario is not null and estado_beneficiario=1 )');
        } else {
            $select->where('s.cod_entesuscriptor IS NOT NULL)');
        }
        //echo $select->assemble();exit;
        
        $result = $db->fetchRow($select);
        /* if($result)
          {
          $result['Nombres'] = utf8_encode($result['Nombres']);
          $result['Apellidos'] = utf8_encode($result['Apellidos']);
          } */
        return $result;
    }

    public function productosSuscriptor($suscriptor, $codigo)
    {
        //$db = Zend_Db_Table::getDefaultAdapter();
        $db = Zend_Registry::get('db2');
        $codigos = explode(',', $codigo);
        $select = $db->select();
        $select->from(
            array('p' => 't_suscriptor_producto'), 
            array(
                'Producto' => 'desc_producto', 
                'Cod_Suscripcion' => 'cod_suscripcion', 
                'CodProducto' => 'cod_producto',
                'CodPaquete' => 'cod_paquete',
                'Desc_Paquete' => 'desc_paquete',
                'Id_Tarifa' => 'id_tarifa',
                'Desc_Tarifa' => 'desc_tarifa',
                'FechInicio' => 'fch_inicial_vg',
                'FecFinVigencia' => 'fch_fin_vg'
            )
        )
        ->where('p.cod_ente_suscriptor = ?', $suscriptor)
        //->where('p.fch_fin_vg >= ?', date("Y-m-d"))
        ->where('p.estado = ?', 1);
        if (!is_null($codigo) && $codigo != '')
            $select->where('p.cod_producto IN (?)', $codigos);
        return $db->fetchAll($select);
    }

    public function distritosSuscriptor($suscriptor, $codigo)
    {
        //$db = Zend_Db_Table::getDefaultAdapter();
        $db = Zend_Registry::get('db2');
        $select = $db->select();
        $select->from(
            array('p' => 't_suscriptor_direccion'), 
            array(
                'CodDistrito' => 'cod_distrientrega',
                'Distrito' => 'des_distrientrega',
                'Direccion' => 'des_direccion',
                //,'Estado'=>'estado'
                'FecRegistro' => 'fec_registro',
                'FecActualizacion' => 'fec_actualizacion'
            )
        )
        ->where('p.cod_entesuscriptor = ?', $suscriptor)
        ->where('p.estado = ?', 1);
        if (!is_null($codigo) && $codigo != '')
            $select->where('p.cod_distrientrega = ?', $codigo);
        return $db->fetchAll($select);
    }
    public function generarRUC($dni)
    {
        $factor = "5432765432";
        $ruc = "10$dni";
        $ruc = trim($ruc);

        if ((!is_numeric($dni)) || (strlen($dni) != 8)) {
            return 0;
        }

        for ($i = 0; $i < 10; $i++) {
            $arr[] = substr($ruc, $i, 1) * substr($factor, $i, 1);
        }

        $suma = 0;
        foreach ($arr as $a) {
            $suma = $suma + $a;
        }

        //Calculamos el residuo
        $residuo = $suma % 11;
        $resta = 11 - $residuo;
        $digVerifAux = substr($resta, -1);
        return $ruc . $digVerifAux;
    }
}
