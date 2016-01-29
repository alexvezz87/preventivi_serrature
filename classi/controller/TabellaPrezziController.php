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
                    $this->DAO->savePrezziTabella($idTabella);
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
    public function getTabelleArticolo(){
        //Funzione che racchiude in un array tutte le tabelle coi prezzi
        $tabelle = array();
        //query sulle tabelle
        $result_tabelle = $this->DAO->getTabelle();
        foreach($result_tabelle as $temp_tabella){
            //creo l'oggetto tabella
            $tabella = new Tabella();
            $tabella->setId($temp_tabella->ID);
            $tabella->setNomeTabella($temp_tabella->nome);
            $tabella->setStartRows($temp_tabella->start_rows);
            $tabella->setEndRows($temp_tabella->end_rows);
            $tabella->setStepRows($temp_tabella->step_rows);
            $tabella->setStartCols($temp_tabella->start_cols);
            $tabella->setEndCols($temp_tabella->end_cols);
            $tabella->setStepCols($temp_tabella->step_cols);
                        
            //query sui prezzi         
            $tabella->setPrezzi($this->getArrayPrezzi($this->DAO->getPrezzi($tabella->getId())));
            array_push($tabelle, $tabella);
        }
        
        return $tabelle;
    }
    
    
    function getArrayPrezzi($array){
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

}
