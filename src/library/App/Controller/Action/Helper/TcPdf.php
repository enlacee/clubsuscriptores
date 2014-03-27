<?php
/*
 * Helper para manejar de TcPdf.
 * 
 */
class App_Controller_Action_Helper_TcPdf
    extends Zend_Controller_Action_Helper_Abstract
{
    protected $_pdf;
    
    public function init()
    {
        //$this->_pdf = new TCPDF();
    }
    
    public function render($html, $tamano, $orientacion='P') {
        $this->_pdf = new TCPDF();
        $this->_pdf->setPageOrientation($orientacion);
        $this->_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $this->_pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $this->_pdf->AddPage();
        $this->_pdf->writeHTML($html, true, false, true, false, '');
        //$this->_pdf->SetFont("helvetica");
        
        $url = "http://www.s.devel.clubsc.info/images/logocs.png";
        $url="http://www.e.devel.clubsc.info/beneficios/Promo-1017-4bac653e7c870289be6455d26dd1dab7_80_210x115.jpeg";
        //$url="http://www.prelovac.com/vladimir/wp-content/uploads/2008/03/example.jpg";
        //ini_set('allow_url_fopen','On');
        //$b64 = base64_encode(file_get_contents($url,true));
        
        //echo $b64; exit;
        //echo "<img src=data:image/png;base64,".$b64." />"; exit;
        //$this->_pdf->Image("@".$b64, 0,0,100,100);
        //$this->_pdf->setJPEGQuality(75);
        $this->_pdf->Image($url, 50, 0, 30, 30);
        //echo file_get_contents('http://google.com');
        
    }
    
    public function mostrarPDF($html, $tamano, $orientacion='P',$filename = 'example.pdf') {
        $this->render($html, $tamano, $orientacion);
        $this->_pdf->Output($filename, 'I');
    }
}
