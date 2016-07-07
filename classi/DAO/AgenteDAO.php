<?php

/**
 * Description of AgenteDAO
 *
 * @author Alex
 */
class AgenteDAO {    
    
    private $wpdb;
    private $table;
    private $fatherTable;
    private $grandFatherTable;
    
    function __construct() {
        global $wpdb;
        $wpdb->prefix = 'pps_';
        $this->wpdb = $wpdb;
        $this->table = $wpdb->prefix.'agenti';
        $this->fatherTable = $wpdb->prefix.'clienti';
        $this->grandFatherTable = $wpdb->prefix.'utenti';
    }
    
    /**
     * La funzione salva un agente nel DB
     * @param Agente $a
     * @return boolean
     */
    public function saveAgente(Agente $a){       
       
        if($a->getIdUtente() != false){
            //salvo l'agente
            try{
                $this->wpdb->insert(
                        $this->table,
                        array(
                            'id_utente' => $a->getIdUtente(),
                            'provvigione' => $a->getProvvigione()
                        ),
                        array('%d', '%f')
                    );
                return $this->wpdb->insert_id;
            } catch (Exception $ex) {
                _e($ex);
                return false;
            }
        }
    }
    
    /**
     * La funzione restituisce un Agente, passandogli l'ID Cliente
     * @param type $idCliente
     * @return \Agente
     */
    public function getAgente($idCliente){
        //ottengo il cliente
        
        if($idCliente != null){
            try{
                $query = "SELECT * FROM ".$this->table." WHERE id_utente = ".$idCliente;
                $tempAgente = $this->wpdb->get_row($query);
                
                //restituisco un oggetto Agente
                $agente = new Agente();                
                $agente->setID($tempAgente->ID);
                $agente->setProvvigione($tempAgente->provvigione);                
                
                return $agente;
                
            } catch (Exception $ex) {
                _e($ex);
                return null;
            }
        }
    }
    
    /**
     * LA funzione controlla se un id Cliente passato è nella tabella Agenti
     * @param type $idCliente
     * @return boolean
     */
    public function isAgente($idCliente){
        try{
            if($idCliente != false && $idCliente != null){
                $query = "SELECT ID FROM ".$this->table." WHERE id_utente = ".$idCliente;
                if($this->wpdb->get_var($query) != null){
                    return true;
                }
            }
            return false;
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
    
    /**
     * Funzione che restituisce un array di ID Agenti
     * @param type $parameters
     * @return type
     */
    public function getIdAgentiByParameters($parameters){
        //ottengo degli Agenti attraverso determinati parametri
        //su Agenti i parametri ricevuti possono essere
        //provvigione --> Ricerca per provvigione        
        try{
            $query = "SELECT ".$this->grandFatherTable.".id_user_wp AS ID"
                    . "FROM ".$this->table." "
                    . "INNER JOIN ".$this->fatherTable." ON ".$this->fatherTable.".ID = ".$this->table.".id_utente "
                    . "INNER JOIN ".$this->grandFatherTable." ON ".$this->grandFatherTable.".ID = ".$this->fatherTable.".id_utente "
                    . "WHERE 1=1";
            foreach($parameters as $k => $v){               
                $query .= " AND ".$k." LIKE '%".$v."%'";                
            }
            return $this->wpdb->get_results($query);
            
        } catch (Exception $ex) {
            _e($ex);
            return null;
        }
    }
    
    
    /**
     * La funzione cancella un Agente dal DB, passato l'ID Cliente
     * @param type $idCliente
     * @return boolean
     */
    public function deleteAgente($idCliente){       
        if($idCliente != null){
            try{
                $this->wpdb->delete($this->table, array('id_utente' => $idCliente));
                
                return true;
            } catch (Exception $ex) {
                _e($ex);
                return false;
            }
        }
        return false;
    }

    /**
     * La funzione aggiorna un Agente nel DB
     * @param Agente $a
     * @return boolean
     */
    public function updateAgente(Agente $a){        
       
        if($a->getIdUtente() != null){
            try{
                $this->wpdb->update(
                        $this->table,
                        array('provvigione' => $a->getProvvigione()),
                        array('id_utente' => $a->getIdUtente()),
                        array('%f'),
                        array('%d')
                    );                
                return true;
            } catch (Exception $ex) {
                _e($ex);
                return false;
            }
        }
        return false;
    }
}
