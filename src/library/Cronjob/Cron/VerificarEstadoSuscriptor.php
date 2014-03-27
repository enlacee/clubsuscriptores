<?php

class Cronjob_Cron_VerificarEstadoSuscriptor extends Cronjob_Base
{
    public $descripcion = "VERIFICA ESTADO SUSCRIPTOR";
    protected $_objActSuscriptor;
    protected $_objSuscriptor;
    protected $_objUsuario;
    protected $_hashHelper;

    public function __construct($args = null)
    {
        $this->_hashHelper = new App_Controller_Action_Helper_Suscriptor();
        $this->_objActSuscriptor = new Application_Model_ActSuscriptor();
        $this->_objSuscriptor = new Application_Model_Suscriptor();
        $this->_objUsuario = new Application_Model_Usuario();
        parent::__construct($args);
    }

    public function run()
    {

        $db = $this->_objActSuscriptor->getAdapter();

        $sql = $db->select()
            ->from('t_actsuscriptor', array('cant' => new Zend_Db_Expr(('COUNT(*)'))))
            ->where('indica_mig = 0')
            ->where("cod_tipdocid IN ('DNI','CEX', 'PAS', 'RUC')");
        $total = $db->fetchOne($sql);
        $i = 1;

        $p = new App_Util_Console_ProgressBar(
            'Registros Procesados %fraction% [%bar%] %percent% ET: %elapsed% ETA: %estimate%', '=>', '-', 120, $total
        );

//        echo 'Procesando Suscriptores' . PHP_EOL;
        // PASO 1: SUSCRIPTORES
        $n = 1000;
        $susActualizados = 0;
        $susInsertados = 0;

        while (count($suscriptores = Application_Model_ActSuscriptor::getSuscriptoresActualizar($n)) > 0) {
            foreach ($suscriptores as $suscriptor) {
                $suscriptorClub = Application_Model_Suscriptor
                    ::getSuscriptorByDocumentoCron($suscriptor['cod_tipdocid'], $suscriptor['des_numdocid']);
                if (empty($suscriptorClub)) {
                    $this->insertarSuscriptor($suscriptor);
                    $susInsertados++;
                } else {
                    $this->actualizarSuscriptor($suscriptor, $suscriptorClub);
                    $susActualizados++;
                }
                $p->update($i++);
            }
        }

        // PASO 1: BENEFICIARIOS
        $n = 1000;
        $benActualizados = 0;
        $benInsertados = 0;

        while (count($beneficiarios = Application_Model_ActSuscriptor::getBeneficiariosActualizar($n)) > 0) {
            foreach ($beneficiarios as $beneficiario) {
                $beneficiarioClub = Application_Model_Suscriptor
                    ::getSuscriptorByDocumentoCron($beneficiario['cod_tipdocid'], $beneficiario['des_numdocid']);
                if (empty($beneficiarioClub)) {
                    $this->insertarSuscriptor($beneficiario);
                    $benInsertados++;
                } else {
                    $this->actualizarSuscriptor($beneficiario, $beneficiarioClub);
                    $benActualizados++;
                }
                $p->update($i++);
            }
        }
        
        echo PHP_EOL;
        echo 'Suscriptores Nuevos: ' .$susInsertados . PHP_EOL;
        echo 'Suscriptores Actualizados: ' .$susActualizados . PHP_EOL;
        echo 'Beneficiarios Nuevos: ' .$benInsertados . PHP_EOL;
        echo 'Beneficiarios Actualizadios: ' .$benActualizados . PHP_EOL;
        parent::run();
    }

    private function _crearSlug($suscriptor, $lastId)
    {
        $slugFilter = new App_Filter_Slug(array('field' => 'slug', 'model' => 'suscriptor'));

        $slug = $slugFilter->filter(
            $suscriptor['nombres'] . ' ' .
            $suscriptor['apellido_paterno'] . ' ' .$suscriptor['apellido_materno'] . ' ' .
            substr(md5($lastId), 0, 8)
        );
        return $slug;
    }

    private function _getSuscriptorByCodigo($codigo)
    {
        $db = $this->_objSuscriptor->getAdapter();
        $sql = $db->select()->from('suscriptor')->where('codigo_suscriptor = ?', $codigo)->limit(1);
        return $db->fetchRow($sql);
    }

