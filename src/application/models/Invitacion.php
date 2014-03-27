<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Invitacion
 *
 * @author Computer
 */
class Application_Model_Invitacion extends App_Db_Table_Abstract
{

    protected $_name = 'invitacion';

    public function getInvitacionByEmailToken($email, $token)
    {
        $db = $this->getAdapter();
        $sql = $db->select()
            ->from($this->_name)
            ->where('email = ?', $email)
            ->where('token = ?', $token)
            ->limit(1);
        $rs = $db->fetchRow($sql);
        return $rs;
    }

    public static function isValidToken($token = null)
    {
        if ($token == null) {
            return false;
        }
        $inv = new Application_Model_Invitacion();
        $sql = $inv->select()
            ->from('invitacion')
            ->where('token_activacion = ?', $token);
        $sql = $sql->limit('1');
        $invitacion = $inv->getAdapter()->fetchRow($sql);
        if ($invitacion == null) {
            return false;
        }
        if (time() > strtotime($invitacion['token_expiracion'])) {
            return false;
        }
        return $invitacion;
    }

    public static function generarToken($userData, $lifetime)
    {
        $invitacion = new Application_Model_Invitacion();
        $token = sha1(uniqid(rand(), 1));
        $userData['token_activacion'] = $token;
        $userData['token_expiracion'] = $lifetime;
        $invitacion->insert($userData);
        return $token;
    }

    public static function deleteInvitacion($token)
    {
        $invitacion = new Application_Model_Invitacion();
        $where = $invitacion->getAdapter()->quoteInto('token_activacion = ?', $token);
        $invitacion->delete($where);
        return $token;
    }

}
