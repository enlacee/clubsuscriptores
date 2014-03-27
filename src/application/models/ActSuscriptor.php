<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of ActSuscriptor
 *
 * @author Computer
 */
class Application_Model_ActSuscriptor extends App_Db_Table_Abstract
{
    protected $_name = 't_actsuscriptor';
    protected $_nact = 0;

    public function getSuscriptorByCodigo($value)
    {
        $db = $this->getAdapter();

        $sql = $db->select()
            ->from(
                't_actsuscriptor', array(
                'id' => 'id',
                'cod_entesuscriptor' => 'cod_entesuscriptor',
                'des_apepaterno' => 'des_apepaterno',
                'des_apematerno' => 'des_apematerno',
                'des_nombres' => 'des_nombres',
                'des_razonsocial' => 'des_razonsocial',
                'fch_nacimiento' => 'fch_nacimiento',
                'cod_tipdocid' => 'cod_tipdocid',
                'des_numdocid' => 'des_numdocid',
                'tip_sexoente' => 'tip_sexoente',
                'tip_estadocivil' => 'tip_estadocivil',
                'des_instruccion' => 'des_instruccion',
                'des_email' => 'des_email',
                'des_numtelf01' => 'des_numtelf01',
                'des_numtelf02' => 'des_numtelf02',
                'des_numtelf03' => 'des_numtelf03',
                'des_numfax' => 'des_numfax',
                'des_profesion' => 'des_profesion',
                'des_ocupacion' => 'des_ocupacion',
                'des_empresa' => 'des_empresa',
                'des_cargo' => 'des_cargo',
                'des_club' => 'des_club',
                'des_direccion_benef' => 'des_direccion_benef',
                'est_actualizacion' => 'est_actualizacion',
                'fch_actualizacion' => 'fch_actualizacion',
                'fch_actualizacion_dt' => 'fch_actualizacion_dt',
                'num_familia' => 'num_familia',
                'cod_entesuscriptor_padre' => 'cod_entesuscriptor_padre',
                'des_interes' => 'des_interes',
                'relacion' => 'relacion',
                'referido' => 'referido',
                'cod_suscripcion' => 'cod_suscripcion',
                'id_beneficiario' => 'id_beneficiario',
                'estado_beneficiario' => 'estado_beneficiario',
                'estado_reparto' => 'estado_reparto',
                'tipo_actualizacion' => 'tipo_actualizacion',
                'est_sus' => 'est_sus'
                )
            )
            ->where("cod_entesuscriptor = ?", $value);

        $rs = $db->fetchRow($sql);
        return $rs;
    }
    
    public function getSuscriptorByDocumento($tdoc, $ndoc)
    {
        $db = $this->getAdapter();
        $sql = $db->select()
            ->from(
                't_actsuscriptor', 
                array(
                    'id' => 'id',
                    'cod_entesuscriptor' => 'cod_entesuscriptor',
                    'des_apepaterno' => 'des_apepaterno',
                    'des_apematerno' => 'des_apematerno',
                    'des_nombres' => 'des_nombres',
                    'des_razonsocial' => 'des_razonsocial',
                    'fch_nacimiento' => 'fch_nacimiento',
                    'cod_tipdocid' => 'cod_tipdocid',
                    'des_numdocid' => 'des_numdocid',
                    'tip_sexoente' => 'tip_sexoente',
                    'tip_estadocivil' => 'tip_estadocivil',
                    'des_instruccion' => 'des_instruccion',
                    'des_email' => 'des_email',
                    'des_numtelf01' => 'des_numtelf01',
                    'des_numtelf02' => 'des_numtelf02',
                    'des_numtelf03' => 'des_numtelf03',
                    'des_numfax' => 'des_numfax',
                    'des_profesion' => 'des_profesion',
                    'des_ocupacion' => 'des_ocupacion',
                    'des_empresa' => 'des_empresa',
                    'des_cargo' => 'des_cargo',
                    'des_club' => 'des_club',
                    'des_direccion_benef' => 'des_direccion_benef',
                    'est_actualizacion' => 'est_actualizacion',
                    'fch_actualizacion' => 'fch_actualizacion',
                    'fch_actualizacion_dt' => 'fch_actualizacion_dt',
                    'num_familia' => 'num_familia',
                    'cod_entesuscriptor_padre' => 'cod_entesuscriptor_padre',
                    'des_interes' => 'des_interes',
                    'relacion' => 'relacion',
                    'referido' => 'referido',
                    'cod_suscripcion' => 'cod_suscripcion',
                    'id_beneficiario' => 'id_beneficiario',
                    'estado_beneficiario' => 'estado_beneficiario',
                    'estado_reparto' => 'estado_reparto',
                    'tipo_actualizacion' => 'tipo_actualizacion',
                    'est_sus' => 'est_sus'
                )
            )
            ->where("LOWER(cod_tipdocid) = ?", $tdoc)
            ->where("des_numdocid = ?", $ndoc);
        $rs = $db->fetchRow($sql);
        return $rs;
    }

