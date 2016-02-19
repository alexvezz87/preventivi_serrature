<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PreventivoController
 *
 * @author Alex
 */
class PreventivoController {
    private $pDAO; //DAO del preventivo
    private $iDAO; //DAO dell'infisso
    private $imDAO; //DAO di infisso_maggiorazione
    private $pdfWriter;
    
    function __construct() {
        $this->pDAO = new PreventivoDAO();
        $this->iDAO = new InfissoDAO();
        $this->imDAO = new InfissoMaggiorazioneDAO();
        $this->pdfWriter = new PdfController();
    }

    /**
     * La funzione salva un preventivo con i rispettivi infissi, passati come array di infissi
     * @param Preventivo $p
     * @param type $array
     * @return boolean
     */
    public function savePreventivo(Preventivo $p){
        //Per salvare il preventivo devo:
        //1. salvare il preventivo nel database e ottenere l'id
        //2. salvare tutti gli infissi con l'id del preventivo
        
        //$p = $this->convertToPreventivo($pArray);
        
        $idPreventivo = $this->pDAO->savePreventivo($p);
        
        
        if($idPreventivo == false){
            return false;
        }
        //dormo un secondo per dare tempo al db di ricevere risposta
        //sleep(1);
        foreach($p->getInfissi() as $item){
            //il parametro $array è composto da un vettore di oggetti infisso
            $i = new Infisso();
            $i = $item;
            
            $i->setIdPreventivo($idPreventivo);
                        
            $idInfisso = $this->iDAO->saveInfisso($i);
            if($idInfisso == false){
                return false;
            }
            //sleep(1);
            
            foreach($i->getMaggiorazioni() as $m){
                if(!$this->imDAO->saveInfissoMaggiorazione($idInfisso, $m)){
                    return false;
                }
            }
        }  
                
        return  $idPreventivo;
    }
    
    /**
     * La funzione restituisce in array di oggetti preventivo, passati dei parametri in ingresso
     * @param type $parameters
     * @return boolean|array
     */
    public function getPreventivi($parameters){
                
        $array = $this->pDAO->getPreventivi($parameters);
        if(count($array) == 0){
            return false;
        }
        $preventivi = array();
        //ciclo i preventivi per ottenere l'id e i relativi infissi
        foreach($array as $item){
            $p = new Preventivo();
            $p->setId($item->ID);
            $p->setData($item->data);
            $p->setIdUtente($item->id_utente);
            $p->setClienteNome($item->cliente_nome);
            $p->setClienteVia($item->cliente_via);
            $p->setClienteTel($item->cliente_tel);
            $p->setSpesaTotale($item->spesa_totale);
            $p->setVisionato($item->visionato);
            $p->setPdf($item->pdf);
            
            //ottengo gli infissi
            $array2 = $this->iDAO->getInfissi($p->getId());
            $infissi = array();
            foreach($array2 as $item2){               
                array_push($infissi, $this->getInfisso($item2));
            }
            $p->setInfissi($infissi);
            array_push($preventivi, $p);                    
        }        
        return $preventivi;
    }
    
    /**
     * La funzione restituisce un oggetto Preventivo dal database conoscendone l'ID
     * @param type $idPreventivo
     * @return \Preventivo|boolean
     */
    public function getPreventivo($idPreventivo){
        //chiamata al db
        $item = $this->pDAO->getPreventivo($idPreventivo);
        if($item == null){
            return false;
        }
        $p = new Preventivo();
        $p->setId($item->ID);
        $p->setData($item->data);
        $p->setIdUtente($item->id_utente);
        $p->setClienteNome($item->cliente_nome);
        $p->setClienteVia($item->cliente_via);
        $p->setClienteTel($item->cliente_tel);
        $p->setSpesaTotale($item->spesa_totale);
        $p->setVisionato($item->visionato);
        $p->setPdf($item->pdf);

        //ottengo gli infissi
        $array2 = $this->iDAO->getInfissi($p->getId());
        $infissi = array();
        foreach($array2 as $item2){               
            array_push($infissi, $this->getInfisso($item2));
        }
        $p->setInfissi($infissi);
        
        return $p;
        
    }
    
    /**
     * La funzione riceve un array associativo preventivo e restituisce un oggetto preventivo
     * @param type $item
     * @return \Preventivo
     */
    public function convertToPreventivo($item){
        $p = new Preventivo();
        $p->setData($item['data']);
        $p->setIdUtente($item['rivenditore']);
        $p->setClienteNome($item['clienteNome']);
        $p->setClienteVia($item['clienteVia']);
        $p->setClienteTel($item['clienteTel']);
        $p->setSpesaTotale($item['totale']);
        
        $infissi = array();
        foreach($item['infissi'] as $item){
            array_push($infissi, $this->convertToInfisso($item));
        }        
        $p->setInfissi($infissi);
        
        return $p;
        
    }
    
