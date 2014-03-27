<?php

class Application_Model_Suscriptor extends App_Db_Table_Abstract
{
    protected $_name = "suscriptor";
    const ESTADO_ACTIVO = 1;
    const ESTADO_INACTIVO = 0;
    /**
     * Retorna el nombre y el slug de un suscriptor de acuerdo al ID de usuario
     * 
     * @param int $usuarioId
     */
    public function getSlugByUsuarioId($usuarioId)
    {
        $db = $this->getAdapter();
        $sql = $db->select()
            ->from($this->_name, array('nombres', 'slug'))
            ->where('usuario_id = ?', $usuarioId);
        return $db->fetchRow($sql);
    }

    public function getPerfil($id)
    {
        $datospersonales = $this->getSuscriptorPerfil($id);
        if ($datospersonales == null) {
            return false;
        }
        $perfil = array(
            'suscriptor' => $datospersonales,
            'beneficiarios' => $this->getBeneficiarios($datospersonales['id']),
            'consumos' => $this->getConsumos('id')
        );
        return $perfil;
        //}
    }

    public function getSuscriptorPerfil($id)
    {
        $db = $this->getAdapter();

        $whereField = is_numeric($id) ? 'id' : 'slug';

        $sql = $db->select()
            ->from(
                array('s' => $this->_name), array(
                'id' => 's.id',
                'usuario_id' => 's.usuario_id',
                'suscriptor_padre_id' => 's.suscriptor_padre_id',
                'nombres' => 's.nombres',
                'apellidos' => new Zend_Db_Expr("CONCAT(s.apellido_paterno, ' ', s.apellido_materno)"),
                'sexo' => 's.sexo',
                'fecha_nacimiento' => 's.fecha_nacimiento',
                'tipo_documento' => 's.tipo_documento',
                'numero_documento' => 's.numero_documento',
                'codigo_suscriptor' => 's.codigo_suscriptor',
                'telefono' => 's.telefono',
                'slug' => 's.slug',
                'hash' => 's.hash',
                'enviar_alertas_email' => 's.enviar_alertas_email',
                'activo' => 's.activo',
                'usuario_activo' => 'u.activo',
                'es_invitado' => 's.es_invitado',
                'es_suscriptor' => 's.es_suscriptor',
                'fecha_invitacion' => 's.fecha_invitacion',
                'fecha_registro' => 's.fecha_registro',
                'fecha_actualizacion' => 's.fecha_actualizacion',
                'origen' => 's.origen'
                )
            )->join(
                array('u' => 'usuario'), 'u.id = s.usuario_id ', array('usuario_id' => 'u.id',
                'email' => 'u.email')
            )
            ->where("s.$whereField = ?", $id);
        $rs = $db->fetchRow($sql);
        return $rs;
    }

    public function getSuscriptor($id, $query = false)
    {
        $db = $this->getAdapter();
        $whereField = is_numeric($id) ? 'id' : 'slug';
        $sql = $db->select()
            ->from(
                array('s' => $this->_name)
            )
            ->where("s.$whereField = ?", $id);
        $level = $db->fetchOne($sql);

        $fields = array(
            'email_contacto' => 's.email_contacto',
            'nombres' => 's.nombres',
            'apellidos' => new Zend_Db_Expr("CONCAT(s.apellido_paterno, ' ', s.apellido_materno)"),
            'apellido_paterno' => 's.apellido_paterno',
            'apellido_materno' => 's.apellido_materno',
            'fecha_nacimiento' => 's.fecha_nacimiento',
            'tipo_documento' => 's.tipo_documento',
            'numero_documento' => 's.numero_documento',
            'fecha_nacimiento' => 's.fecha_nacimiento',
            'sexoMF' => 's.sexo',
            'telefono' => 's.telefono',
            'codigo_suscriptor' => 's.codigo_suscriptor',
            'slug' => 's.slug',
            'usuario_id' => 's.usuario_id',
            'es_suscriptor' => 's.es_suscriptor',
            'activo' => 's.activo',
            'es_invitado' => 's.es_invitado',
            'usuario_id' => 's.usuario_id'
        );
        $sql = $db->select()->from(array('s' => $this->_name), $fields);

        $sql = $sql->join(
            array('u' => 'usuario'), 'u.id=s.usuario_id ',
            array('usuario_id' => 'u.id', 'email' => 'u.email')
        )
            ->where("s.$whereField = ?", $id);
        $rs = $db->fetchRow($sql);
        if ($query)
            return $sql;
        return $rs;
    }