    public static function validacionSuscriptorByCodigo($value, $activo = false)
    {
        $options = func_get_args();
        $obj = new Application_Model_ActSuscriptor();
        $db = $obj->getAdapter();
        $sql = $db->select()
            ->from($obj->_name, 'id')
            ->where('cod_entesuscriptor = ?', $value);
        if ($activo) {
            $sql = $sql->where('est_sus = ?', 'AC')
                ->where('estado_reparto = ?', 'AC');
        }
        $sql = $sql->limit('1');
        $r = $db->fetchOne($sql);
        return (bool) $r;
    }

    public static function validacionSuscriptorByDocumento($tipodoc, $numDoc, $activo = false)
    {
        $options = func_get_args();
        $obj = new Application_Model_ActSuscriptor();
        $db = $obj->getAdapter();
        $sql = $db->select()
            ->from($obj->_name, 'id')
//            ->where('LOWER(cod_tipdocid) = ?', $tipo)
//            ->where('des_numdocid = ?', $numero);
            ->limit('1');
        if ($activo) {
            $sql = $sql->where('est_sus = ?', 'AC')
                ->where('estado_reparto = ?', 'AC');
        }

        if ($tipodoc == 'DNI') {
            $dataWhereIn = array("RUC", "DNI");
            $sql = $sql->where('LOWER(cod_tipdocid) IN(?)', $dataWhereIn);
            $sql = $sql->where(' des_numdocid = "' . $numDoc . '" or des_numdocid like "__' . $numDoc . '_"  ');
        } elseif ($tipodoc == 'RUC') {
            $dataWhereIn = array("RUC", "DNI");
            $dataNumDni = substr($numDoc, 2, 8);
            $sql = $sql->where('LOWER(cod_tipdocid) IN(?)', $dataWhereIn);
            $sql = $sql->where('des_numdocid = "' . $numDoc . '" or des_numdocid like "' . $dataNumDni . '" ');
        } else {
            $sql = $sql->where('LOWER(cod_tipdocid) = ?', $tipodoc);
            $sql = $sql->where('des_numdocid = ?', $numDoc);
        }

//        echo $sql;exit;
//        $sql = $sql->limit('1');
        $r = $db->fetchOne($sql);
        return (bool) $r;
    }


