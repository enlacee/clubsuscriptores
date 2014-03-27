<?php

class App_Controller_Action_Helper_ValidCatalogo extends Zend_Controller_Action_Helper_Abstract
{
    
    public function _adaptadorCatalogoVirtual($array)
    {
//        $return = array();
//        $array["quebuscas"];
//        $array["beneficios"];
//        $return["categoria"] =$array["categoria"];
//        $return["np"]        =$array["np"];
//        $return["col"]       =$array["col"];
//        $return["ord"]       =$array["ord"];
//        $return["page"]      =$array["page"];
//        var_dump($array);exit;
        
        $array["np_slug"]="";
        
        //***** col
        $esVal = $this->_esCOLClubS($array["categoria"],$array["np"]);
        if ($esVal["return"]) {
            $array["col"]      =$esVal["col"];
            $array["ord"]      =$esVal["ord"];
            $array["ord_slug"] =$esVal["ord_slug"];
            $array["categoria"]="";
        } else {
            $esVal = $this->_esCOLClubS($array["np"],$array["col"]);
            if ($esVal["return"]) {
                $array["col"]      =$esVal["col"];
                $array["ord"]      =$esVal["ord"];
                $array["ord_slug"] =$esVal["ord_slug"];
                $array["np"]="";
            } else {
                $esVal = $this->_esCOLClubS($array["col"],$array["ord"]);
                if ($esVal["return"]) {
                    $array["col"]      =$esVal["col"];
                    $array["ord"]      =$esVal["ord"];
                    $array["ord_slug"] =$esVal["ord_slug"];
                }
            }
        }
        
        //***** page
        $esVal = $this->_esPAGEClubS($array["categoria"]);
        if ($esVal["return"]) {
            $array["page"]     =$esVal["valor"];
            $array["categoria"]="";
        } else {
            $esVal = $this->_esPAGEClubS($array["np"]);
            if ($esVal["return"]) {
                $array["page"] =$esVal["valor"];
                $array["np"]   ="";
            } else {
                $esVal = $this->_esPAGEClubS($array["col"]);
                if ($esVal["return"]) {
                    $array["page"] =$esVal["valor"];
                    $array["col"]  ="";
                } else {
                    $esVal = $this->_esPAGEClubS($array["page"]);
                    if ($esVal["return"]) {
                        $array["page"] = $esVal["valor"];
                    } else {
                        $array["page"] = 1;
                    }
                }
            }
        }
        
        //***** np
        $esVal = $this->_esNPClubS($array["categoria"]);
        if ($esVal["return"]) {
            $array["np_slug"]  =$array["categoria"];
            $array["np"]       =$esVal["valor"];
            $array["categoria"]="";
        } else {
            $esVal = $this->_esNPClubS($array["np"]);
            if ($esVal["return"]) {
                $array["np_slug"]=$array["np"];
                $array["np"]     =$esVal["valor"];
            } else {
                $esVal = $this->_esNPClubS($array["col"]);
                if ($esVal["return"]) {
                    $array["np_slug"]=$array["col"];
                    $array["np"]     =$esVal["valor"];
                    $array["col"]    ="";
                } else {
                    $array["np"] ="";
                }
            }
        }
        
        //que buscas
        $esVal = $this->_esQUEBUSCASClubS($array["quebuscas"]);
        $array["quebuscas"]    = $esVal["quebuscas"];
        $array["quebuscas_id"] = $esVal["quebuscas_id"];
        $array["quebuscas_descrip"] = $esVal["quebuscas_descrip"];
        
        //var_dump($array);exit;
        return $array;
    }
    
    public function _esCOLClubS($col,$ord)
    {
        $return=false;$ord_slug="";
        $search_array = array(
            'nombre' => 2,
            'oferta' => 2,
            'tipo' => 2,
            'categoria' => 2,
            'fecha' => 2
        );
        if (array_key_exists($col, $search_array)) {
            $return=true;
        }
        
        if ($return) {
            if ($ord == "a-z"){
                $ord_slug=$ord;
                $ord     = "ASC";
            } else {
                $ord_slug="z-a";
                $ord     = "DESC";                
            }
        }
        
        return array("return"=>$return,"col"=>$col,"ord"=>$ord,"ord_slug"=>$ord_slug);
    }
    
    public function _esNPClubS($valor)
    {
        $return=false;
        if (!empty($valor)) {
            $explode=  explode("-", $valor);
            $count=count($explode);
            if ($count==2) {
                $explode[0] = (int) $explode[0];
                $explode[1] = (int) $explode[1];
                if( is_int($explode[0]) && is_int($explode[1]) ) {
                    if ($explode[0]<=$explode[1] && $explode[0]>0 ) {
                        $return=true;
                        $valor=$explode[0];
                    }                    
                }
            }
        } 
        return array("return"=>$return,"valor"=>$valor);
    }
    
    public function _esPAGEClubS($valor)
    {
        $return=false;
        if (!empty($valor)) {
            $explode=  explode("-", $valor);
            $count=count($explode);
            if ($count==1) {
                $valid = (int) $valor;
                if ($valid>0 ) {
                    $return=true;
                }
            }
        } 
        return array("return"=>$return,"valor"=>$valor);
    }
    
    public function _esQUEBUSCASClubS($valor)
    {
        
        switch ($valor) {
            case "nuevo":
                $quebuscas_id=0;
                $descrip="Lo nuevo";
                break;
            case "mas-visto":
                $quebuscas_id=1;
                $descrip="Lo más visto";
                break;
            case "mas-consumido":
                $quebuscas_id=2;
                $descrip="Lo más consumido";
                break;
//            case "todos":
//                $loultimo="";
//                $loultimo_id="";
//                break;
            default:
                $valor="nuevo";
                $quebuscas_id=0;
                $descrip="Lo nuevo";
                break;
        }
        
        return array("quebuscas"=>$valor,"quebuscas_id"=>$quebuscas_id,"quebuscas_descrip"=>$descrip);
    }
    
}