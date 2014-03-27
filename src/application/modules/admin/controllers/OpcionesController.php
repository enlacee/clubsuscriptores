<?php

class Admin_OpcionesController extends App_Controller_Action_Admin
{
    private $_opcion;

    public function init()
    {
        parent::init();
        $this->_opcion = new Application_Model_Opcion();
    }

    public function indexAction()
    {
        Zend_Layout::getMvcInstance()->active = App_Controller_Action_Admin::MENU_NAME_OPCIONES;
        $this->view->headScript()->appendFile(
            $this->mediaUrl . '/js/admin/opciones.js'
        );
        $filtroModulo = new Application_Form_FiltroModulo();
        $this->view->filtroModulo = $filtroModulo;
    }

    public function listarOpcionesAction()
    {
        $this->_helper->layout->disableLayout();
        $page = $this->_getParam('pag', 1);
        $modulo = $this->_getParam('modulo', null);
        $opciones = Application_Model_Opcion::getOpcionesPaginator($modulo);
        $opciones->setCurrentPageNumber($page);

        $nroPorPage = $opciones->getItemCountPerPage();
        $nroPage = $opciones->getCurrentPageNumber();
        $nroReg = $opciones->getCurrentItemCount();

        $this->view->mostrando = "Mostrando " .
            ' ' . (($nroPage - 1) * $nroPorPage + 1) .
            ' - ' . ((($nroPage - 1) * $nroPorPage) + $nroReg) .
            ' de ' . $opciones->getTotalItemCount();
        $this->view->opciones = $opciones;
        $this->view->nroregistros = "Registros listados : " . $nroReg;
    }

    public function editarOpcionAction()
    {
        $this->_helper->layout->disableLayout();
        $formOpcion = new Application_Form_Opcion();

        if ($this->_request->isPost()) {
            $this->_helper->viewRenderer->setNoRender();
            $params = $this->_getAllParams();
//            $formOpcion->setDefaults($params);
            $isValid = $formOpcion->isValid($params);
            if ($isValid) {
                $values = $formOpcion->getValues();
                try {
                    $db = $this->getAdapter();
                    $db->beginTransaction();
                    $where = $db->quoteInto('id = ?', $params['id']);
                    unset($values['id']);
                    $this->_opcion->update($values, $where);
                    $this->_response->setBody('<span class="good">Opción actualizada correctamente</span>');
                    $db->commit();
                } catch (Exception $exc) {
                    $db->rollBack();
                    $this->_response->setBody('<span class="bad">Hubo un error en la actualización</span>');
                }
            } else {
                $this->_response->setBody('<span class="bad">Los datos ingresados no son correctos</span>');
            }
        } else {
            $id = $this->_getParam('id', 0);
            $opcion = $this->_opcion->getOpcionByID($id);
            $formOpcion->setDefaults($opcion);
            $this->view->opcion = $opcion;
        }
        $this->view->formOpcion = $formOpcion;
    }

}

