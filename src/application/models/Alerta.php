<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of Alerta
 *
 * @author Computer
 */
class Application_Model_Alerta extends App_Db_Table_Abstract
{
    protected $_name = 'alerta';

    public function getAlertasBySuscriptor($id)
    {
        $sql = $this->select()->from(
            array("a" => $this->_name), array(
            'id' => "a.id",
            'suscriptor_id' => "a.suscriptor_id",
            'categoria_id' => "a.categoria_id",
            'fecha_afiliacion' => "a.fecha_afiliacion",
            'fecha_ultima_notificacion' => "a.fecha_ultima_notificacion"
            )
        )
            ->where('a.suscriptor_id = ?', $id);
        $rs = $this->getAdapter()->fetchAll($sql);
        return $rs;
    }

    public function getAlertaBySuscriptorAndCategoria($suscripto, $categoria)
    {
        $sql = $this->select()->from(
            array("a" => $this->_name), array(
            'id' => "a.id",
            'suscriptor_id' => "a.suscriptor_id",
            'categoria_id' => "a.categoria_id",
            'fecha_afiliacion' => "a.fecha_afiliacion",
            'fecha_ultima_notificacion' => "a.fecha_ultima_notificacion"
            )
        )
            ->where('a.suscriptor_id = ?', $id)
            ->where('a.categoria_id');
        $rs = $this->getAdapter()->fetchAll($sql);
        return $rs;
    }

}