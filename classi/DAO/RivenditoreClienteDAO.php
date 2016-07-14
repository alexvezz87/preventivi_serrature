<?php

/**
 * La classe si impone di gestire i rapporti della relazione uno a molti tra rivenditore e cliente
 *
 * @author Alex
 */
class RivenditoreClienteDAO {
    
    private $wpdb;
    private $table;
    
    function __construct() {
        global $wpdb;
        $wpdb->prefix = 'pps_';
        $this->wpdb = $wpdb;
        $this->table = $wpdb->prefix.'rivenditori_clienti';
    }
    
    /**
     * La funzione salva un'associazione tra rivenditore e cliente
     * @param type $idRivenditore
     * @param type $idCliente
     * @return boolean
     */
    public function saveRivenditoreCliente($idRivenditore, $idCliente){
        try{
           $this->wpdb->insert(
                   $this->table,
                   array(
                       'id_rivenditore' => $idRivenditore,
                       'id_cliente' => $idCliente
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
     * La funzione elimina dal database un'associaizone rivenditore - cliente
     * @param type $idRivenditore
     * @param type $idCliente
     * @return boolean
     */
    public function deleteRivenditoreCliente($idRivenditore, $idCliente){
        try{
            $this->wpdb->delete(
                    $this->table,
                    array(
                        'id_rivenditore' => $idRivenditore,
                        'id_cliente' => $idCliente,
                    )
                );
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
     * La funzione elimina tutte i record con le occorrenze di un determinato cliente
     * @param type $idCliente
     * @return boolean
     */
    public function deleteCliente($idCliente){
        try{
            $this->wpdb->delete($this->table, array('id_cliente' => $idCliente));
            return true;
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
    
    /**
     * La funzione restituisce un array di ID cliente, passato un rivenditore
     * @param type $idRivenditore
     */
    public function getClienti($idRivenditore){
        try{
            $query = "SELECT id_cliente FROM ".$this->table." WHERE id_rivenditore = ".$idRivenditore;
            $temp = $this->wpdb->get_results($query);
            if($temp != null && count($temp) > 0){
                $ids = array();
                foreach($temp as $t){
                    array_push($ids, $t->id_cliente);
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
     * La funzione restituisce un array di tutti gli id dei rivenditori associati ad un cliente
     * @param type $idCliente
     * @return array
     */
    public function getRivenditori($idCliente){
        try{
            $query = "SELECT id_rivenditore FROM ".$this->table." WHERE id_cliente = ".$idCliente;
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
