<?php

class Cronjob_Cron_PublicarBeneficios extends Cronjob_Base
{

    public $descripcion="CRON PUBLICAR BENEFICIOS";
 
    public function __construct($args = null)
    {
        parent::__construct($args);
    }

    public function run()
    {
        //Beneficios
        $objBeneficio = new Application_Model_Beneficio();
        $apublicar = $objBeneficio->getBeneficiosAPublicar();
        
        foreach ($apublicar as $index=>$value) {
            $arreglo["publicado"] = "1";
            $objBeneficio->update($arreglo, "id=".$value["id"]);
            $this->log->info("BENEFICIO CON ID:".$value["id"]." PUBLICADO");
        }

        //Articulos
        $objArticulo = new Application_Model_Articulo();
        $apublicar = $objArticulo->getArticulosAPublicar();
        foreach ($apublicar as $index=>$value) {
            $arreglo["activo"] = "1";
            $arreglo["fecha_actualizacion"] = date("Y-m-d H:i:s");
            $objArticulo->update($arreglo, "id=".$value["id"]);
            $this->log->info("ARTICULO CON ID:".$value["id"]." ACTIVADO");
        }

        parent::run();
    }
}