    public static function validacionDocumento($value)
    {
        $options = func_get_args();
        $tipoDocumento = $options[2];
        $id = $options[3];
        $o = new Application_Model_Suscriptor();
        $sql = $o->select()
            ->from('suscriptor', 'id')
            ->where('tipo_documento = ?', $tipoDocumento->getValue())
            ->where('numero_documento = ?', $value)
            ->limit('1');
        if ($id) {
            $sql = $sql->where('id != ?', $id);
        }
        $sql = $sql->limit('1');

        $r = $o->getAdapter()->fetchOne($sql);
        return!(bool) $r;
    }

    public static function isSuscriptorActivo($value)
    {
        $options = func_get_args();
        $tipoDocumento = $options[2];
        $id = $options[3];
        $o = new Application_Model_Suscriptor();
        $sql = $o->select()
            ->from('suscriptor', 'id')
            ->where('tipo_documento = ?', $tipoDocumento->getValue())
            ->where('numero_documento = ?', $value)
            ->where('es_suscriptor = ?', 1)
            ->where('activo = ?', 1);
        if ($id) {
            $sql = $sql->where('id != ?', $id);
        }
        $sql = $sql->limit('1');

        $r = $o->getAdapter()->fetchOne($sql);
        return!(bool) $r;
    }

    public static function validacionCodigoSuscriptor($value)
    {
        $options = func_get_args();
        $id = $options[2];
        $o = new Application_Model_Suscriptor();
        $sql = $o->select()
            ->from('suscriptor', 'id')
            ->where('codigo_suscriptor = ?', $value)
            ->limit('1');
        if ($id) {
            $sql = $sql->where('id != ?', $id);
        }
        $sql = $sql->limit('1');

        $r = $o->getAdapter()->fetchOne($sql);
        return!(bool) $r;
    }

    public static function getBeneficiarios($id)
    {
        $obj = new Application_Model_Suscriptor();
        $sql = $obj->getAdapter()->select()
            ->from(
                array('s' => 'suscriptor'), array(
                'id' => 's.id',
                'nombres' => 's.nombres',
                'apellidos' => new Zend_Db_Expr("CONCAT(s.apellido_paterno, ' ', s.apellido_materno)"),
                'tipo_documento' => 's.tipo_documento',
                'numero_documento' => 's.numero_documento',
                'fecha_nacimiento' => 's.fecha_nacimiento',
                'fecha_invitacion' => 's.fecha_invitacion',
                'fecha_registro' => 's.fecha_registro'
                )
            )
            ->join(
                array('u' => 'usuario'), 
                's.usuario_id = u.id', 
                array('usuario_id' => 'u.id','email' => 'u.email')
            )
            ->where('suscriptor_padre_id = ?', $id)
            ->where("u.rol = 'suscriptor'");
        $rs = $obj->getAdapter()->fetchAll($sql);
        return $rs;
    }

    public function getConsumos($id)
    {
        $sql = $this->getAdapter()->select()
            ->from(
                array('c' => 'cupon'), array(
                'codigo' => 'c.codigo',
                'fecha_emision' => 'c.fecha_emision',
                'fecha_consumo' => 'c.fecha_consumo',
                'titulo' => 'b.titulo',
                'descripcion' => 'b.descripcion',
                'path_logo' => 'b.path_logo',
                'establecimiento' => 'e.nombre'
                )
            )
            ->join(array('b' => 'beneficio'), 'c.beneficio_id = b.id')
            ->join(array('e' => 'establecimiento'), 'b.establecimiento_id = e.id')
            ->where('c.suscriptor_id = ?', $id)
            ->order('c.fecha_emision');
        $rs = $this->getAdapter()->fetchAll($sql);
        return $rs;
    }

    /**
     *
     * @param type $email Email de sesion de usuario del suscriptor
     * @param type $dni DNI del suscriptor
     * @param type $idSuscriptor Id del Suscriptor
     * @param type $idUsuario Id del usuario
     * @return type bool
     */
    public static function validarInvitado(
        $email = false, $dni = false, $idSuscriptor = false, $idUsuario = false
    )
    {
        $obj = new Application_Model_Suscriptor();
        $sql = $obj->select();
        $sql->setIntegrityCheck(false);
        $sql = $sql->from(array('s' => 'suscriptor'), 'id')
            ->join(
                array('u' => 'usuario'), 
                's.usuario_id = u.id', 
                array('usuario_id' => 'u.id', 'email' => 'u.email')
            );
        if ($email)
            $sql = $sql->where('u.email = ?', $email);
        if ($dni)
            $sql = $sql->where('s.numero_documento = ?', $dni);
        if ($idSuscriptor)
            $sql = $sql->where('s.id = ?', $idSuscriptor);
        if ($idUsuario)
            $sql = $sql->where('u.id = ?', $idUsuario);
        $sql = $sql->where('s.es_invitado = 1 OR s.activo = 1');
        $sql = $sql->limit('1');
        $r = $obj->getAdapter()->fetchOne($sql);
        return!(bool) $r;
    }