    /* INSERTAR SUSCRIPTOR */
    private function insertarSuscriptor($suscriptorMigrado, $esBeneficiario = false)
    {
        $nuevosDatos = array();
        try {
            $db = $this->_objSuscriptor->getAdapter();

            $db->beginTransaction();
            //insert usuario
            $usuario["rol"] = "suscriptor";
            $usuario["activo"] = "0";
            $usuario["fecha_registro"] = date("Y-m-d H:i:s");
            $usuario["fecha_actualizacion"] = date("Y-m-d H:i:s");
            $idUsuario = $this->_objUsuario->insert($usuario);
            

            $nuevosDatos['usuario_id'] = $idUsuario;
            $nuevosDatos['nombres'] = $suscriptorMigrado['des_nombres'];
            $nuevosDatos['apellido_paterno'] = $suscriptorMigrado['des_apepaterno'];
            $nuevosDatos['apellido_materno'] = $suscriptorMigrado['des_apematerno'];
            $nuevosDatos['tipo_documento'] = $suscriptorMigrado['cod_tipdocid'];
            $nuevosDatos['numero_documento'] = $suscriptorMigrado['des_numdocid'];
            $nuevosDatos['sexo'] = $suscriptorMigrado['tip_sexoente'] == 'F' ? 'F' : 'M';
            $nuevosDatos['codigo_suscriptor'] = $suscriptorMigrado['cod_entesuscriptor'];
            $nuevosDatos['telefono'] = $suscriptorMigrado['des_numtelf01'];
            $nuevosDatos['activo'] = $suscriptorMigrado['estado_reparto'] == 'AC' ? 1 : 0;
            $nuevosDatos['es_invitado'] = $suscriptorMigrado['estado_beneficiario'] == 1 ? 1 : 0;
            $nuevosDatos['fecha_invitacion'] = $suscriptorMigrado['fch_registro'];
            $nuevosDatos['fecha_actualizacion'] = date('Y-m-d H:i:s');
            $nuevosDatos['es_suscriptor'] = $suscriptorMigrado['est_sus'] == 'AC' ? 1 : 0;
            $nuevosDatos['email_contacto'] = $suscriptorMigrado['des_email'];
            $nuevosDatos["fecha_nacimiento"] = null;
            $nuevosDatos["origen"] = 'cron';
            if (!empty($suscriptorMigrado['fch_nacimiento']) && $suscriptorMigrado['fch_nacimiento'] != 'n') {
                $f = explode("/", $suscriptorMigrado["fch_nacimiento"]);
                if (count($f) == 3) {
                    $nuevosDatos["fecha_nacimiento"] = $f[2] . "-" . $f[1] . "-" . $f[0];
                } else {
                    $nuevosDatos["fecha_nacimiento"] = $suscriptorMigrado["fch_nacimiento"];
                }
            }

            if ($esBeneficiario && $suscriptorMigrado['estado_beneficiario'] == 1) {
                $padre = $this->_getSuscriptorByCodigo($suscriptorMigrado['cod_entesuscriptor_padre']);
                if (empty($padre)) {
                    throw new Exception(
                        'suscriptor padre no encontrado [codigo: ' .
                        $suscriptorMigrado['cod_entesuscriptor_padre'] . 
                        ' | t_id: ' . $suscriptorMigrado['cod_entesuscriptor_padre']
                    );
                } else {
                    $nuevosDatos['suscriptor_padre_id'] = $padre['id'];
                }
            }

            $idSuscriptor = $this->_objSuscriptor->insert($nuevosDatos);
            $where = $this->_objSuscriptor->getAdapter()->quoteInto("id = ?", $idSuscriptor);

            $addData['slug'] = $this->_crearSlug($nuevosDatos, $idSuscriptor);
            $addData['hash'] = $this->_hashHelper->generaHashSuscriptor($idSuscriptor);
            $this->_objSuscriptor->update($addData, $where);

            $whereMig = $this->_objActSuscriptor->getAdapter()->quoteInto('id = ?', $suscriptorMigrado['id']);
            $this->_objActSuscriptor->update(array('indica_mig' => 1), $whereMig);
            $db->commit();
            $this->log->info("suscriptor insertado [id: " . $idSuscriptor . "]");
        } catch (Exception $e) {
            $this->log->info('[t_id :' . $suscriptorMigrado['id'] . '] ' . $e->getMessage());
            $db->rollBack();
        }
    }

