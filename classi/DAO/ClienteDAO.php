<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ClienteDAO
 *
 * @author Alex
 */
class ClienteDAO {    
    
    private $wpdb;
    private $table;
    private $fatherTable;
    
    function __construct() {
        global $wpdb;
        
        $wpdb->prefix = 'pps_';
        $this->wpdb = $wpdb;
        $this->table = $wpdb->prefix.'clienti';
        $this->fatherTable = $wpdb->prefix.'utenti';
        
    }
    
    
    /**
     * La funzione salva un cliente nel DB
     * @param Cliente $c
     * @return boolean
     */
    public function saveCliente(Cliente $c){       
        
        if($c->getIdUtente() != false){
            //salvo il cliente
            try{
                $this->wpdb->insert(
                        $this->table,
                        array(
                            'id_utente' => $c->getIdUtente(),
                            'nome' => $c->getNome(),
                            'cognome' => $c->getCognome()
                        ),
                        array('%d', '%s', '%s')
                    );
                return $this->wpdb->insert_id;
            } catch (Exception $ex) {
                _e($ex);
                return false;
            }
        }
        return false;
    }
    
    /**
     * La funzione restituisce un Cliente passandogli l'ID Utente
     * @param type $idUtente
     * @return \Cliente|boolean
     */
    public function getCliente($idUtente){
        //ottengo l'utente dalla classe padre        
        if($idUtente != null){ 
            try{
                $query = "SELECT * FROM ".$this->table." WHERE id_utente = ".$idUtente;
                $tempCliente = $this->wpdb->get_row($query);                
                //restituisco un oggetto cliente (mi piace poco perchè dovrebbe farla il controller)
                $cliente = new Cliente();
                $cliente->setCognome($tempCliente->cognome);
                $cliente->setNome($tempCliente->nome);
                $cliente->setID($tempCliente->ID);
                return $cliente;
            } catch (Exception $ex) {
                _e($ex);
                return null;
            }
        }        
        return null;
    }
   
    
    /**
     * La funzione controlla se un id Utente passato è nella tabella Clienti
     * @param type $idUtente
     * @return boolean
     */
    public function isCliente($idUtente){
        try{           
           if($idUtente != false && $idUtente != null){
               $query = "SELECT ID FROM ".$this->table." WHERE id_utente = ".$idUtente;
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
     * Funzione che restituise un array di ID Clienti
     * @param type $parameters
     * @return array
     */
    public function getIdClientiByParameters($parameters){
        //ottengo dei clienti attraverso determinati parametri
                
        //su clienti i parametri ricevuti possono essere
        //nome --> Ricerca per nome cliente
        //cognome --> Ricerca per cognome cliente
        try{                       
            $query = "SELECT ".$this->fatherTable.".id_user_wp as ID"
                    . "FROM ".$this->table." "
                    . "INNER JOIN ".$this->fatherTable." ON ".$this->fatherTable.".ID = ".$this->table.".id_utente "
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
     * La funzione cancella un Cliente dal DB
     * @param type $idUtente
     * @return boolean
     */
    public function deleteCliente($idUtente){
        //cancello prima il cliente ottenendo l'ID dall'utente        
        if($idUtente != null){
            try{
                $this->wpdb->delete($this->table, array('id_utente' => $idUtente));                                
                return true;                
            } catch (Exception $ex) {
                _e($ex);
                return false;
            }
        }
        return false;
    }

    /**
     * La funzione aggiorna un Cliente nel DB
     * @param Cliente $c
     * @return boolean
     */
    public function updateCliente(Cliente $c){
                 
        if($c->getIdUtente() != null){
            //aggiorno il cliente
            try{
                $this->wpdb->update(
                        $this->table,
                        array(
                            'nome' => $c->getNome(),
                            'cognome' => $c->getCognome()
                        ),
                        array('id_utente' => $c->getIdUtente()),
                        array('%s', '%s'),
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
    
    /**
     * La funzione restituisce l'ID conoscendo l'id_utente
     * @param type $idUtente
     * @return type
     */
    public function getIdCliente($idUtente){
        try{
            $query = "SELECT ID FROM ".$this->table." WHERE id_utente = ".$idUtente;
            return $this->wpdb->get_var($query);
        } catch (Exception $ex) {
            _e($ex);
            return null;
        }
    }
    
    
}