    public function eliminarBeneficiario($id)
    {
        try {
            $db = $this->getAdapter();
            $db->beginTransaction();
            $where['id = ?'] = $id;
            $ok = $db->update(
                'suscriptor', 
                array('suscriptor_padre_id' => null, 'es_invitado' => 0, 'activo' => 0), 
                $where
            );
            $db->commit();
        } catch(Exception $e) {
            $db->rollBack();
            echo $e->getMessage();
            return false;
        }
        return true;
    }
    
    
    public function getSuscriptorByEmailUsuario($email)
    {
        $db = $this->getAdapter();
        $sql = $db->select()
            ->from(array('s' => $this->_name), 's.*')
            ->join(array('u' => 'usuario'), 's.usuario_id = u.id', array('u.email'))
            ->where('u.email = ?', $email)
            ->limit(1);
        $rs = $db->fetchRow($sql);
        return $rs;
    }
    
    public static function getTiposDocumentoSuscriptor()
    {
        $objTable = new Application_Model_Suscriptor();
        $cadenavalor = $objTable->getAdapter()
            ->query('SHOW COLUMNS FROM '.$objTable->_name." LIKE 'tipo_documento'")->fetchColumn(1);
        $cadenavalor = str_replace("enum('", '', $cadenavalor);
        $cadenavalor = str_replace("'", '', $cadenavalor);
        $cadenavalor = str_replace(")", '', $cadenavalor);
        $valores = explode(',', $cadenavalor); $array = array();
        foreach ($valores as $item) $array[$item] = $item;
        //print_r($valores); exit;
        return $array;
    }
    
    public static function getSuscriptorPadrePorBeneficiario($_idSuscriptor)
    {
        $objTable = new Application_Model_Suscriptor();
        $db = $objTable->getAdapter();
        $sql = $db->select()
            ->from(array('anf'=> $objTable->_name))
            ->where('anf.id = ?', $_idSuscriptor);
        return $db->fetchRow($sql);
    }
    
    public function getBuscarXDocNumSuscriptorInvitado($numDoc, $tipodoc)
    {
        $sql = $this->getAdapter()->select()
            ->from($this->_name)
            ->where('es_invitado = 1 OR es_suscriptor=1')
//            ->where('numero_documento = ?', $numDoc)
            ->limit('1');

        if ($tipodoc == 'DNI') {
            $dataWhereIn = array("RUC", "DNI");
            $sql = $sql->where('tipo_documento IN(?)', $dataWhereIn);
            $sql = $sql->where(' numero_documento = "' . $numDoc . '" or numero_documento like "__' . $numDoc . '_"  ');
        } elseif ($tipodoc == 'RUC') {
            $dataWhereIn = array("RUC", "DNI");
            $dataNumDni = substr($numDoc, 2, 8);
            $sql = $sql->where('tipo_documento IN(?)', $dataWhereIn);
            $sql = $sql->where('numero_documento = "' . $numDoc . '" or numero_documento like "' . $dataNumDni . '" ');
        } else {
            $sql = $sql->where('tipo_documento = ?', $tipodoc);
            $sql = $sql->where('numero_documento = ?', $numDoc);
        }
//        echo $sql;
        //echo $sql->assemble(); exit;
        return $this->getAdapter()->fetchRow($sql);
    }

    public function getBuscarXDni ($numDoc, $tipodoc="")
    {
        $sql = $this->getAdapter()->select()
            ->from($this->_name)
            ->where('es_invitado = 1 OR es_suscriptor=1')
            ->where('numero_documento = ?', $numDoc)
            ->limit('1');

        if ($tipodoc!="") {
            $sql = $sql->where('tipo_documento = ?', $tipodoc);
        }
        //echo $sql->assemble(); exit;
        return $this->getAdapter()->fetchRow($sql);
    }
    
