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
    
    function __construct() {
        $this->pDAO = new PreventivoDAO();
        $this->iDAO = new InfissoDAO();
        $this->imDAO = new InfissoMaggiorazioneDAO();
    }

    /**
     * La funzione salva un preventivo con i rispettivi infissi, passati come array di infissi
     * @param Preventivo $p
     * @param type $array
     * @return boolean
     */
    public function savePreventivo(Preventivo $p, $array){
        //Per salvare il preventivo devo:
        //1. salvare il preventivo nel database e ottenere l'id
        //2. salvare tutti gli infissi con l'id del preventivo
        
        $idPreventivo = $this->pDAO->savePreventivo($p);
        $spesaTotale = 0;
        
        if($idPreventivo == false){
            return false;
        }
        //dormo un secondo per dare tempo al db di ricevere risposta
        //sleep(1);
        foreach($array as $item){
            //il parametro $array è composto da un vettore di oggetti infisso
            $i = new Infisso();
            $i = $item;
            
            $i->setIdPreventivo($idPreventivo);
            //il totale è quello complessivo, viene salvata la spesa dal front-end
            $spesaTotale+= floatval($i->getSpesaInfisso()) * floatval($i->getNInfisso());
            
            //ottengo gli id delle maggiorazioni associate
            //il pattern è x,x,x            
            $temp = explode(',', $i->getMaggiorazioni());
            
            $idInfisso = $this->iDAO->saveInfisso($i);
            if($idInfisso == false){
                return false;
            }
            //sleep(1);
            for($k=0; $k < count($temp); $k++){
                //associo nel database la maggiorazione all'infisso
                if(!$this->imDAO->saveInfissoMaggiorazione($idInfisso, $temp[$k])){
                    return false;
                }
            }
        }   
        //aggiorno la spesa totale
        if(!$this->pDAO->updateSpesaTotale($idPreventivo, $spesaTotale)){
            return false;
        }
        
        return true;
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
    
    private function getInfisso($item2){
        //ciclo gli infissi
        $i = new Infisso();
        $i->setId($item2->id);
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
    
    
    
    
}
