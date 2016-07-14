<?php

/**
 * La classe si impone di gestire i rapporti della relazione uno a molti tra rivenditore ed agente
 *
 * @author Alex
 */
class RivenditoreAgenteDAO {
    
    private $wpdb;
    private $table;
    
    function __construct() {
        global $wpdb;
        $wpdb->prefix = 'pps_';
        $this->wpdb = $wpdb;
        $this->table = $wpdb->prefix.'rivenditori_agenti';
    }
    
    /**
     * La funzione salva un'associazione tra rivenditore ed agente
     * @param type $idRivenditore
     * @param type $idAgente
     * @return boolean
     */
    public function saveRivenditoreAgente($idRivenditore, $idAgente){
        try{
            $this->wpdb->insert(
                    $this->table,
                    array(
                        'id_riveditore' => $idRivenditore,
                        'id_agente' => $idAgente
                    ),
                    array('%d', '%d')
                );
            return $this->wpdb->insert_id;
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
    
    /**
     * La funzione cancella dal database un'associazione rivenditore agente
     * @param type $idRivenditore
     * @param type $idAgente
     * @return boolean
     */
    public function deleteRivenditoreAgente($idRivenditore, $idAgente){
        try{
            $this->wpdb->delete(
                    $this->table,
                    array(
                        'id_rivenditore' => $idRivenditore,
                        'id_agente' => $idAgente
                    )
                );
            return true;
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    } 
    
    /**
     * La funzione elimina tutte i record con le occorrenze di un determinato rivenditore
     * @param type $idRivenditore
     * @return boolean
     */
    public function deleteRivenditore($idRivenditore){
        try{
            return $this->wpdb->delete($this->table, array('id_rivenditore' => $idRivenditore));            
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
    
    /**
     * La funzione elimina tutte i record con le occorrenze di un determinato agente
     * @param type $idAgente
     * @return boolean
     */
    public function deleteAgente($idAgente){
        try{
            $this->wpdb->delete($this->table, array('id_agente' => $idAgente));
            return true;
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
    
    /**
     * La funzione restituisce un array di id agente passato un rivenditore
     * @param type $idRivenditore
     * @return array
     */
    public function getAgenti($idRivenditore){
        try{
            $query = "SELECT id_agente FROM ".$this->table." WHERE id_rivenditore = ".$idRivenditore;
            $temp = $this->wpdb->get_results($query);
            if($temp != null && count($temp) > 0){
                $ids = array();
                foreach($temp as $t){
                    array_push($ids, $t->id_agente);
                }
                return $ids;
            }
            return null;
        } catch (Exception $ex) {
            _e($ex);
            return null;
        }
    }
    
    /**
     * La funzione restituisce un array di id di tutti i rivenditori associati ad un agente
     * @param type $idAgente
     * @return array
     */
    public function getRivenditori($idAgente){
        try{
            $query = "SELECT id_rivenditore FROM ".$this->table." WHERE id_agente = ".$idAgente;
            $temp = $this->wpdb->get_results($query);
            if($temp != null && count($temp) > 0){
                $ids = array();
                foreach($temp as $t){
                    array_push($ids, $t->id_rivenditore);
                }
                return $ids;
            }
            return null;
        } catch (Exception $ex) {
            _e($ex);
            return null;
        }
    }

}
