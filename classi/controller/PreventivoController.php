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
    private $fDAO; //DAO della foto
    private $pdfWriter;
    
    function __construct() {
        $this->pDAO = new PreventivoDAO();
        $this->iDAO = new InfissoDAO();
        $this->imDAO = new InfissoMaggiorazioneDAO();
        $this->fDAO = new FotoDAO();
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
            $p->setNote($item->note);
            $p->setTipo($item->tipo);
            $p->setClienteTipo($item->cliente_tipo);
            $p->setClienteEmail($item->cliente_email);
            $p->setClienteCF($item->cliente_cf);
            
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
        $p->setNote($item->note);
        $p->setTipo($item->tipo);
        $p->setClienteTipo($item->cliente_tipo);
        $p->setClienteEmail($item->cliente_email);
        $p->setClienteCF($item->cliente_cf);

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
        $p->setNote($item['note']);
        $p->setTipo($item['tipo']);
        $p->setClienteTipo($item['clienteTipo']);
        $p->setClienteEmail($item['clienteEmail']);
        $p->setClienteCF($item['clienteCF']);
        
        //gli ho passato un array di nomi di foto
        //NB. Non è un oggetto foto
        //NB2. Potrebbero non aver incluso foto
        if(isset($item['foto'])){
            $fotos = array();
            foreach($item['foto'] as $nomeFoto){
               array_push($fotos, $nomeFoto);
            }
            $p->setFoto($fotos);
        }
        else{
            $p->setFoto(null);
        }
        
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
        $i->setAntaPrincipale($item['anta-principale']);
        $i->setPosizioneSerratura($item['posizione-serratura']);

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
        $i->setAntaPrincipale($item2->anta_principale);
        $i->setPosizioneSerratura($item2->posizione_serratura);

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
            //creo la pagina
            $this->pdfWriter->setPage();
            
            //intestazione          
            $this->pdfWriter->createHeader($p->getId(), $p->getTipo());
            
            //corpo del preventivo
            $this->pdfWriter->createBody($p);
            
            //stampo immagini se ce ne sono
            $foto = $this->getFotoPreventivo($idPreventivo);
            if($foto != null){
                $this->pdfWriter->printFoto($foto);
            }
            
            //footer
            //$this->pdfWriter->createFooter();
            
            //salvo il pdf
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
     * La funzione aggiorna il campo tipo, da preventivo ad ordine (da 0 a 1)
     * @param type $idPreventivo
     * @return type
     */
    public function setPreventivoToOrdine($idPreventivo){
        return $this->pDAO->setPreventivoToOrdine($idPreventivo);
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
        global $DIR_IMG_PREVENTIVI; 
        global $DIR_IMG_PREVENTIVI_THUMB;
        
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
        
        //elimino tutte le foto del preventivo
        $fotos = $this->getFotoPreventivo($idPreventivo);
        if($fotos != null){
            
            foreach($fotos as $item){               
                $f = new Foto();
                $f = $item;
                unlink($DIR_IMG_PREVENTIVI.$f->getNomeFoto());
                unlink($DIR_IMG_PREVENTIVI_THUMB.$f->getNomeFoto());
            }
            if(!$this->fDAO->deleteFotoPreventivo($idPreventivo)){
                return false;
            }
        }
        
        
        
        //elimino il preventivo
        if(!$this->pDAO->deletePreventivo($idPreventivo)){
            return false;
        }
        
        
        
        return true;
        
    }
    
    /**
     * La funzione salva le foto nel sistema
     * @param Foto $f
     * @return type
     */
    public function saveFoto(Foto $f){
        return $this->fDAO->saveFoto($f);
    }
    
    /**
     * La funzione esegue una query sul db per ottenere tutte le foto di un determinato
     * preventivo e restituisce un array di oggetti foto
     * @param type $idPreventivo
     * @return array
     */
    public function getFotoPreventivo($idPreventivo){
        $results = array();
        $foto = $this->fDAO->getFotoPreventivo($idPreventivo);
        
        foreach($foto as $item){
            $i = new Foto();
            $i->setIdPreventivo($item->id_preventivo);
            $i->setNomeFoto($item->nome_foto);
            $i->setUrlFoto($item->url_foto);
            $i->setUrlThumbFoto($item->url_thumb_foto);
            
            array_push($results, $i);
        }
        
        return $results;
    }
    
    
    
}
