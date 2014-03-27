<?php

class Application_Model_Usuario extends App_Db_Table_Abstract
{
    protected $_name = "usuario";

    public function getRolByEmail($email)
    {
        $db = $this->getAdapter();
        $sql = $db->select()
            ->from($this->_name, array('id', 'rol'))
            ->where('email = ?', $email);
        return $db->fetchRow($sql);
    }

    public function getUsuarioMail($id)
    {
        $db = $this->getAdapter();
        $sql = $db->select()
            ->from($this->_name, 'email')
            ->where('id = ?', $id);
        return $db->fetchOne($sql);
    }

    public function getUsuarioId($id)
    {
        $db = $this->getAdapter();
        $sql = $db->select()
            ->from($this->_name, '*')
            ->where('id = ?', $id);
        $rs = $db->fetchAll($sql, array(), Zend_Db::FETCH_OBJ); // Row($sql);
        return $rs[0];
    }

    public static function validacionEmail($value)
    {
        $options = func_get_args();
        $idUsuario = $options[2];
        $o = new Application_Model_Usuario();
        $sql = $o->select()
            ->from('usuario', 'id')
            ->where('email = ?', $value);

        if ($idUsuario) {
            $sql = $sql->where('id != ?', $idUsuario);
        }
        $sql = $sql->limit('1');
        $r = $o->getAdapter()->fetchOne($sql);
        return!(bool) $r;
    }
    
    public function validacionNDocSuscriptor($tipodoc, $numDoc)
    {
        $options = func_get_args();
        $idUsuario = $options[2];
        $db = $this->getAdapter();
        $sql = $db->select()
            ->from('suscriptor', 'id')
//            ->where('tipo_documento = ?', $tipodoc)
//            ->where('numero_documento = ?', $value);
            ->limit('1');
        if ($idUsuario) {
            $sql = $sql->where('id != ?', $idUsuario);
        }

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

        $r = $db->fetchOne($sql);
        return !(bool) $r;
    }
    
    public function validacionNDoc($tipo, $value)
    {
        $options = func_get_args();
        $idUsuario = $options[2];
        $db = $this->getAdapter();
        $sql = $db->select()
            ->from('suscriptor', 'id')
            ->where('tipo_documento = ?', $tipo)
            ->where('numero_documento = ?', $value);
        if ($idUsuario) {
            $sql = $sql->where('id != ?', $idUsuario);
        }
        $sql = $sql->limit('1');
        $r = $db->fetchOne($sql);
        return!(bool) $r;
    }

    public function validacionCodigoSuscriptor($value)
    {
        $options = func_get_args();
        $idUsuario = $options[2];
        $db = $this->getAdapter();
        $sql = $db->select()
            ->from('suscriptor', 'id')
            ->where('codigo_suscriptor = ?', $value);
        if ($idUsuario) {
            $sql = $sql->where('id != ?', $idUsuario);
        }
//        var_dump($sql->assemble());
        //exit();
        $sql = $sql->limit('1');
        $r = $db->fetchOne($sql);
        return!(bool) $r;
    }

    public static function validacionPswd($value)
    {
        $options = func_get_args();
        $login = $options[2];
        $rol = empty($options[3])?'':$options[3];
        $rawPassword = $value;
        $encPassword = self::valuePswd($login, $rol);
        
        $valor = App_Auth_Adapter_ClubDbTable::checkPassword($rawPassword, $encPassword);
        return $valor;
    }

    public static function valuePswd($email, $rol='')
    {
        $u = new Application_Model_Usuario();
        $sql = $u->select()
            ->from('usuario', array('pswd' => 'pswd'))
            ->where('email = ?', $email)
            ->limit('1');
        if (!empty($rol)) {
            $sql->where('rol = ?', $rol);
        }
        return $u->getAdapter()->fetchOne($sql);
    }

