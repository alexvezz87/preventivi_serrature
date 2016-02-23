<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MaggiorazioneController
 *
 * @author Alex
 */
class MaggiorazioneController {
   
    private $DAO;
    
    function __construct() {
        $this->DAO = new MaggiorazioneDAO();
    }
    
    /**
     * La funzione salva una maggiorazione nel database
     * @param Maggiorazione $m
     * @return type
     */
    public function saveMaggiorazione(Maggiorazione $m){        
        return $this->DAO->saveMaggiorazione($m);
    }
    
    /**
     * La funzione aggiorna una maggiorazione esistente nel database
     * @param Maggiorazione $m
     * @return type
     */
    public function updateMaggiorazione(Maggiorazione $m){
        return $this->DAO->saveMaggiorazione($m);
    }
    
    /**
     * La funzione esegue una query sul database per ottenere tutte le maggiorazioni
     * e restituisce un array di oggetti Maggiorazione
     * @return array
     */
    public function getMaggiorazioni(){
        $maggiorazioni = array();
        $result = $this->DAO->getMaggiorazioni();
        
        foreach($result as $item){
            $m = new Maggiorazione();
            $m->setID($item->ID);
            $m->setNome($item->nome);
            $m->setQuantita($item->quantita);
            $m->setUnitaMisura($item->unita_misura);
            array_push($maggiorazioni, $m);
        }
        
        return $maggiorazioni;
    }
    
    /**
     * Funzione che elimina una maggiorazione passata per parametro
     * @param type $idM
     * @return type
     */
    public function deleteMaggiorazione($idM){
        return $this->DAO->deleteMaggiorazione($idM);
    }
    
    public function getInfoMaggiorazione($idMaggiorazione){
        $maggiorazione = $this->DAO->getMaggiorazione($idMaggiorazione);
        
        return $maggiorazione->nome.' + '.$maggiorazione->quantita.' '.$maggiorazione->unita_misura;
    }
    

}
