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
    
    public function setPage(){
        $this->AliasNbPages();
        $this->AddPage();
        $this->SetFont('Times','',12);
    }    
    
    public function createHeader($num, $tipo){
        global $URL_IMG;        
        
        if($tipo == 0){
            $tipo = 'Preventivo';
        }
        else{
            $tipo = 'Ordine';
        }
        
        $this->SetFont('Arial','B',18);
        $this->Image($URL_IMG.'logo_pradelli.png',10,10,30);
        $this->Cell(40);        
        $this->Cell(60,10, $tipo.' online n. '.$num,0,0,'L');
        $this->Ln();
        $this->Line(5, 25, 205, 25);
        $this->Ln(7);
    }
    
    public function createBody(Preventivo $p){  
        
        define('EURO',chr(128));
        //scrivo i dati generali del preventivo
        $infissi = $p->getInfissi();
        
        //creo l'array associativo
        $info_preventivo = array();
        $info_preventivo['Data'] = getTime($p->getData());
        $nomeRivenditore = "";
        if($p->getIdUtente() != 0){
            $user_info = get_userdata($p->getIdUtente());
            $nomeRivenditore = $user_info->display_name; 
        }
        $info_preventivo['Rivenditore/Agente'] = $nomeRivenditore;
        $info_preventivo['Tipo Cliente'] = $p->getClienteTipo();
        $info_preventivo['Cliente'] = $p->getClienteNome();
        $info_preventivo['CF/PIVA'] = $p->getClienteCF();
        $info_preventivo['Indirizzo'] = $p->getClienteVia();
        $info_preventivo['Telefono'] = $p->getClienteTel();
        $info_preventivo['Email'] = $p->getClienteEmail();
        $tipo = "";
        if($p->getTipo() == 0){
            $tipo = 'Preventivo';
        }
        else{
            $tipo = 'Ordine';
        }        
        $info_preventivo['Tipo'] = $tipo;
        $info_preventivo['Num. Infissi'] = count($infissi);
        $info_preventivo['Prezzo Totale'] = EURO.' '.$p->getSpesaTotale();
        $info_preventivo['Note'] = $p->getNote();
        
        $this->printPdfTable($info_preventivo);   
        
        
        
        
        //stampo una linea
            $this->Ln(3);
            $this->Cell(180,0,'',1);
            $this->Ln(3);
        
        //scrivo la tabella per gli infissi 
        $this->printTableInfissi($infissi);        
        
    }
    
    private function printTableInfissi($infissi){
        
        $tPrezzi = new TabellaPrezziController();   
        $cMaggiorazione = new MaggiorazioneController();
        $startLine = 85;
        $count = 1;
        foreach($infissi as $item){
            $array = array();
            $i = new Infisso();
            $i = $item;
            
            $array['count'] = $count;
            $array['Tipo infisso'] = $i->getTipo();
            if($i->getNAnte() == 0){
                $array['Numero Ante'] = 'inferriata fissa';
            }
            else{
                $array['Numero Ante'] = $i->getNAnte();
            }
                       
            $array['Infisso'] = $tPrezzi->getNomeInfisso($i->getIdInfisso());
            $array['Altezza'] = $i->getAltezza().' mm';
            $array['Lunghezza'] = $i->getLunghezza().' mm';
            $array['Apertura (vista interna)'] = str_replace('-', ' ', $i->getApertura());
            
            $antaPrincipale = "";
            switch ($i->getAntaPrincipale()){
                case 'D': $antaPrincipale = "Destra"; break;
                case 'DC': $antaPrincipale = "Centrale Destra"; break;
                case 'S' : $antaPrincipale = "Sinistra"; break;
                case 'SC' : $antaPrincipale = "Centrale Sinistra"; break;
                default : $antaPrincipale = "Centrale"; break;
            }                        
            $array['Anta principale'] = $antaPrincipale;
            
            $posizioneSerratura = "";
            switch($i->getPosizioneSerratura()){
                case 'S': $posizioneSerratura = "Sinistra"; break;
                case 'D': $posizioneSerratura = "Destra"; break;
                default : $posizioneSerratura = "Nessuna"; break;                
            }            
            $array['Posizione serratura'] = $posizioneSerratura;
            
            $array['Barra'] = str_replace('-', ' ', $i->getBarra());
            if($i->getSerratura() == 'cilindro'){
                $array['Serratura'] = 'Solo cilindro';
            }           
            else{
                $array['Serratura'] = 'A leva con maniglia in pvc';
            }
            $array['Nodo'] = 'Nodo '.$i->getNodo();
            $array['Colore'] = str_replace('-', ' ', $i->getColore());
            $array['Cerniera'] = str_replace('-', ' ', $i->getCerniera());
            //maggiorazioni
            $maggiorazioni = $i->getMaggiorazioni();
            $count_maggiorazioni = 1;
            foreach($maggiorazioni as $m){
                $array['Maggiorazione '.$count_maggiorazioni] = str_replace('€', EURO, $cMaggiorazione->getInfoMaggiorazione($m));               
                $count_maggiorazioni++;
            }            
            $array['Spesa'] = EURO.' '.$i->getSpesaInfisso();
            $array['Copie infisso'] = $i->getNInfisso();            
            
            
            if($count % 2 == 0){
                $this->AddPage();
            }
            
            
            $this->printPdfTable($array);            
            
            //stampo una linea
            $this->Ln(3);
            $this->Cell(180,0,'',1);
            $this->Ln(3);
            
            $count++;
        }
        
    }
        
    private function printPdfTable($array){
        //imposto il bordo
        $border = 0;
        
        foreach($array as $chiave => $valore){
            
            if($chiave == 'count'){
                $this->SetFont('Arial','B',12);
                $this->Cell(50, 8, 'Infisso N.'.$valore, $border); 
                $this->Ln();
            }
            else{
                //imposto il font bold per la chiave
                $this->SetFont('Arial','B',10);
                //scrivo la chiave
                $this->Cell(50, 4, $chiave, $border);            
                //imposto il font non bold per il valore
                $this->SetFont('Arial','',10);
                //scrivo il valore
                $this->MultiCell(130, 4, $valore, $border, 'left'); 
                $this->Ln();
            }
        }
        
    }
    
    /**
     * La funzione ricevuto in ingresso un array di foto, stampa sul pdf le immagini
     * @param type $arrayFoto
     */
    public function printFoto($arrayFoto){
        $count = 1;
        foreach($arrayFoto as $item){
            $foto = new Foto();
            $foto = $item;
            
            //stampo una linea a separazione
            $this->AddPage();
            $this->SetFont('Arial','B',18);          
            $this->Cell(60,10, 'Foto '.$count,0,0,'L');
            $this->Ln(20);
            $this->Image(preg_replace('/ /', '%20', $foto->getUrlFoto()), $this->GetX(), $this->GetY(), 180);
            $count++;
        }        
        
    }
    
    public function createFooter(){
        $this->SetY(-10);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
    }
    
    public function savePDF($path){       
        //salvo il file nel file system
        $this->Output($path, 'F');   
    }
    
    
    // Simple table
    function BasicTable($header, $data){
        // Header
        foreach($header as $col)
            $this->Cell(40,7,$col,1);
        $this->Ln();
        // Data
        foreach($data as $row)
        {
            foreach($row as $col)
                $this->Cell(40,6,$col,1);
            $this->Ln();
        }
    }

}
