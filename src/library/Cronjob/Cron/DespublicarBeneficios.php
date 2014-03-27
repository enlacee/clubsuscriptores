<?php

class Cronjob_Cron_DespublicarBeneficios extends Cronjob_Base
{

    public $descripcion="CRON DESPUBLICAR BENEFICIOS";
 
    public function __construct($args = null)
    {
        parent::__construct($args);
    }

    public function run()
    {
        //Beneficios
        $objBeneficio = new Application_Model_Beneficio();
        $objBeneficioVersion = new Application_Model_BeneficioVersion();
        $dadosBaja = $objBeneficioVersion->getBeneficioVersionVencidos();
        foreach ($dadosBaja as $index=>$value) {
            $arreglo["activo"] = "0";
            $objBeneficio->update($arreglo, "id=".$value["id"]);

            $zl = new ZendLucene();
            $zl->deleteIndexBeneficio($value["id"]);
            
            $this->log->info("BENEFICIO CON ID:".$value["id"]." DADO DE BAJA");
        }
        
        //Articulos
        $objArticulo = new Application_Model_Articulo();
        $dadosBajaArticulos = $objArticulo->getArticulosVencidos();
        
        foreach ($dadosBajaArticulos as $index=>$value) {
            $arreglo["activo"] = "0";
            $arreglo["fecha_actualizacion"] = date("Y-m-d H:i:s");
            $objArticulo->update($arreglo, "id=".$value["id"]);
            $this->log->info("ARTICULO CON ID:".$value["id"]." DADO DE BAJA");
        }
        parent::run();
    }
} 

