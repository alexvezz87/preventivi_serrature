<?php


/**
 * Description of UtenteDAO
 *
 * @author Alex
 */
class UtenteDAO {
    private $wpdb;
    private $table;
    
    function __construct($wpdb) {       
        
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
                       'pi' => $u->getPi()
                   ),
                   array('%d', '%s')
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
                return $utente;
            }
            return false;
            
        } catch (Exception $ex) {
            _e($ex);
            return false;
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
            return false;
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
                    array('pi' => $u->getPi()),
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
