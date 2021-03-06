<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PreventivoDAO
 *
 * @author Alex
 */
class PreventivoDAO {
    
    private $wpdb;
    private $table;
    
    function __construct() {
        global $wpdb;
        $wpdb->prefix = 'pps_';
        $this->wpdb = $wpdb;
        $this->table = $wpdb->prefix.'preventivi';
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
     * La funzione salva nel database un preventivo restituendo in caso di successo 
     * l'id del record nel database
     * @param Preventivo $p
     * @return type
     */
    public function savePreventivo(Preventivo $p){        
        try{
            //imposto il timezone
            date_default_timezone_set('Europe/Rome');
            $timestamp = date('Y-m-d H:i:s', strtotime("now")); 
            $this->wpdb->insert(
                    $this->table,
                    array(
                        'data' => $timestamp,
                        'id_utente' => $p->getIdUtente(),
                        'nome_rivenditore' => $p->getNomeRivenditore(),
                        'cliente_nome' => $p->getClienteNome(),
                        'cliente_via' => $p->getClienteVia(),
                        'cliente_tel' => $p->getClienteTel(),
                        'spesa_totale' => $p->getSpesaTotale(),
                        'visionato' => 0,
                        'note' => $p->getNote(),
                        'tipo' => $p->getTipo(),
                        'cliente_tipo' => $p->getClienteTipo(),
                        'cliente_email' => $p->getClienteEmail(),
                        'cliente_cf' => $p->getClienteCF(),
                        'codice_rivenditore' => $p->getCodiceRivenditore(),
                        'agente' => $p->getAgente(),
                        'sconto_rivenditore' => $p->getScontoRivenditore(),
                        'trasporto' => $p->getTrasporto()
                    ),
                    array('%s', '%d', '%s', '%s', '%s', '%s', '%f', '%d', '%s', '%d', '%s', '%s', '%s', '%s', '%s', '%f', '%f')
                );
            //restituisco l'id del record di preventivo inserito
            return $this->wpdb->insert_id;  
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
    
    /**
     * La funzione aggiorna lo stato del preventivo "da visionare" a "visionato"
     * cambiando un flag di un campo del database
     * @param type $id
     * @return boolean
     */
    public function setPreventivoVisionato($id){
        try{
            //imposto il timezone
            date_default_timezone_set('Europe/Rome');
            $timestamp = date('Y-m-d H:i:s', strtotime("now")); 
            $this->wpdb->update(
                    $this->table,
                    array('visionato' => 1, 'data_visionato' => $timestamp),
                    array('ID' => $id),
                    array('%d', '%s'),
                    array('%d')
                );
            return true;
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
    
    public function setPreventivoToOrdine($id){
        try{           
            
            $this->wpdb->update(
                    $this->table,
                    array('tipo' => 1),
                    array('ID' => $id),
                    array('%d'),
                    array('%d')
                );
            return true;
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
    
    /**
     * La funzione aggiorna il campo pdf nel database
     * @param type $id
     * @param type $pdf
     * @return boolean
     */
    public function setPDF($id, $pdf){
        try{
            $this->wpdb->update(
                    $this->table,
                    array('pdf' => $pdf),
                    array('ID' => $id),
                    array('%s'),
                    array('%d')
                );
            return true;
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
    
    public function updateSpesaTotale($id, $spesa){
        try{
            $this->wpdb->update(
                    $this->table,
                    array('spesa_totale' => $spesa),
                    array('ID' => $id),
                    array('%f'),
                    array('%d')
                );
            return true;
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
    
    /**
     * La funzione aggiorna i campi di un preventivo in modo da renderlo ordine
     * @param type $id
     * @param type $commessa
     * @param type $pdfOrdine
     * @return boolean
     */
    public function updatePreventivoToOrdine($id, $commessa, $pdfOrdine){
        try{
            $this->wpdb->update(
                    $this->table,
                    array(
                        'commessa' => $commessa,
                        'pdf_ordine' => $pdfOrdine
                    ),
                    array('ID' => $id),
                    array('%s', '%s'),
                    array('%d')
                );
            return true;
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
    
    public function updatePDF($id, $url){
        try{
            $this->wpdb->update(
                    $this->table,
                    array('pdf' => $url),
                    array('ID' => $id),
                    array('%s'),
                    array('%d')
                );
            return true;
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
       
    /**
     * La funzione passati dei parametri di ricerca di preventivi, restituisce i risultati corrispondenti
     * @param type $parameters
     * @return boolean
     */
    public function getPreventivi($parameters){
        //la funzione restituisce i preventivi che soddisfano determinati campi
        //id utente
        //visionato
        
        try{
            $query = 'SELECT * FROM '.$this->table.' WHERE 1=1';
            if(isset($parameters['id_utente'])){
                $query.= ' AND id_utente = '.$parameters['id_utente'];
            }
            if(isset($parameters['visionato'])){
                $query.= ' AND visionato = '.$parameters['visionato'];
            }   
            
            if(isset($parameters['tipo'])){
                $query.= ' AND tipo = '.$parameters['tipo'];
            }
            
            if(isset($parameters['order']) && isset($parameters['type-order'])){
                $query.= ' ORDER BY '.$parameters['order'].' '.$parameters['type-order'];
            }
            
            if(isset($parameters['limit'])){
                $query.= ' LIMIT '.$parameters['limit'];
            }
            //echo $query;
            return $this->wpdb->get_results($query);            
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }        
    }
    
    /**
     * La funzione ritorna un oggetto preventivo dal DB
     * @param type $idPreventivo
     * @return boolean
     */
    public function getPreventivo($idPreventivo){
        try{
            $query = 'SELECT * FROM '.$this->table.' WHERE ID = '.$idPreventivo;
            return $this->wpdb->get_row($query);
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
    
    /**
     * La funzione elimina un preventivo dal database
     * @param type $idPreventivo
     * @return boolean
     */
    public function deletePreventivo($idPreventivo){
        try{
            $this->wpdb->delete($this->table, array('ID' => $idPreventivo));
            return true;
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
    
    


}
