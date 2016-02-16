<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Questa classe ha lo scopo di interconnettere la realzione tra Maggiorazione e Infisso
 *
 * @author Alex
 */
class InfissoMaggiorazioneDAO {
    
    private $wpdb;
    private $table;
    
    function __construct() {
        global $wpdb;
        $wpdb->prefix = 'pps_';
        $this->wpdb = $wpdb;
        $this->table = $wpdb->prefix.'infissi_maggiorazioni';
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

    
    public function saveInfissoMaggiorazione($idInfisso, $idMaggiorazione){
        try{
            $this->wpdb->insert(
                    $this->table,
                    array(
                        'id_infisso' => $idInfisso,
                        'id_maggiorazione' => $idMaggiorazione
                    ),
                    array('%d', '%d')
                );
            return true;
            
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
    
    /**
     * La funzione restituisce gli ID delle maggiorazioni associate ad un infisso
     * @param type $idInfisso
     * @return boolean
     */
    public function getIdMaggiorazione($idInfisso){
        try{
            $query = 'SELECT id_maggiorazione FROM '.$this->table.' WHERE id_infisso = '.$idInfisso;
            return $this->wpdb->get_results($query);
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
    
    /**
     * La funzione elimina dal database le maggiorazioni associate ad un infisso
     * @param type $idInfisso
     * @return boolean
     */
    public function deleteMaggiorazioni($idInfisso){
        try{
            $this->wpdb->delete($this->table, array('id_infisso' => $idInfisso));
            return true;
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
    
    

    
}
