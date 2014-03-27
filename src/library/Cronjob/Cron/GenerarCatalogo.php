<?php

class Cronjob_Cron_GenerarCatalogo extends Cronjob_Base
{
    public $descripcion="CRON GENERAR CATALOGO DE BENEFICIOS";
 
    public function __construct($args = null)
    {
        parent::__construct($args);
    }

    public function run()
    {
        $bin = APPLICATION_PATH."/../../bin/wkhtmltopdf/linux/wkhtmltopdf-i386";
        if(count(explode("WIN", PHP_OS))>1) 
                $bin = APPLICATION_PATH."/../../bin/wkhtmltopdf/win/wkhtmltopdf.exe";
        
        try {
            $wkhtmltopdf = new Wkhtmltopdf(
                array(
                    'path' => ELEMENTS_ROOT.'/pdfs_tmp/',
                    'binpath' => $bin
                )
            );
            $wkhtmltopdf->setTitle("Catalogo de Beneficios");
            $wkhtmltopdf->setUrl($this->urlActual()."/magazine");
            $wkhtmltopdf->output(
                Wkhtmltopdf::MODE_SAVE, ELEMENTS_ROOT.
                '/pdfs_tmp/catalogodebeneficios.pdf'
            );
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        parent::run();
    }
}