    public static function getLastRecordSuscriptorPorDocumento($tipo, $numero)
    {
        $obj = new Application_Model_ActSuscriptor();
        $db = $obj->getAdapter();
        $sql = $db->select()
            ->from('t_actsuscriptor')
            ->where("des_numdocid = ?", $numero)
            ->where("cod_tipdocid = ?", $tipo)
            ->order('id DESC')
            ->limit(1);

        $rs = $db->fetchRow($sql);
        return $rs;
    }
// @codingStandardsIgnoreStart
    public function seleccionarCandidatosaActualizar($condicion="", $x, $y)
    {
        $db = $this->getAdapter();
        $sql = $db->select()
            ->from(
                't_actsuscriptor', array(
                'id' => 'id',
                'cod_entesuscriptor' => 'cod_entesuscriptor',
                'des_apepaterno' => 'des_apepaterno',
                'des_apematerno' => 'des_apematerno',
                'des_nombres' => 'des_nombres',
                'des_razonsocial' => 'des_razonsocial',
                'fch_nacimiento' => 'fch_nacimiento',
                'fch_registro' => 'fch_registro',
                'cod_tipdocid' => 'cod_tipdocid',
                'des_numdocid' => 'des_numdocid',
                'tip_sexoente' => 'tip_sexoente',
                'tip_estadocivil' => 'tip_estadocivil',
                'des_instruccion' => 'des_instruccion',
                'des_email' => 'des_email',
                'des_numtelf01' => 'des_numtelf01',
                'des_numtelf02' => 'des_numtelf02',
                'des_numtelf03' => 'des_numtelf03',
                'des_numfax' => 'des_numfax',
                'des_profesion' => 'des_profesion',
                'des_ocupacion' => 'des_ocupacion',
                'des_empresa' => 'des_empresa',
                'des_cargo' => 'des_cargo',
                'des_club' => 'des_club',
                'des_direccion_benef' => 'des_direccion_benef',
                'est_actualizacion' => 'est_actualizacion',
                'fecha_actualizacion' =>
                    new Zend_Db_Expr("date_format(fch_actualizacion,'%Y-%m-%d')"),
                'fch_actualizacion_dt' => 'fch_actualizacion_dt',
                'num_familia' => 'num_familia',
                'cod_entesuscriptor_padre' => 'cod_entesuscriptor_padre',
                'des_interes' => 'des_interes',
                'relacion' => 'relacion',
                'referido' => 'referido',
                'cod_suscripcion' => 'cod_suscripcion',
                'id_beneficiario' => 'id_beneficiario',
                'estado_beneficiario' => 'estado_beneficiario',
                'estado_reparto' => 'estado_reparto',
                'tipo_actualizacion' => 'tipo_actualizacion',
                'est_sus' => 'est_sus'
                )
            )
            ->where(
                "indica_mig = 0"
            )
            ->where("cod_tipdocid IN ('DNI','CEX', 'PAS', 'RUC')")
            ->order('id ASC');
            /*->group("des_numdocid")
            ->group("cod_tipdocid");*/

        if ($condicion!="") {
            $sql = $sql->where("estado_beneficiario=1");
        } else {
            $sql = $sql->where("estado_beneficiario=0 OR estado_beneficiario IS NULL");
        }
        
        $sql = $sql->limit($x);
        //echo $sql->assemble(); exit;
        $rs = $db->fetchAll($sql);
        return $rs;
    }
    
    public static function getSuscriptoresActualizar($limit = 1000)
    {
        $obj = new Application_Model_ActSuscriptor();
        $db = $obj->getAdapter();
        $sql = $db->select()
            ->from('t_actsuscriptor')
            ->where("indica_mig = 0")
            ->where("estado_beneficiario != 1")
            ->where("cod_tipdocid IN ('DNI','CEX', 'PAS', 'RUC')")
            ->order('id ASC');
        $sql = $sql->limit($limit);
        $rs = $db->fetchAll($sql);
        return $rs;
    }
    
    public static function getBeneficiariosActualizar($limit = 1000)
    {
        $obj = new Application_Model_ActSuscriptor();
        $db = $obj->getAdapter();
        $sql = $db->select()
            ->from('t_actsuscriptor')
            ->where("indica_mig = 0")
            ->where("cod_tipdocid IN ('DNI','CEX', 'PAS', 'RUC')")
            ->where("estado_beneficiario = 1")
            ->order('id ASC');
        $sql = $sql->limit($limit);
        $rs = $db->fetchAll($sql);
        return $rs;
    }
// @codingStandardsIgnoreEnd
    
}
