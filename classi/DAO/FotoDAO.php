<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FotoDAO
 *
 * @author Alex
 */
class FotoDAO {
    //put your code here
    private $wpdb;
    private $table;
    
    function __construct() {
        global $wpdb;
        $wpdb->prefix = 'pps_';
        $this->wpdb = $wpdb;
        $this->table = $wpdb->prefix.'foto';
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
     * La funzione salva una foto nel database
     * @param Foto $f
     * @return boolean
     */
    public function saveFoto(Foto $f){
        global $URL_IMG_PREVENTIVI; 
        global $URL_IMG_PREVENTIVI_THUMB;
        
        try{
            $this->wpdb->insert(
                    $this->table,
                    array(
                        'id_preventivo' => $f->getIdPreventivo(),
                        'nome_foto' => $f->getNomeFoto(),
                        'url_foto' => $URL_IMG_PREVENTIVI.$f->getNomeFoto(),
                        'url_thumb_foto' => $URL_IMG_PREVENTIVI_THUMB.$f->getNomeFoto()
                    ),
                    array('%d', '%s', '%s', '%s')
                );
            return $this->wpdb->insert_id;          
            
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
    
    /**
     * La funzione restituisce tutte le foto di un determinato preventivo (passato per ID)
     * @param type $idPreventivo
     * @return boolean
     */
    public function getFotoPreventivo($idPreventivo){
        try{
            $query = 'SELECT * FROM '.$this->table.' WHERE id_preventivo = '.$idPreventivo;
            return $this->wpdb->get_results($query);
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
    
    /**
     * La funzione elimina tutte le foto associate ad un preventivo
     * @param type $idPreventivo
     * @return boolean
     */
    public function deleteFotoPreventivo($idPreventivo){
        try{
            $this->wpdb->delete($this->table, array('id_preventivo' => $idPreventivo));
            return true;
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
    
    


}
