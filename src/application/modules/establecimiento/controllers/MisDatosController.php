<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of MisDatosController
 *
 * @author Favio Condori
 */
class Establecimiento_MisDatosController extends App_Controller_Action_Establecimiento
{
    protected $_establecimiento;

    public function init()
    {
        parent::init();
        $this->_establecimiento = new Application_Model_Establecimiento();
    }

    public function indexAction()
    {
        Zend_Layout::getMvcInstance()->active =
            App_Controller_Action_Establecimiento::MENU_NAME_MIS_DATOS;
        $this->view->headScript()->appendFile(
            $this->mediaUrl . '/js/establecimiento/mis_datos.js'
        );

        $this->view->headScript()->appendFile(
            $this->mediaUrl . '/js/establecimiento/establecimiento.logo.uploader.js'
        );

        $establecimiento = $this->_establecimiento->getEstablecimiento($this->auth['establecimiento']['id']);
        $imagen = $establecimiento['path_imagen'];
        $formNEstablecimiento = new Application_Form_Establecimiento(null);
        $formNEstablecimiento->setValuesEdit();
        $formNEstablecimiento->setDefaults($establecimiento);

        if ($this->_request->isPost()) {
            $formNEstablecimiento->getElement('path_imagen')->removeValidator('Count');
            $allParams = $this->_getAllParams();
            $formValid = $formNEstablecimiento->isValid($allParams);
            if ($formValid) {
                try {
                    $db = $this->getAdapter();
                    $db->beginTransaction();

                    $utilfile = $this->_helper->getHelper('UtilFiles');
                    $nuevoNombre =
                        $utilfile->_renameFile(
                            $formNEstablecimiento, 'path_imagen', 'establecimiento'
                        );
                    $valuesEstablecimiento = $formNEstablecimiento->getValues();
                    $date = date('Y-m-d H:i:s');
                    if ($nuevoNombre != '') {
                        @unlink($this->_config->paths->elementsEstablecimientoRoot . $imagen);
                        $valuesEstablecimiento['path_imagen'] = $nuevoNombre;
                    } else {
                        $valuesEstablecimiento['path_imagen'] = $imagen;
                    }
                    $valuesEstablecimiento['actualizado_por'] = $this->auth['usuario']->id;
                    $valuesEstablecimiento['fecha_actualizacion'] = $date;
                    $where = $this->_establecimiento->getAdapter()->quoteInto('id = ?', $establecimiento['id']);

                    $val = $this->_establecimiento->update($valuesEstablecimiento, $where);
                    $db->commit();
                    if ($val) {
                        $this->getMessenger()->success('Establecimiento actualizado con exito.');
                    }
                    $storage = Zend_Auth::getInstance()->getStorage()->read();
                    $storage['establecimiento']['contacto'] = $valuesEstablecimiento['contacto'];
                    $storage['establecimiento']['path_imagen'] = $valuesEstablecimiento['path_imagen'];
                    $storage['establecimiento']['email_contacto'] = $valuesEstablecimiento['email_contacto'];
                    $storage['establecimiento']['direccion'] = $valuesEstablecimiento['direccion'];
                    $storage['establecimiento']['telefono'] = $valuesEstablecimiento['telefono'];
                    Zend_Auth::getInstance()->getStorage()->write($storage);
                    $this->_redirect('/establecimiento/mis-datos');
                } catch (Exception $e) {
                    $db->rollBack();
                    $this->getMessenger()->error('Error al actualizar.');
                    echo $e->getMessage();
                }
            }
        }

        $this->view->formEstablecimiento = $formNEstablecimiento;
        $this->view->establecimiento = $establecimiento;
    }

    public function listarUsuariosAction()
    {
        $this->_helper->layout->disableLayout();
        $page = $this->_getParam('pag', 1);
        $usuarios = Application_Model_Administrador
            ::getUsuariosByEstablecimientoIDPaginator($this->auth['establecimiento']['id']);
        $usuarios->setCurrentPageNumber($page);

        $nroPorPage = $usuarios->getItemCountPerPage();
        $nroPage = $usuarios->getCurrentPageNumber();
        $nroReg = $usuarios->getCurrentItemCount();

        $this->view->mostrando = "Mostrando " .
            ' ' . (($nroPage - 1) * $nroPorPage + 1) .
            ' - ' . ((($nroPage - 1) * $nroPorPage) + $nroReg) .
            ' de ' . $usuarios->getTotalItemCount();
        $this->view->usuarios = $usuarios;
        $this->view->nroregistros = "Listados : " . $nroReg;
    }

    public function cargafotoAction()
    {
        $img = 'path_imagen';
        $config = Zend_Registry::get('config');
        $r = $this->getRequest();
        if ($r->isPost()) {
            $session = $this->getSession();
            if ($session->__isset("tmp_img")) {
                @unlink($session->__get("tmp_img"));
            }
            $tamanomax = $r->__get("filesize");
            $tamano = $_FILES[$img]['size'];
            if ($tamano <= $tamanomax) {
                $utilfile = $this->_helper->getHelper('UtilFiles');
                $archivo = $_FILES[$img]['name'];
                $tipo = $utilfile->_devuelveExtension($archivo);
                $nombrearchivo = "elements/establecimientos/temp/temp_" . time() . "." . $tipo;
                $session->__set("tmp_img", $nombrearchivo);
                move_uploaded_file($_FILES[$img]['tmp_name'], $nombrearchivo);
                $imgx = new ZendImage();
                $imgx->loadImage(APPLICATION_PATH . "/../public/" . $nombrearchivo);
                echo "success|<img height='" .
                $config->logoestablecimiento->tamano->establecimiento->h . "' 
                    width='" . $config->logoestablecimiento->tamano->establecimiento->w . "' 
                    style='height:" . $config->logoestablecimiento->tamano->establecimiento->h . "px;
                        width:" . $config->logoestablecimiento->tamano->establecimiento->w . "px'
                    src='" . SITE_URL . '/' . $nombrearchivo . "' />";
            } else {
                echo "error|Tamaño de archivo sobrepasa el limite Permitido";
            }
        } else {
            echo "error|ERROR";
        }
        die();
    }

}
