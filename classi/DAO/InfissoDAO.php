<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of InfissoDAO
 *
 * @author Alex
 */
class InfissoDAO {
    
    private $wpdb;
    private $table;
    
    function __construct() {
        global $wpdb;
        $wpdb->prefix = 'pps_';
        $this->wpdb = $wpdb;
        $this->table = $wpdb->prefix.'infissi';
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
     * La funzione salva all'interno del database un oggetto Infisso
     * @param Infisso $i
     * @return boolean
     */
    public function saveInfisso(Infisso $i){
        
        try{
            $this->wpdb->insert(
                    $this->table,
                    array(
                        'id_preventivo' => $i->getIdPreventivo(),
                        'tipo' => $i->getTipo(),
                        'n_ante' => $i->getNAnte(),
                        'id_infisso' => $i->getIdInfisso(),
                        'altezza' => $i->getAltezza(),
                        'lunghezza' => $i->getLunghezza(),
                        'apertura' => $i->getApertura(),
                        'barra' => $i->getBarra(),
                        'serratura' => $i->getSerratura(),
                        'nodo' => $i->getNodo(),
                        'colore' => $i->getColore(),
                        'cerniera' => $i->getCerniera(),
                        'n_infisso' => $i->getNInfisso(),
                        'spesa_infisso' => $i->getSpesaInfisso(),
                        'anta_principale' => $i->getAntaPrincipale(),
                        'posizione_serratura' => $i->getPosizioneSerratura()
                    ),
                    array('%d', '%s', '%d', '%d', '%d', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%f', '%s', '%s')
                );
            return $this->wpdb->insert_id;
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
    
    /**
     * La funzione restituisce tutti gli inifissi associati ad un determinato preventivo
     * @param type $idPreventivo
     * @return boolean
     */
    public function getInfissi($idPreventivo){
        try{
            $query = 'SELECT * FROM '.$this->table.' WHERE id_preventivo = '.$idPreventivo;
            return $this->wpdb->get_results($query);
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
    
    public function getIdInfissi($idPreventivo){
        try{
            $query = 'SELECT ID FROM '.$this->table.' WHERE id_preventivo = '.$idPreventivo;
            return $this->wpdb->get_results($query);
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
   
    /**
     * La funzione elimina dal database tutti gli infissi associati ad un determinato preventivo
     * @param type $idPreventivo
     * @return boolean
     */
    public function deleteInfissi($idPreventivo){
        try{
            $this->wpdb->delete($this->table, array('id_preventivo' => $idPreventivo));
            return true;
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }

}