    public static function auth($login, $pswd, $type, $writeStorage = true)
    {
        $adapter = Zend_Db_Table::getDefaultAdapter();
        $authAdapter = new App_Auth_Adapter_ClubDbTable($adapter);
        $authAdapter->setIdentity($login);
        $authAdapter->setCredential($pswd);
        $authAdapter->setRol($type);
        
        $auth = Zend_Auth::getInstance();
        $auth->setStorage(new Zend_Auth_Storage_Session());
        $authResult = $auth->authenticate($authAdapter);
        $isValid = $authResult->isValid();
        if ($isValid && $writeStorage) {
            $datalogeo = array(); $options = array();
            if ($type == Application_Form_Login::ROL_SUSCRIPTOR ||
                $type == Application_Form_Login::ROL_ADMIN ||
                $type == Application_Form_Login::ROL_ESTABLECIMIENTO ||
                $type == Application_Form_Login::ROL_GESTOR) {

                if ($type == Application_Form_Login::ROL_ADMIN) {
                    $class = "Application_Model_" . ucfirst($type);
                    $model = new $class();
                    $usuario = $authAdapter->getResultRowObject(null, 'pswd');
                    $related = $model->fetchRow('usuario_id = ' . $usuario->id)->toArray();
                    
                    // @codingStandardsIgnoreStart
                    $objop = new Application_Model_OpcionPerfil();
                    $objop->setPerfil_id($usuario->perfil_id);
                    $options = $objop->getOpcionesByPerfil();
                    // @codingStandardsIgnoreEnd
                } elseif ( $type == Application_Form_Login::ROL_ESTABLECIMIENTO ) {
                    $class = "Application_Model_" . ucfirst($type);
                    $model = new $class();
                    $usuario = $authAdapter->getResultRowObject(null, 'pswd');
                    if ($usuario->perfil_id == 7) {
                        $related = array('perfil_id'=>7);
                    } else {
                        $related = $model->getEstablecimientoPorUsuario($usuario->id);
                    }
                    
                    $objAdministrador = new Application_Model_Administrador();
                    $dataS = $objAdministrador
                        ->fetchRow('usuario_id = ' . $usuario->id);
                    
                    if(!empty($dataS)):
                        $datalogeo['datos'] = $dataS->toArray();
                    endif;
                    // @codingStandardsIgnoreStart
                    $objop = new Application_Model_OpcionPerfil();
                    $objop->setPerfil_id($usuario->perfil_id);
                    $options = $objop->getOpcionesByPerfil();
                    // @codingStandardsIgnoreEnd
                    //var_dump($options); exit;
                } else {
                    $class = "Application_Model_" . ucfirst($type);
                    $model = new $class();
//                    $model = new Application_Model_Administrador();
                    $usuario = $authAdapter->getResultRowObject(null, 'pswd');
                    $related = $model->fetchRow('usuario_id = ' . $usuario->id)->toArray();
                    
                    // @codingStandardsIgnoreStart
                    if ($type == Application_Form_Login::ROL_GESTOR) {
                        $objop = new Application_Model_OpcionPerfil();
                        $objop->setPerfil_id($usuario->perfil_id);
                        $options = $objop->getOpcionesByPerfil();
                    }
                    // @codingStandardsIgnoreEnd
                }
            }
            //var_dump($usuario, $options); exit;
            $datalogeo['usuario'] = $usuario;
            $datalogeo[$type] = $related;
            $datalogeo['opciones'] = $options;
            
            $authStorage = $auth->getStorage();
            $authStorage->write(
                $datalogeo
            );
        }
        
        return $isValid;
    }

    /**
     * Funcion que agrega un token a la cuenta de usuario, para que pueda
     * recuperar su clave
     * 
     * @param string $emailUser
     * @param int $lifetime
     * @return string
     */
    public static function generarToken($emailUser, $rol, $lifetime)
    {
        $u = new Application_Model_Usuario();
        $sql = $u->select()
            ->from('usuario', 'id')
            ->where('email = ?', $emailUser)
            ->where('rol = ?', $rol);
        $sql = $sql->limit('1');
        $userId = $u->getAdapter()->fetchOne($sql);
        $token = sha1(uniqid(rand(), 1));
        if ($userId == null) {
            return false;
        }
        $u->update(
            array(
            'token_activacion' => $token,
            'token_expiracion' => date('Y-m-d H:i:s', time() + $lifetime)
            ), $u->getAdapter()->quoteInto('id = ?', $userId)
        );
        return $token;
    }

    /**
     * Valida el token de un usuario
     * 
     * @param string $token
     */
    public static function isValidToken($token = null)
    {
        if ($token == null) {
            return false;
        }
        $u = new Application_Model_Usuario();
        $sql = $u->select()
            ->from('usuario')
            ->where('token_activacion = ?', $token);
        $sql = $sql->limit('1');
        $user = $u->getAdapter()->fetchRow($sql);
        if ($user == null) {
            return false;
        }
        if (time() > strtotime($user['token_expiracion'])) {
            return false;
        }
        return $user;
    }

