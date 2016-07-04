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
    
    private $DAO;
    private $wpdb;
    private $table;
    
    function __construct() {
        global $wpdb;
        
        $wpdb->prefix = 'pps_';
        $this->wpdb = $wpdb;
        $this->table = $wpdb->prefix.'clienti';
        
        //Istanzio la classe DAO padre
        $this->DAO = new UtenteDAO($wpdb);
    }
    
    
    /**
     * La funzione salva un cliente nel DB
     * @param Cliente $c
     * @return boolean
     */
    public function saveCliente(Cliente $c){        
        //salvo prima l'utente
        $idUtente = $this->DAO->saveUtente($c);
        if($idUtente != false){
            //salvo il cliente
            try{
                $this->wpdb->insert(
                        $this->table,
                        array(
                            'id_utente' => $idUtente,
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
     * La funzione restituisce un Cliente passandogli l'ID utente di Wordpress
     * @param type $idUserWP
     * @return \Cliente|boolean
     */
    public function getCliente($idUserWP){
        //ottengo l'utente dalla classe padre
        $idUtente = $this->DAO->getIdUtente($idUserWP);
        if($idUtente != null){            
            $utente = new Utente();
            $utente = $this->DAO->getUtente($idUserWP);
            try{
                $query = "SELECT * FROM ".$this->table." WHERE id_utente = ".$idUtente;
                $tempCliente = $this->wpdb->get_row($query);
                
                //restituisco un oggetto cliente (mi piace poco perchè dovrebbe farla il controller)
                $cliente = new Cliente();
                $cliente->setCognome($tempCliente->cognome);
                $cliente->setNome($tempCliente->nome);
                $cliente->setID($tempCliente->ID);
                $cliente->setIdUserWP($utente->getIdUserWP());
                $cliente->setPi($utente->getPi());
                
                return $cliente;
            } catch (Exception $ex) {
                _e($ex);
                return null;
            }
        }        
        return null;
    }
    
    /**
     * La funzione cancella un Cliente dal DB
     * @param type $idUserWP
     * @return boolean
     */
    public function deleteCliente($idUserWP){
        //cancello prima il cliente ottenendo l'ID dall'utente
        $idUtente = $this->DAO->getIdUtente($idUserWP);
        if($idUtente != null){
            try{
                $this->wpdb->delete($this->table, array('id_utente' => $idUtente));
                //cancello l'utente
                $this->DAO->deleteUtente($idUserWP);
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
        //ottengo l'utente 
        $idUtente = $this->DAO->getIdUtente($c->getIdUserWP());
        if($idUtente != null){
            //aggiorno il cliente
            try{
                $this->wpdb->update(
                        $this->table,
                        array(
                            'nome' => $c->getNome(),
                            'cognome' => $c->getCognome()
                        ),
                        array('id_utente' => $idUtente),
                        array('%s', '%s'),
                        array('%d')
                    );
                //aggiorno l'utente
                $this->DAO->updateUtente($c);
                return true;
            } catch (Exception $ex) {
                _e($ex);
                return false;
            }
        }
        return false;
    }
    
    
}
