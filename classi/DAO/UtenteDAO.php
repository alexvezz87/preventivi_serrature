<?php


/**
 * Description of UtenteDAO
 *
 * @author Alex
 */
class UtenteDAO {
    private $wpdb;
    private $table;
    
    function __construct() {       
        global $wpdb;
        $wpdb->prefix = 'pps_';
        $this->wpdb = $wpdb;
        $this->table = $wpdb->prefix.'utenti';        
    }
    
    /**
     * La funzione salva un oggetto utente nel database
     * @param Utente $u
     * @return boolean
     */
    public function saveUtente(Utente $u){
       try{
            $this->wpdb->insert(
                   $this->table,
                   array(
                       'id_user_wp' => $u->getIdUserWP(),
                       'pi' => $u->getPi(),
                       'telefono' => $u->getTelefono()
                   ),
                   array('%d', '%s', '%s')
                );
            return $this->wpdb->insert_id;           
           
       } catch (Exception $ex) {
            _e($ex);
            return false;
       } 
       
    }
    
    /**
     * La funzione restituisce un utente passando l'ID utente di Wordpress
     * @param type $idUserWP
     * @return boolean
     */
    public function getUtente($idUserWP){
        try{
            $query = "SELECT * FROM ".$this->table." WHERE id_user_wp = ".$idUserWP;
            $temp = $this->wpdb->get_row($query);
            if($temp != null){
                $utente = new Utente();
                $utente->setID($temp->ID);
                $utente->setIdUserWP($temp->id_user_wp);
                $utente->setPi($temp->pi);
                $utente->setTelefono($temp->telefono);
                
                return $utente;
            }
            return false;
            
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
    
    /**
     * La funzione restituisce un array di Utenti dati determinati parametri in ingresso
     * @param type $parameters
     * @return array
     */
    public function getUtentiByParameters($parameters){
        //I parametri da utilizzare sono:
        //1. piva --> Ricerca su partita iva
        //2. telefono --> Ricerca su numero di telefono
        try{
            $query = "SELECT * FROM ".$this->table." WHERE 1=1";
            foreach($parameters as $k => $v){
                if($k == 'piva' || $k == 'telefono'){
                    $query .= " AND ".$k." = '".$v."'";
                }
            }
            
            $temp = $this->wpdb->get_results($query);
            if($temp != null && count($temp) > 0){
                $result = array();
                foreach($temp as $t){
                    $u = new Utente();
                    $u->setID($t->ID);
                    $u->setIdUserWP($t->id_user_wp);
                    $u->setIndirizzo($t->indirizzo);
                    $u->setPi($t->pi);
                    $u->setTelefono($t->telefono);
                    
                    array_push($result, $u);
                }
                return $result;
            }
        } catch (Exception $ex) {
            _e($ex);
            return null;
        }
    }
    
    /**
     * La funzine restituisce l'ID di un utente passato l'ID utente di Wordpress
     * @param type $idUserWP
     * @return boolean
     */
    public function getIdUtente($idUserWP){
        try{
            $query = "SELECT ID FROM ".$this->table." WHERE id_user_wp = ".$idUserWP;
            return $this->wpdb->get_var($query);
        } catch (Exception $ex) {
            _e($ex);
            return null;
        }
    }
    
    /**
     * La funzione restituisce un ID utente di wordpress conoscendo l'id utente dell'anagrafica
     * @param type $idUtente
     * @return type
     */
    public function getIdUserWP($idUtente){
        try{
            $query = "SELECT id_user_wp FROM ".$this->table." WHERE ID = ".$idUtente;
            return $this->wpdb->get_var($query);
        } catch (Exception $ex) {
            _e($ex);
            return null;
        }
    }
    
      
    
    /**
     * La funzione elimina un utente dal DB passandogli l'ID 
     * @param type $idUserWP
     * @return boolean
     */
    public function deleteUtente($idUserWP){
        try{
            $this->wpdb->delete($this->table, array('id_user_wp' => $idUserWP));
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
    
    /**
     * La funzione aggiorna un utente nel DB
     * @param Utente $u
     * @return boolean
     */
    public function updateUtente(Utente $u){
        try{
            $this->wpdb->update(
                    $this->table,
                    array(
                        'pi' => $u->getPi(),
                        'telefono' => $u->getTelefono()
                    ),
                    array('id_user_wp' => $u->getIdUserWP()),
                    array('%s'),
                    array('%d')
                );
            return true;
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
        
    }
    

}
