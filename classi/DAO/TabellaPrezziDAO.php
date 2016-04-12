<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TabellePrezzi
 *
 * @author Alex
 */
class TabellaPrezziDAO {
    
    private $wpdb;
    private $table_tabelle;
    private $table_prezzi;
    
    
    function __construct() {
        global $wpdb;
        $wpdb->prefix = 'pps_';
        $this->wpdb = $wpdb;     
        $this->table_tabelle = $wpdb->prefix.'tabelle';
        $this->table_prezzi = $wpdb->prefix.'prezzi';
    }
    
    function getWpdb() {
        return $this->wpdb;
    }

    function getTable_tabelle() {
        return $this->table_tabelle;
    }

    function getTable_prezzi() {
        return $this->table_prezzi;
    }

    function setWpdb($wpdb) {
        $this->wpdb = $wpdb;
    }

    function setTable_tabelle($table_tabelle) {
        $this->table_tabelle = $table_tabelle;
    }

    function setTable_prezzi($table_prezzi) {
        $this->table_prezzi = $table_prezzi;
    }

    /**
     * Funzione che salva una tabella di articolo all'interno del DB e in caso 
     * affermativo restituisce l'ID della tabella generato
     * @param Tabella $tab
     * @return type
     */
    public function saveTabellaArticolo(Tabella $tab){
        
        try{
            $this->wpdb->insert(
                        $this->table_tabelle,
                        array(
                            'nome' => $tab->getNomeTabella(),
                            'start_rows' => $tab->getStartRows(),
                            'end_rows' => $tab->getEndRows(),
                            'step_rows' => $tab->getStepRows(),
                            'start_cols' => $tab->getStartCols(),
                            'end_cols' => $tab->getEndCols(),
                            'step_cols' => $tab->getStepCols(),
                            'ante' => $tab->getAnte(),
                            'prezzo_iniziale' => $tab->getPrezzoIniziale(),
                            'incremento' => $tab->getIncremento()
                        ),
                        array('%s', '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%f', '%f')
                    );
            return $this->wpdb->insert_id;
            
        } catch (Exception $ex) {
            _e($ex);
            return -1;
        }
    } 
    
    /**
     * Funzione che genera una cella con il prezzo di una daterminata tabella
     * @param type $idTabella
     * @param Prezzo $prezzo
     * @return boolean
     */
    public function savePrezziTabella(Prezzo $prezzo){
        try{
            $this->wpdb->insert(
                        $this->table_prezzi,
                        array(
                            'id_tabella' => $prezzo->getIdTabella(),
                            'val_row' => $prezzo->getValRow(),
                            'val_col' => $prezzo->getValCol(),
                            'prezzo' => $prezzo->getPrezzo()
                        ),
                        array('%d', '%d', '%d', '%f')
                    );
            return true;
            
        } catch (Exception $ex) {
            _e($ex);
            return -1;
        }
    }
    
    /**
     * Funzione che aggiorna il valore di un prezzo nelle tabelle
     * @param type $idTabella
     * @param Prezzo $prezzo
     * @return boolean
     */
    public function updatePrezzo(Prezzo $prezzo){
        try{
            $this->wpdb->update(
                        $this->table_prezzi,
                        array('prezzo' => $prezzo->getPrezzo()),
                        array(
                            'id_tabella' => $prezzo->getIdTabella(),
                            'val_row' => $prezzo->getValRow(),
                            'val_col' => $prezzo->getValCol()
                        ),
                        array('%f'),
                        array('%d', '%d', '%d')
                    );
            return true;
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
    
    public function getTabelle(){
        try{
            $query = 'SELECT * FROM '.$this->table_tabelle.' ORDER BY ID DESC';
            return $this->wpdb->get_results($query);
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
    
    
    public function getTabella($idTabella){
        try{
            $query = "SELECT * FROM ".$this->table_tabelle.' WHERE ID = '.$idTabella;
            return $this->wpdb->get_row($query);
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
    
    public function getPrezzi($idTabella){
        try{
            $query = "SELECT * FROM ".$this->table_prezzi." WHERE id_tabella = ".$idTabella;
            return $this->wpdb->get_results($query);
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
    
    /**
     * La funzione restituisce il prezzo passati in ingresso una tabella, riga e colonna
     * @param type $idTabella
     * @param type $row
     * @param type $col
     * @return boolean
     */
    public function getPrezzo($idTabella, $row, $col){
        try{
            $query = "SELECT prezzo FROM ".$this->table_prezzi." WHERE id_tabella = ".$idTabella." AND val_row = ".$row." AND val_col = ".$col;
            return $this->wpdb->get_var($query);
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
    
    public function getRows($idTabella){
        try{
            $query = "SELECT DISTINCT val_row FROM ".$this->table_prezzi." WHERE id_tabella = ".$idTabella." ORDER BY val_row ASC";
            return $this->wpdb->get_col($query);
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
    
    public function getCols($idTabella){
        try{
            $query = "SELECT DISTINCT val_col FROM ".$this->table_prezzi." WHERE id_tabella = ".$idTabella." ORDER BY val_col ASC";
            return $this->wpdb->get_col($query);
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
    
    /**
     * Funzione che cancella tabella articolo e tabella prezzi dal database
     * @param type $idTabella
     * @return boolean
     */
    public function deleteTabellaPrezzi($idTabella){
        try{
            
            $this->wpdb->delete($this->table_prezzi, array('id_tabella' =>$idTabella));
            $this->wpdb->delete($this->table_tabelle, array('ID' => $idTabella));
            return true;
            
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
    
    /**
     * La funzione resituisce le tabelle che ritrovano riscontro nei parametri passati
     * @param type $value
     * @param type $operator
     * @return boolean
     */
    public function getTabelleByParameters($string_parameters){
        try{
            $query = "SELECT * FROM ".$this->table_tabelle.' WHERE '.$string_parameters;
            //echo $query;
            return $this->wpdb->get_results($query);
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
    
    /**
     * Funzione che restituisce il numero di ante a seconda di determinati parametri passati
     * @param type $string_parameters
     * @return boolean
     */
    public function getAnte($string_parameters){
        try{
            $query = "SELECT DISTINCT ante FROM ".$this->table_tabelle.' WHERE '.$string_parameters;
            
            return $this->wpdb->get_results($query);            
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
}
