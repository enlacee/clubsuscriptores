<?php

class Application_Model_Articulo extends App_Db_Table_Abstract
{
    protected $_name = 'articulo';

    public function getVidaSocialCache($cache=true)
    {
        $cacheEt = $this->_config->cache->{$this->info('name')}->{__FUNCTION__};
        $cacheId = $this->info('name').'_'.__FUNCTION__;
        if ($this->_cache->test($cacheId) && $cache) {
            return $this->_cache->load($cacheId);
        }
        
        $sql = $this->getArticulos("RAND()", "", "", "", true, 1,true);;
        $result = $this->getAdapter()->fetchRow($sql);

        $this->_cache->save($result, $cacheId, array(), $cacheEt);
        return $result;
    }
    
    public function getArticulos($col="", $ord="", $estado="", $query="", $flag=true, $limit=0, $activos="")
    {
        $col = $col==""?"fecha_registro":$col;
        $ord = $ord==""?"DESC":$ord;
        
        $db = $this->getAdapter();
        // listando los articulos activos o que se publicaran
        $sql = $db->select()->from(array('art'=>$this->_name))
                //->where('CURRENT_DATE() BETWEEN fecha_inicio_vigencia AND fecha_fin_vigencia')
                ->where('gal.es_principal = ?', 1);
        $sql->join(
            array('gal'=>'galeria_imagenes'), 'gal.articulo_id = art.id', 
            array('imagen'=>'path_imagen','galid'=>'id','orden')
        );
        if ($flag) {
            $sql = $sql->where('art.activo = ?', 1);
        }
        if ($estado!="") {
            $sql = $sql->where('art.activo = ?', $estado);
        }
        if ($query!="") {
            $where = $this->getAdapter()->quoteInto("art.titulo like ?", "%".$query."%");
            $sql = $sql->where($where);
        }
        if (!empty($limit)) {
            $sql = $sql->limit($limit);
        }
//        if (!empty($activos)) {
//            $sql = $sql->where('CURRENT_DATE() BETWEEN art.fecha_inicio_vigencia AND art.fecha_fin_vigencia');
//        }
        $sql->where('elog = ?', 0); //agregado v1.5
        $sql = $sql->order($col." ".$ord);
        //echo $sql->assemble(); exit;
        return $sql;
    }
    public function getListaArticulos($col="", $ord="", $estado="", $query="")
    {
        $paginado = $this->_config->gestor->articulos->nropaginas;
        $p = Zend_Paginator::factory(
            $this->getArticulos($col, $ord, $estado, $query, false)
        );
        return $p->setItemCountPerPage($paginado);
    }
    
    public function getArticuloReciente()
    {
        $db = $this->getAdapter();
        $sql = $db->select()->from(array('art'=>$this->_name))
                //->limit(1, 0)
                ->where('art.activo = ?', 1)->where('art.portada = ?', 1)
                ->where(
                    'CURRENT_DATE() BETWEEN art.fecha_inicio_vigencia'.
                    ' AND art.fecha_fin_vigencia'
                )
                ->order('art.fecha_registro desc');
        
        $sql->join(
            array('gal'=>'galeria_imagenes'), 'gal.articulo_id = art.id', 
            array('idimg'=>'id','orden')
        )->where('gal.es_principal = ?', 1);
        
        //echo $sql; exit;
        $rs = $db->fetchRow($sql);
        return $rs;
    }
    
    public function getArticulo($articuloId)
    {
        $sql = $this->select()->from($this->_name)->where('id = ?', $articuloId);
        $rs = $this->getAdapter()->fetchRow($sql);
        return $rs;
    }

    public function getArticulosAPublicar()
    {
        $db = $this->getAdapter();
        $sql = $db->select()->from(array('a'=>$this->_name))
                ->where(
                    'a.fecha_inicio_vigencia = CURRENT_DATE()'
                );
        $rs = $this->getAdapter()->fetchAll($sql);
        return $rs;
    }

    public function getArticulosVencidos()
    {
        $db = $this->getAdapter();
        $sql = $db->select()->from(array('a'=>$this->_name))
                ->where(
                    'CURRENT_DATE() > a.fecha_fin_vigencia'
                )
                ->where(
                    'a.portada <> 1'
                );
        $rs = $this->getAdapter()->fetchAll($sql);
        return $rs;
    }
}
