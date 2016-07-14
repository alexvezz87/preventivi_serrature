<?php


/**
 * Description of TrasportoDAO
 *
 * @author Alex
 */
class TrasportoDAO {
    
    private $wpdb;
    private $table;
    
    function __construct() {
        global $wpdb;
        $wpdb->prefix = 'pps_';
        $this->wpdb = $wpdb;
        $this->table = $wpdb->prefix.'trasporti';
    }
    
    /**
     * La funzione salva un oggetto Trasporto nel DB
     * @param Trasporto $t
     * @return boolean
     */
    public function saveTrasporto(Trasporto $t){
        try{
            $this->wpdb->insert(
                    $this->table,
                    array(
                        'area' => $t->getArea(),
                        'prezzo' => $t->getPrezzo()
                    ),
                    array('%s', '%f')
                );
            return $this->wpdb->insert_id;
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
    
    /**
     * La funzione restituisce tutti i trasporti presenti nel DB
     * @return array
     */
    public function getTrasporti(){
        try{
            $query = "SELECT * FROM ".$this->table." ORDER BY ID ASC";
            $temp = $this->wpdb->get_results($query);
            if($temp != null && count($temp) > 0){
                $result = array();
                foreach($temp as $i){
                    $t = new Trasporto();
                    $t->setArea($i->area);
                    $t->setID($i->ID);
                    $t->setPrezzo($i->prezzo);
                    
                    array_push($result, $t);
                }
                return $result;
            }
            return null;
        } catch (Exception $ex) {
            _e($ex);
            return null;
        }
    }
    
    /**
     * La funzione restituisce un Trasporto passato per ID
     * @param type $idTrasporto
     * @return \Trasporto
     */
    public function getTrasporto($idTrasporto){
        try{
            $query = "SELECT * FROM ".$this->table." WHERE ID = ".$idTrasporto;
            $temp = $this->wpdb->get_row($query);
            if($temp != null){
                $result = new Trasporto();
                $result->setArea($temp->area);
                $result->setID($temp->ID);
                $result->setPrezzo($temp->prezzo);
                
                return $result;
            }
            return null;
        } catch (Exception $ex) {
            _e($ex);
            return null;
        }
    }
    
    /**
     * La funzione elimina un oggetto Trasporto dal DB
     * @param type $idTrasporto
     * @return boolean
     */
    public function deleteTrasporto($idTrasporto){
        try{
            return $this->wpdb->delete($this->table, array('ID' => $idTrasporto));            
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }

    /**
     * La funzione aggiorna un oggetto Trasporto nel DB
     * @param Trasporto $t
     * @return boolean
     */
    public function updateTrasporto(Trasporto $t){
        try{
            $this->wpdb->update(
                    $this->table,
                    array(
                        'area' => $t->getArea(),
                        'prezzo' => $t->getPrezzo()
                    ),
                    array('ID' => $t->getID()),
                    array('%s', '%f'),
                    array('%d')
                );
            return true;
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }
    }
}