    /**
     * Funzione che converte un array associativo di inifisso in un oggetto infisso
     * @param type $item
     * @return \Infisso
     */
    private function convertToInfisso($item){
        $i = new Infisso(); 
        
        $i->setTipo($item['tipo-infisso']);
        $i->setNAnte($item['numero-ante']);
        $i->setIdInfisso($item['infisso']);
        $i->setAltezza($item['altezza']);
        $i->setLunghezza($item['lunghezza']);
        $i->setApertura($item['apertura']);
        $i->setBarra($item['barra']);
        $i->setSerratura($item['serratura']);
        $i->setNodo($item['nodo']);
        $i->setColore($item['colore']);
        $i->setCerniera($item['cerniera']);
        $i->setNInfisso($item['num-infissi']);
        $i->setSpesaInfisso($item['spesa-parziale']);       

        //ottengo le maggiorazioni        
        
        
        $maggiorazioni = array();
        if(isset($item['maggiorazione']) && count($item['maggiorazione']) > 0){
            foreach($item['maggiorazione'] as $m){
                //ciclo le maggiorazioni                    
                array_push($maggiorazioni, $m);
            }   
        }
        $i->setMaggiorazioni($maggiorazioni);
        return $i;
        
    }
    
    private function getInfisso($item2){
        //ciclo gli infissi
        $i = new Infisso();
        $i->setId($item2->ID);
        $i->setIdInfisso($item2->id_infisso);
        $i->setTipo($item2->tipo);
        $i->setNAnte($item2->n_ante);
        $i->setIdInfisso($item2->id_infisso);
        $i->setAltezza($item2->altezza);
        $i->setLunghezza($item2->lunghezza);
        $i->setApertura($item2->apertura);
        $i->setBarra($item2->barra);
        $i->setSerratura($item2->serratura);
        $i->setNodo($item2->nodo);
        $i->setColore($item2->colore);
        $i->setCerniera($item2->cerniera);
        $i->setNInfisso($item2->n_infisso);
        $i->setSpesaInfisso($item2->spesa_infisso);       

        //ottengo le maggiorazioni
        $array3 = $this->imDAO->getIdMaggiorazione($i->getId());
        $maggiorazioni = array();
        foreach($array3 as $item3){
            //ciclo le maggiorazioni                    
            array_push($maggiorazioni, $item3->id_maggiorazione);
        }                
        $i->setMaggiorazioni($maggiorazioni);
        
        return $i;
    }
    
    /**
     * La funzione sfruttando le classi FPDF crea un pdf e ne restituisce l'url salvato nel server
     * @global type $DIR_PDF
     * @global type $URL_PDF
     * @param type $idPreventivo
     * @return boolean
     */
    public function createPDF($idPreventivo){
        global $DIR_PDF;
        global $URL_PDF;
        
        //ottengo il preventivo
        $p = new Preventivo();
        $p = $this->getPreventivo($idPreventivo);    
        
        $name = 'preventivo-'.$p->getId().'.pdf';
       
        try{
            $this->pdfWriter->createHeader();
            $this->pdfWriter->savePDF($DIR_PDF.$name);            
            
            $result['url'] = $URL_PDF.$name;
            $result['dir'] = $DIR_PDF.$name;
                        
            return $result;
        }
        catch (Exception $ex){
            _e($ex);
            return false;
        }        
    }
    
    /**
     * La funzione aggiorna il campo pdf nel database
     * @param type $idPreventivo
     * @param type $url
     * @return type
     */
    public function updateUrlPdf($idPreventivo, $url){
        return $this->pDAO->updatePDF($idPreventivo, $url);
    }
    
    /**
     * La funzione aggiorna il campo visionato nel database a 1
     * @param type $idPreventivo
     * @return type
     */
    public function setPreventivoVisionato($idPreventivo){
        return $this->pDAO->setPreventivoVisionato($idPreventivo);
    }
    
    /**
     * La funzione elabora un preventivo ed invia una mail con associato un allegato
     * @param type $idPreventivo
     * @param type $dir
     * @return type
     */
    public function sendEmailtoAdmin($idPreventivo, $dir){
        //creo un oggetto preventivo
        $p = new Preventivo();
        $p = $this->getPreventivo($idPreventivo);
        
        //ottengo il nome del rivenditore/agente
        $user_info = get_userdata($p->getIdUtente());
        
        $to = 'info@alexsoluzioniweb.it';
        $subject = "Ricevuto Preventivo online da ".$user_info->display_name;
        $message = "Un nuovo preventivo online è stato ricevuto!";
        $attachments = array($dir);
        
        add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));
        return wp_mail($to, $subject, $message, $headers = '', $attachments );                
        
    }
    
    public function deletePreventivo($idPreventivo){
        //devo eliminare tutto ciò che riguarda il preventivo
        
        //ottengo gli id degli infissi del preventivo
        $infissi = $this->iDAO->getIdInfissi($idPreventivo);
        //elimino tutti le associazioni di maggiorazione/infisso dedicate a quell'infisso
        foreach($infissi as $infisso){
            if(!$this->imDAO->deleteMaggiorazioni($infisso->ID)){
                return false;
            }
        }
        
        //elimino tutti gli infissi del preventivo
        if(!$this->iDAO->deleteInfissi($idPreventivo)){
            return false;
        }
        
        //elimino il preventivo
        if(!$this->pDAO->deletePreventivo($idPreventivo)){
            return false;
        }
        
        return true;
        
    }
    
    
}
