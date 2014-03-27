<?php

/**
 * Description of delta_0060
 *
 * @author FCJ
 */
class Delta_0060 extends App_Migration_Delta
{
    protected $_autor = 'Favio Condori';
    protected $_desc = 'Actualizando campos de apellidos paterno en la tabla administrador desde el campo apellidos';

    public function up()
    {
        try {
            $obj = new Application_Model_Administrador();
            $admins = $obj->fetchAll();

            $db = $obj->getAdapter();
            $db->beginTransaction();
            foreach ($admins as $admin) {
                $where = $db->quoteInto('id = ?', $admin['id']);
                $obj->update(array('apellido_paterno' => $admin['apellidos']), $where);
            }
            $db->commit();
        } catch (Exception $exc) {
            $db->rollBack();
            return false;
        }
        return true;
    }

}