    /**
     * Ingresa un nuevo password al usuario
     * 
     * @param int $userId
     * @param string $newPswd
     * @return bool
     */
    public static function setNewPassword($userId, $newPswd)
    {
        $u = new Application_Model_Usuario();
        $newPswd = App_Auth_Adapter_ClubDbTable::generatePassword($newPswd);
        $u->update(
            array(
                'pswd' => $newPswd,
                'token_expiracion' => date('Y-m-d H:i:s')
            ), 
            $u->getAdapter()->quoteInto('id = ?', $userId)
        );
        return true;
    }

    public function getIdByEmailRol($email, $rol)
    {
        $db = $this->getAdapter();
        $sql = $db->select()
            ->from($this->_name, array('id', 'rol'))
            ->where('email = ?', $email)
            ->where('rol = ?', $rol);
        //echo $sql; exit;
        return $db->fetchRow($sql);
    }
    public function getListaUsuarios(
            $query="", $idEstablecimiento="", $estado="", $tipo="", $ord="", $col=""
    )
    {
        $col = $col==""?"id":$col;
        $ord = $ord==""?"DESC":$ord;
        $opcSql="";
        
        $db = $this->getAdapter();
        $sqlA = $db->select()
            ->from(
                array("u"=>$this->_name),
                array(
                    'id'                    => 'u.id',
                    'rol'                   => 'u.rol',
                    'email'                 => 'u.email',
                    'nombres'               => 'a.nombres',
                    'apellidos' => 
                    new Zend_Db_Expr(
                        "CONCAT(COALESCE(a.apellido_paterno,''), ' ',".
                        " COALESCE(a.apellido_materno,''))"
                    ),
                    'nombreEstablecimiento' => 'e.nombre',
                    'estado'                => 'u.activo'
                )
            )
            ->join(
                array("a"=>"administrador"),
                "a.usuario_id=u.id",
                array()
            )
            ->joinLeft(
                array("eu"=>"establecimiento_usuario"),
                "eu.usuario_id=u.id",
                array()
            )
            ->joinLeft(
                array("e"=>"establecimiento"),
                "e.id=eu.establecimiento_id",
                array()
            ) //change
            ->joinLeft(
                array("p"=>"perfil"),
                "p.id=u.perfil_id",
                array('nombre_perfil'=>'nombre')
            )
            //->order($col." ".$ord)
            ->where('u.rol NOT IN ("suscriptor")');

        if ($query!="") {
            $where = $this->getAdapter()->quoteInto(
                "a.nombres like ? OR a.apellido_paterno like ? OR a.apellido_materno like ?", "%".$query."%"
            );
            $sqlA = $sqlA->where($where);
        }
        if ($tipo!="" && $tipo!="0") {
            //$sqlA = $sqlA->where("u.rol=?", $tipo); //change
            $sqlA = $sqlA->where("u.perfil_id in ($tipo)");
            $opcSql = ($tipo=="S")?'B':'A';
        }
        if ($idEstablecimiento!="") {
            $where = $this->getAdapter()->quoteInto("e.id=?", $idEstablecimiento);
            $sqlA = $sqlA->where($where);
            $opcSql="A";
        }
        if ($estado!="") {
            $where = $this->getAdapter()->quoteInto("u.activo=?", $estado);
            $sqlA = $sqlA->where($where);
        }
        
        //***** Para poder consultar a los suscriptores
        
        $sqlB = $db->select()
            ->from(
                array("u"=>$this->_name),
                array(
                    'id'                    => 'u.id',
                    'rol'                   => 'u.rol',
                    'email'                 => 'u.email',
                    'nombres'               => 's.nombres',
                    'apellidos' => 
                    new Zend_Db_Expr(
                        "CONCAT(COALESCE(s.apellido_paterno,''), ' ',".
                        " COALESCE(s.apellido_materno,''))"
                    ),
                    'nombreEstablecimiento' => new Zend_Db_Expr("' '"),
                    'estado'                => 'u.activo',
                    'nombre_perfil'         => new Zend_Db_Expr("'Suscriptor'"),
                )
            )
            ->join(
                array("s"=>"suscriptor"),
                "s.usuario_id=u.id",
                array()
            );
        if ($query!="") {
            $where = $this->getAdapter()->quoteInto(
                "s.nombres like ? OR s.apellidos like ? OR s.apellido_paterno like ? OR s.apellido_materno like ?", 
                 "%".$query."%"
            );
            $sqlB = $sqlB->where($where);
        }
        if ($estado!="") {
            $where = $this->getAdapter()->quoteInto("u.activo=?", $estado);
            $sqlB = $sqlB->where($where);
        }        
        //echo $sqlA->assemble();exit
        //echo $sqlB->assemble();exit;
        if ($opcSql=="A") {
            $sql=$sqlA;
        } elseif ($opcSql=="B") {
            $sql=$sqlB;
        } else {
            $sql = $db->select()
            ->union(array($sqlA, $sqlB))
            ->order($col." ".$ord);
        }
        //echo $sql->assemble();exit;
        return $sql;
    }