    public static function getActivoXId ($id)
    {
        $o = new Application_Model_Suscriptor();
        $sql = $o->select()
            ->from($o->_name, array('activo'))
            ->where('id = ?', $id);
        $r = $o->getAdapter()->fetchRow($sql);
        return (empty($r["activo"])? 0 : $r["activo"]);
    }
    
    public function getBuscarXDniCupon ($numDoc, $tipo) 
    {
        $sql = $this->getAdapter();
        if ($tipo == 0) {
            $sql = $sql->select()
                ->from($this->_name)
                ->where('es_invitado = ?', 1)
                ->orWhere('es_suscriptor = ?', 1)
                ->where('numero_documento = ?', $numDoc)
                ->limit('1');
        } else {
            $sql = $sql->select()
                ->from(array('c'=>'cupon'), array('id_cupon' => 'id','cod_cupon'=>'codigo'))
                ->joinInner(
                    array('s'=>$this->_name),
                    's.id = c.suscriptor_id',
                    array(
                        'activo', 
                        'nombres', 
                        'apellidos' => new Zend_Db_Expr("CONCAT(apellido_paterno, ' ', apellido_materno)")
                    )
                )->where('codigo = ?', $numDoc);
        }
        return $this->getAdapter()->fetchRow($sql);
    }

    public function getSuscriptores()
    {
        $sql = $this->getAdapter();
        $sql = $sql->select()
                ->from(
                    array("s"=>$this->_name),
                    array(
                        "esinvitado"=> "s.es_invitado",
                        "id" => "s.id",
                        "fecha_actualizacion" => "s.fecha_actualizacion",
                        "numero_documento" => "s.numero_documento",
                        "tipo_documento" => "s.tipo_documento",
                        "es_suscriptor" => "s.es_suscriptor"
                    )
                );
        return $this->getAdapter()->fetchAll($sql);
    }

    public function getSuscriptoresxDoc($nd, $tipo)
    {
        $sql = $this->getAdapter();
        $sql = $sql->select()
                ->from(
                    array("s"=>$this->_name),
                    array(
                        "esinvitado"=> "s.es_invitado",
                        "id" => "s.id",
                        "fecha_actualizacion" => "s.fecha_actualizacion",
                        "numero_documento" => "s.numero_documento",
                        "tipo_documento" => "s.tipo_documento",
                        "es_suscriptor" => "s.es_suscriptor"
                    )
                )
                ->where("s.numero_documento = ?", $nd)
                ->where("s.tipo_documento = ?", $tipo);
        return $this->getAdapter()->fetchRow($sql);
    }

    public static function getSuscriptorActivoByDocumento($tipo, $numero)
    {
        $obj = new Application_Model_Suscriptor();
        $db = $obj->getAdapter();
        $sql = $db->select()
            ->from(array('s' => $obj->_name))
            ->where('activo = 1 AND (es_suscriptor = 1 OR es_invitado = 1)')
            ->where('tipo_documento = ?', $tipo)
            ->where('numero_documento = ?', $numero);
        return $db->fetchRow($sql);
    }
    
    public static function getSuscriptorById($arrayId, $valido = false)
    {
        $obj = new Application_Model_Suscriptor();
        $db = $obj->getAdapter();

        $sql = $db->select()
            ->from(
                array('s' => $obj->_name), array(
                'id' => 's.id',
                'usuario_id' => 's.usuario_id',
                'suscriptor_padre' => 's.suscriptor_padre_id',
                'nombres' => 's.nombres',
                'apellidos' => new Zend_Db_Expr("CONCAT(s.apellido_paterno, ' ', s.apellido_materno)"),
                'apellido_paterno' => 'apellido_paterno',
                'apellido_materno' => 'apellido_materno',
                'sexo' => 's.sexo',
                'fecha_nacimiento' => 's.fecha_nacimiento',
                'tipo_documento' => 's.tipo_documento',
                'numero_documento' => 's.numero_documento',
                'codigo_suscriptor' => 's.codigo_suscriptor',
                'telefono' => 's.telefono',
                'slug' => 's.slug',
                'enviar_alertas_email' => 's.enviar_alertas_email',
                'activo' => 's.activo',
                'usuario_activo' => 'u.activo',
                'es_invitado' => 's.es_invitado',
                'fecha_invitacion' => 's.fecha_invitacion',
                'fecha_registro' => 's.fecha_registro',
                'fecha_actualizacion' => 's.fecha_actualizacion',
                'es_suscriptor' => 's.es_suscriptor'
                )
            )->join(
                array('u' => 'usuario'), 'u.id = s.usuario_id ', array('usuario_id' => 'u.id',
                'email' => 'u.email')
            );
        if(!empty($arrayId["suscriptor_id"])){
            $sql->where("s.id = ?", $arrayId["suscriptor_id"]);
        } elseif(!empty($arrayId["usuario_id"])){
            $sql->where("u.id = ?", $arrayId["usuario_id"]);
        }
            
        if($valido) 
            $sql = $sql->where('s.activo = 1 AND (s.es_suscriptor = 1 OR s.es_invitado = 1)');
        //echo $sql;exit;
        $rs = $db->fetchRow($sql);
        return $rs;
    }
    
