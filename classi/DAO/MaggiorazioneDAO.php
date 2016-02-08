<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TabellaMaggiorazioniDAO
 *
 * @author Alex
 */
class MaggiorazioneDAO {
   
    private $wpdb;
    private $table;
    
    function __construct() {
        global $wpdb;
        $wpdb->prefix = 'pps_';
        $this->wpdb = $wpdb;
        $this->table = $wpdb->prefix.'maggiorazioni';
    }
    
    function getWpdb() {
        return $this->wpdb;
    }

    function getTable() {
        return $this->table;
    }

    function setWpdb($wpdb) {
        $this->wpdb = $wpdb;
    }

    function setTable($table) {
        $this->table = $table;
    }

    /**
     * Funzione che salva una maggiorazione nel DB
     * @param Maggiorazione $m
     * @return boolean
     */
    public function saveMaggiorazione(Maggiorazione $m){
        
        try{
            $this->wpdb->insert(
                    $this->table,
                    array(
                        'nome' => $m->getNome(),
                        'quantita' => $m->getQuantita(),
                        'unita_misura' => $m->getUnitaMisura()
                    ),
                    array('%s','%f','%s')
                );
            return true;
            
        } catch (Exception $ex) {
            _e($ex);
            return -1;
        }
    }
    
    /**
     * La funzione aggiorna una maggiorazione, passati i campi nuovi e l'id della maggiorazione da aggiornare
     * @param type $idM
     * @param Maggiorazione $m
     * @return boolean
     */
    public function updateMaggiorazione(Maggiorazione $m){
        try{
            $this->wpdb->update(
                    $this->table,
                    array(
                        'nome' => $m->getNome(),
                        'quantita' => $m->getQuantita(),
                        'unita_misura' => $m->getUnitaMisura()
                    ),
                    array('ID' => $m->getID()),
                    array('%s', '%f', '%d'),
                    array('%d')
                );
            return true;
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
    
    /**
     * Funzione che restituisce tutte le maggiorazioni
     * @return boolean
     */
    public function getMaggiorazioni(){
        try{
            $query = 'SELECT * FROM '.$this->table.' ORDER BY ID DESC';
            return $this->wpdb->get_results($query);
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
    
    /**
     * Funzione che elimina una maggiorazione dal database
     * @param type $idM
     * @return boolean
     */
    public function deleteMaggiorazione($idM){
        try{
            $this->wpdb->delete($this->table, array('ID' => $idM));
            return true;
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }

}