    public function paginarListaUsuarios($query = "", $idEstablecimiento = "", $estado = "", $tipo = "", $ord = "",
                                         $col = "")
    {
        $paginado = $this->_config->admin->usuarios->nropaginas;
        $p = Zend_Paginator::factory(
            $this->getListaUsuarios($query, $idEstablecimiento, $estado, $tipo, $ord, $col)
        );
        return $p->setItemCountPerPage($paginado);
    }

    public static function validarUsuario($email, $rol, $idUsu = null) 
    {
        $options = func_get_args();
        if (isset($options[3])) {
            $rol = $options[3];
        } else {
            $rol = $options[1];
        }
        
        $obj = new Application_Model_Usuario();
        $db = $obj->getAdapter();
        $sql = $db->select()
            ->from(array('u' => $obj->_name), array('id'))
            ->where('email = ?', $email)
            ->where('rol = ?', $rol);
        if (!is_null($idUsu)) {
            $sql = $sql->where('u.id != ?', $idUsu);
        }
        $rs = $db->fetchRow($sql);
        return !(bool)$rs;
    }
    
    public static function validUser($email, $idperfil, $idUsu = null) 
    {        
        $obj = new Application_Model_Usuario();
        $db = $obj->getAdapter();
        $sql = $db->select()
            ->from(array('u' => $obj->_name), array('id'))
            ->where('email = ?', $email);
        if ($idperfil=='null') {
            $sql->where('perfil_id is NULL');
        } else {
            $sql->where('perfil_id = ?', $idperfil);
        }
        if (!is_null($idUsu)) {
            $sql = $sql->where('u.id != ?', $idUsu);
        }
        //echo $sql;exit; 
        $rs = $db->fetchRow($sql);
        return !(bool)$rs;
    }
    
    public static function getPerfilUsuario($id)
    {
        $obj = new Application_Model_Usuario();
        $db = $obj->getAdapter();
        $sql = $db->select()
            ->from(
                array('u' => $obj->_name), 
                array(
                    'id' => 'u.id', 
                    'email' => 'u.email', 
                    'activo' => 'u.activo',
                    //'rol' => 'u.rol' //change
                    'rol' => 'u.perfil_id'
                )
            )
            ->join(
                array('a' => 'administrador'), 
                'u.id = a.usuario_id', 
                array(
                    'a_id' => 'a.id', 
                    'nombres' => 'a.nombres',
                    'apellidos' => new Zend_Db_Expr("CONCAT(a.apellido_paterno, ' ', a.apellido_materno)"),
                    'apellido_paterno' => 'a.apellido_paterno',
                    'apellido_materno' => 'a.apellido_materno',
                    'tipo_documento' => 'a.tipo_documento',
                    'numero_documento' => 'a.numero_documento',
                    'fecha_nacimiento' => 'a.fecha_nacimiento'
                )
            )
            ->joinleft(
                array('eu' => 'establecimiento_usuario'), 
                'u.id = eu.usuario_id',
                array('eu_id' => 'eu.id')
            )
            ->joinleft(
                array('p' => 'perfil'), 
                'u.perfil_id = p.id',
                array('perfil_id' => 'p.id','padre_perfil_id' => 'p.padre_perfil_id','nivel' => 'p.nivel')
            )
            ->joinleft(
                array('e' => 'establecimiento'), 
                'e.id = eu.establecimiento_id', 
                array('establecimiento' => 'e.id')
            )
            ->where('u.id = ?', $id)
            ->limit(1);
        $rs = $db->fetchRow($sql);
        return $rs;
    }
    
    public function validExistsUser($email, $idperfil) 
    {
        $db = $this->getAdapter();
        $sql = $db->select()
            ->from(array('u' => $this->_name), array('id'))
            ->where('email = ?', $email);
        
        if (empty($idperfil)) {
            $sql->where("perfil_id is NULL OR perfil_id = ''");
        } else {
            $sql->where('perfil_id = ?', $idperfil);
        }
        $rs = $db->fetchRow($sql);
        return $rs;
    }
}