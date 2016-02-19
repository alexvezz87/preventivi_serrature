<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PdfController
 *
 * @author Alex
 */
require_once 'fpdf/fpdf.php';

class PdfController extends FPDF {
    //put your code here
    function __construct(){
        parent::__construct();
    }
    
    public function createHeader(){     
        $this->AddPage();
        $this->SetFont('Arial','B',15);
        $this->Cell(30,10,'Preventivo',1,0,'C');
    }
    
    public function savePDF($path){       
        //salvo il file nel file system
        $this->Output($path, 'F');   
    }

    
}
