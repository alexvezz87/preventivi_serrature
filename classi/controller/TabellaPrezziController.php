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
            
            $prezzoIniziale = $tab->getPrezzoIniziale();
            $incremento = $tab->getIncremento();
            
            
            $numRow = 0;            
            for($rowCount = $tab->getStartRows(); $rowCount <= $tab->getEndRows(); $rowCount += $tab->getStepRows()){
                $numCol = 0;
                for($colCount = $tab->getStartCols(); $colCount <= $tab->getEndCols(); $colCount += $tab->getStepCols()){
                    $prezzo = new Prezzo();
                    $prezzo->setIdTabella($idTabella);
                    $prezzo->setValRow($rowCount);
                    $prezzo->setValCol($colCount);
                    $prezzoCella = floatval($prezzoIniziale + (($incremento * $prezzoIniziale )/ 100)*($numCol + $numRow));
                    $prezzoCella = ceil($prezzoCella);
                    $prezzo->setPrezzo($prezzoCella);
                    $this->DAO->savePrezziTabella($prezzo);
                    $numCol++;
                }
                $numRow++;
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
        
        //Il calcolo del prezzo diventa complesso nel caso vengano inseriti valori al di fuori dei campi
        //specificati nelle tabelle corrispondenti. In quel caso bisogna gestire i valori nei determinati casi
        
        //1. Controllo se i valori inseriti rispettano i valori presenti nel db
        $tabella = $this->getTabellaById($idTabella);
        
        if($row < $tabella->end_rows && $col < $tabella->end_cols){
            //2a in caso positivo continuo con il normale procedimento
            
            $rows = $this->DAO->getRows($idTabella);
            $cols = $this->DAO->getCols($idTabella);

            //devo controllare le misure di riga e colonna.
            //se eccedono devo passare alla misura successiva
            $r = $this->getValorePerEccesso($rows, $row);
            $c = $this->getValorePerEccesso($cols, $col);    
            return $this->DAO->getPrezzo($idTabella, $r, $c);
        }
        else{
            //2b in caso negativo devo quantificare di quanto sono uscito dai canoni normali e capire il prezzo
           
            //la funzione di calcolo è sempre questa --> prezzoIniziale + ((incremento*prezzoIniziale)/100)*(numCol + numRow)
            //diventa incognita il valore delle colonne e delle righe
            //ciò si misura dividendo per l'offset e arrotondando per eccesso
            $numCol = intval((intval($col)-intval($tabella->start_cols)) / intval($tabella->step_cols));
            if(intval($col) % intval($tabella->step_cols) != 0){
                $numCol++;
            }
            $numRow = intval((intval($row) - intval($tabella->start_rows) )/ intval($tabella->step_rows));
            if(intval($row) % intval($tabella->step_rows) != 0){
                $numRow++;
            }            
            
            //print_r($numCol.'-'.$numRow.' ');
            
            $prezzo = floatval($tabella->prezzo_iniziale + (($tabella->incremento * $tabella->prezzo_iniziale))/100 * ($numCol + $numRow));
            //arrotondo per eccesso
            $prezzo = ceil($prezzo);
            return number_format($prezzo,2);
        }
    }
    
    /**
     * La funzione preso un array ordinato e un valore, restituisce il valore se presente nel db, 
     * o il valore arrotondato per eccesso se non è presente nel db
     * @param type $array
     * @param type $item
     * @return type
     */
    private function getValorePerEccesso($array, $item){
        $same = false;
        $result = 0;
        $i=0;
        while($same == false && $i < count($array)){
            if($array[$i] == $item){
                $same = true;
                $result = $item;
            }
            else{
                //rows ha i valori ordinati in modo ascendente
                //il primo valore maggiore che becca row è per forza il valore per eccesso che cerco
                if($item < $array[$i]){
                    $result = $array[$i];
                    break;
                }
            }
            
            $i++;
        }
        
        return $result;
    }
    

}