    /*
     * ACTUALIZAR SUSCRIPTOR
     */
    private function actualizarSuscriptor($suscriptorMigrado, $suscriptorClub, $esBeneficiario = false)
    {
        $nuevosDatos = array();
        try {
            $db = $this->_objSuscriptor->getAdapter();

            $db->beginTransaction();

            $nuevosDatos['nombres'] = $suscriptorMigrado['des_nombres'];
            $nuevosDatos['apellido_paterno'] = $suscriptorMigrado['des_apepaterno'];
            $nuevosDatos['apellido_materno'] = $suscriptorMigrado['des_apematerno'];
            $nuevosDatos['sexo'] = $suscriptorMigrado['tip_sexoente'] == 'F' ? 'F' : 'M';
            $nuevosDatos['codigo_suscriptor'] = $suscriptorMigrado['cod_entesuscriptor'];
            $nuevosDatos['telefono'] = $suscriptorMigrado['des_numtelf01'];
            $nuevosDatos['activo'] = $suscriptorMigrado['estado_reparto'] == 'AC' ? 1 : 0;
            $nuevosDatos['es_invitado'] = $suscriptorMigrado['estado_beneficiario'] == 1 ? 1 : 0;
            $nuevosDatos['fecha_invitacion'] 
                = $suscriptorMigrado['estado_beneficiario'] == 1 ? $suscriptorMigrado['fch_registro']: null;
            $nuevosDatos['fecha_actualizacion'] = date('Y-m-d H:i:s');
            $nuevosDatos['es_suscriptor'] = $suscriptorMigrado['est_sus'] == 'AC' ? 1 : 0;
            $nuevosDatos['email_contacto'] = $suscriptorMigrado['des_email'];
            $nuevosDatos["fecha_nacimiento"] = null;
            if (!empty($suscriptorMigrado['fch_nacimiento']) && $suscriptorMigrado['fch_nacimiento'] != 'n') {
                $f = explode("/", $suscriptorMigrado["fch_nacimiento"]);
                if (count($f) == 3) {
                    $nuevosDatos["fecha_nacimiento"] = $f[2] . "-" . $f[1] . "-" . $f[0];
                } else {
                    $nuevosDatos["fecha_nacimiento"] = $suscriptorMigrado["fch_nacimiento"];
                }
            }

            if ($esBeneficiario && $suscriptorMigrado['estado_beneficiario'] == 1) {
                $padre = $this->_getSuscriptorByCodigo($suscriptorMigrado['cod_entesuscriptor_padre']);
                if (empty($padre)) {
                    throw new Exception(
                        'suscriptor padre no encontrado [codigo: ' .
                        $suscriptorMigrado['cod_entesuscriptor_padre'] . 
                        ' | t_id: ' . $suscriptorMigrado['cod_entesuscriptor_padre']
                    );
                } else {
                    $nuevosDatos['suscriptor_padre_id'] = $padre['id'];
                }
            }

//            $idSuscriptor = $this->_objSuscriptor->insert($nuevosDatos);
            $where = $this->_objSuscriptor->getAdapter()->quoteInto("id = ?", $suscriptorClub['id']);

            if(empty($suscriptorClub['slug']))
                $nuevosDatos['slug'] = $this->_crearSlug($nuevosDatos, $suscriptorClub['id']);
            if(empty($suscriptorClub['hash']))
                $nuevosDatos['hash'] = $this->_hashHelper->generaHashSuscriptor($suscriptorClub['id']);
            
            $this->_objSuscriptor->update($nuevosDatos, $where);
            $whereMig = $this->_objActSuscriptor->getAdapter()->quoteInto('id = ?', $suscriptorMigrado['id']);
            $this->_objActSuscriptor->update(array('indica_mig' => 1), $whereMig);
            $db->commit();
            $this->log->info("suscriptor actualizado [id: " . $suscriptorClub['id'] . "]");
        } catch (Exception $e) {
            $this->log->info('[t_id :' . $suscriptorMigrado['id'] . '] ' . $e->getMessage());
            $db->rollBack();
        }
    }

}

