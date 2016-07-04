<?php

/**
 * Description of AgenteDAO
 *
 * @author Alex
 */
class AgenteDAO {
    
    private $DAO;
    private $wpdb;
    private $table;
    
    
    function __construct() {
        global $wpdb;
        $wpdb->prefix = 'pps_';
        $this->wpdb = $wpdb;
        $this->table = $wpdb->prefix.'agenti';
        
        //Istanzio la classe DAO padre
        $this->DAO = new ClienteDAO();
    }
    
    /**
     * La funzione salva un agente nel DB
     * @param Agente $a
     * @return boolean
     */
    public function saveAgente(Agente $a){
        //salvo prima il cliente
        $idCliente = $this->DAO->saveCliente($a);
        if($idCliente != false){
            //salvo l'agente
            try{
                $this->wpdb->insert(
                        $this->table,
                        array(
                            'id_utente' => $idCliente,
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
     * La funzione restituisce un Agente, passandogli l'ID utente di Wordpress
     * @param type $idUserWP
     * @return \Agente
     */
    public function getAgente($idUserWP){
        //ottengo il cliente
        $cliente = new Cliente();
        $cliente = $this->DAO->getCliente($idUserWP);
        if($cliente != null){
            try{
                $query = "SELECT * FROM ".$this->table." WHERE id_utente = ".$cliente->getID();
                $tempAgente = $this->wpdb->get_row($query);
                
                //restituisco un oggetto Agente
                $agente = new Agente();
                $agente->setCognome($cliente->getCognome());
                $agente->setID($tempAgente->ID);
                $agente->setIdUserWP($cliente->getIdUserWP());
                $agente->setNome($cliente->getNome());
                $agente->setCognome($cliente->getCognome());
                $agente->setPi($cliente->getPi());
                $agente->setProvvigione($tempAgente->provvigione);
                
                return $agente;
                
            } catch (Exception $ex) {
                _e($ex);
                return null;
            }
        }
    }
    
    /**
     * La funzione cancella un Agente dal DB, passato l'ID utente di Wordpress
     * @param type $idUserWP
     * @return boolean
     */
    public function deleteAgente($idUserWP){
        //ottengo l'id cliente
        $cliente = new Cliente();
        $cliente = $this->DAO->getCliente($idUserWP);
        if($cliente != null){
            try{
                $this->wpdb->delete($this->table, array('id_utente' => $cliente->getID()));
                //cancello il cliente
                $this->DAO->deleteCliente($idUserWP);
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
        //ottengo il cliente
        $cliente = new Cliente();
        $cliente = $this->DAO->getCliente($a->getIdUserWP());
        if($cliente != null){
            try{
                $this->wpdb->update(
                        $this->table,
                        array('provvigione' => $a->getProvvigione()),
                        array('id_utente' => $cliente->getID()),
                        array('%f'),
                        array('%d')
                    );
                //aggiorno il cliente
                $this->DAO->updateCliente($a);
                return true;
            } catch (Exception $ex) {
                _e($ex);
                return false;
            }
        }
        return false;
    }
}
