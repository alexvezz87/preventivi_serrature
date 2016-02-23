<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TabellaPrezziController
 *
 * @author Alex
 */
class TabellaPrezziController {
    
    private $DAO;
    
    function __construct() {
        $this->DAO = new TabellaPrezziDAO();
    }
    
    /**
     * Funzione che crea una tabella di prezzi di articoli
     * @param Tabella $tab
     * @return boolean
     */
    public function saveTabellaArticolo(Tabella $tab){
        //La funzione deve creare una tabella dai dati ricevuti come parametro
        //In primis deve creare la cartella e ricevere l'ID tabella
        //In secondo luogo, ricevuto l'ID, vengono generate NxM celle vuote 
        
        //Creo la tabella
        $idTabella = $this->DAO->saveTabellaArticolo($tab);
        if($idTabella != false){
            //agisco solo se la tabella è stata salvata
            
            for($rowCount = $tab->getStartRows(); $rowCount <= $tab->getEndRows(); $rowCount += $tab->getStepRows()){
                for($colCount = $tab->getStartCols(); $colCount <= $tab->getEndCols(); $colCount += $tab->getStepCols()){
                    $prezzo = new Prezzo();
                    $prezzo->setIdTabella($idTabella);
                    $prezzo->setValRow($rowCount);
                    $prezzo->setValCol($colCount);
                    $prezzo->setPrezzo(0);
                    $this->DAO->savePrezziTabella($prezzo);
                }
            }            
            return true;
        }        
        return false;
    }
    
    /**
     * Funzione che aggiorna uno specifico prezzo
     * @param Prezzo $prezzo
     */
    public function updatePrezzoArticolo(Prezzo $prezzo){
        return $this->DAO->updatePrezzo($prezzo);        
    }
    
    /**
     * Funzione che restituisce un array di tabelle articoli
     * @return array
     */
    public function getTabelleArticoli(){
        //Funzione che racchiude in un array tutte le tabelle coi prezzi
        $tabelle = array();
        //query sulle tabelle
        $result_tabelle = $this->DAO->getTabelle();
        foreach($result_tabelle as $temp_tabella){            
            
            $tabella = $this->getArrayTabella($temp_tabella);
                        
            //query sui prezzi         
            $tabella->setPrezzi($this->getArrayPrezzi($this->DAO->getPrezzi($tabella->getId())));
            array_push($tabelle, $tabella);
        }        
        return $tabelle;
    }
    
    /**
     * Funzione che ritorna un oggetto del database (non trasformato in oggetto Tabella per via della ajax call)
     * @param type $idTabella
     * @return type
     */
    public function getTabellaById($idTabella){
        return $this->DAO->getTabella($idTabella);
    }
    
    public function getNomeInfisso($idTabella){
        $tabella = $this->getTabellaById($idTabella);
        return $tabella->nome;        
    }
    
    
    /**
     * Funzione che trasforma un array in un oggetto tabella
     * @param type $array
     * @return \Tabella
     */
    private function getArrayTabella($array){
        $result = new Tabella();
        $result->setId($array->ID);
        $result->setNomeTabella($array->nome);
        $result->setStartRows($array->start_rows);
        $result->setEndRows($array->end_rows);
        $result->setStepRows($array->step_rows);
        $result->setStartCols($array->start_cols);
        $result->setEndCols($array->end_cols);
        $result->setStepCols($array->step_cols);
        $result->setAnte($array->ante);
        
        return $result;
    }
        
    private function getArrayPrezzi($array){
        $result = array();
        foreach($array as $item){
                $prezzo = new Prezzo();
                $prezzo->setIdTabella($item->id_tabella);
                $prezzo->setValRow($item->val_row);
                $prezzo->setValCol($item->val_col);
                $prezzo->setPrezzo($item->prezzo);
                array_push($result, $prezzo);
            }
        return $result;
    }
    
    /**
     * Funzione che ricevuto in ingresso l'ID della tabella, la elimina
     * @param type $idTabella
     * @return type
     */
    public function deleteTabellaPrezzi($idTabella){
        return $this->DAO->deleteTabellaPrezzi($idTabella);
    }
    
    /**
     * Funzione che restituisce tabelle in base ai parametri passati 
     * @return array
     */
    public function getTabelleByParameters($parameters){
        $string = "1 = 1";
        //controllo i parametri
        //tipo di infisso
        $string.= $this->getType($parameters);
        //numero di ante
        if(isset($parameters['ante'])){
            $string .= " AND ante = ".$parameters['ante'];
        }
               
        $tabelle = array();
        //ottengo le finesre        
        $tArray = $this->DAO->getTabelleByParameters($string);
        //le trasformo in tabelle
        foreach($tArray as $tItem){            
            array_push($tabelle, $tItem);
        }        
        return $tabelle;            
    }
    
    
    /** 
     * Funzione che restituisce il numero di ante sapendo l'infisso scelto
     * @param type $parameters
     * @return array
     */
    public function getAnte($parameters){        
        $ante = array();
        $string = "1 = 1";
        $string.= $this->getType($parameters);
        $array = $this->DAO->getAnte($string);
        
        foreach($array as $item){
            array_push($ante, $item);
        }        
        return $ante;
        
    }
    
    
    private function getType($parameters){
        $limite = 1800; //rappresenta il limite di altezza minimo che determina una finestra da una portafinestra        
        if(isset($parameters['type']) && $parameters['type'] == 'F'){
            return " AND start_rows < ".$limite;
        }
        else if((isset($parameters['type']) && $parameters['type'] == 'P')){
            return " AND start_rows >= ".$limite;
        }        
        return "";
    }
    
    /**
     * La funzione ritorna il prezzo, passati la tabella, riga e colonna
     * @param type $idTabella
     * @param type $row
     * @param type $col
     * @return type
     */
    public function getPrezzo($idTabella, $row, $col){
        return $this->DAO->getPrezzo($idTabella, $row, $col);
    }

}