    public static function getSuscriptorByHash($hash) 
    {
        $obj = new Application_Model_Suscriptor();
        $db = $obj->getAdapter();

        $sql = $db->select()
            ->from(array('s' => $obj->_name))
            ->where('s.hash = ?', $hash)
        ->limit(1);
        $suscriptor = $db->fetchRow($sql);
        if(empty ($suscriptor))
            return false;
        $perfil = $obj->getPerfil($suscriptor['id']);
        return $perfil;
    }
    
    public static function getSuscriptorByDocumentoCron($tipo, $numero, $esSuscriptor = false)
    {
        $obj = new Application_Model_Suscriptor();
        $db = $obj->getAdapter();
        $sql = $db->select()
            ->from(array('s' => $obj->_name))
            ->where('tipo_documento = ?', $tipo)
            ->where('numero_documento = ?', $numero)
            ->join(array('u' => 'usuario'), 'u.id = s.usuario_id', array('u_id' => 'u.id'));
        if ($esSuscriptor)
            $sql = $sql->where('s.es_suscriptor = 1 OR s.es_invitado = 1');
//            $sql = $sql->where('s.activo = 1 AND (s.es_suscriptor = 1 OR s.es_invitado = 1)');
//        echo $sql->assemble();
//        exit;
        return $db->fetchRow($sql);
    }
    
    public static function getSuscriptorByDocumento($tipo, $numero, $esSuscriptor = false)
    {
        $obj = new Application_Model_Suscriptor();
        $db = $obj->getAdapter();
        $sql = $db->select()
            ->from(array('s' => $obj->_name))
//            ->where('tipo_documento = ?', $tipo)
//            ->where('numero_documento = ?', $numero)
            ->join(array('u' => 'usuario'), 'u.id = s.usuario_id', array('u_id' => 'u.id'));

        if ($tipo == 'DNI') {
            $dataWhereIn = array("RUC", "DNI");
            $sql = $sql->where('s.tipo_documento IN(?)', $dataWhereIn);
            $sql = $sql
                ->where('( numero_documento = "' . $numero . '" or numero_documento like "__' . $numero . '_"  )');
        } elseif ($tipo == 'RUC') {
            $dataWhereIn = array("RUC", "DNI");
            $dataNumDni = substr($numero, 2, 8);
            $sql = $sql->where('s.tipo_documento IN(?)', $dataWhereIn);
            $sql = $sql
                ->where('( numero_documento = "' . $numero . '" or numero_documento like "' . $dataNumDni . '"  )');
        } else {
            $sql = $sql->where('tipo_documento = ?', $tipo);
            $sql = $sql->where('numero_documento = ?', $numero);
        }

        if ($esSuscriptor)
            $sql = $sql->where('s.es_suscriptor = 1 OR s.es_invitado = 1');
//            $sql = $sql->where('s.activo = 1 AND (s.es_suscriptor = 1 OR s.es_invitado = 1)');
//        echo $sql->assemble();
//        exit;
        return $db->fetchRow($sql);
    }
        
    public static function getNumeroInvitadoSuscriptorPadre($idPadre)
    {
        $obj = new Application_Model_Suscriptor();
        $db = $obj->getAdapter();
        $sql = $db->select()
            ->from(array('s' => $obj->_name))
            ->where('s.suscriptor_padre_id = ?', $idPadre);
        return count($db->fetchAll($sql));
    }
    
    public static function getSuscriptoresByDocumento($tipo, $numero)
    {
        if(empty($tipo) || empty($numero))
            return false;
        $sql = $this->getAdapter();
        $sql = $sql->select()
                ->from(array("s" => $this->_name))
                ->where("s.tipo_documento = ?", $tipo)
                ->where("s.numero_documento = ?", $numero);
        return $this->getAdapter()->fetchRow($sql);
    }
}
