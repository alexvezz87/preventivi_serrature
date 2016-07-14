<?php


/**
 * Description of RivenditoreDAO
 *
 * @author Alex
 */
class RivenditoreDAO {    
   
    private $wpdb;
    private $table;
    private $fatherTable;
    
    function __construct() {
        global $wpdb;
        
        $wpdb->prefix = 'pps_';
        $this->wpdb = $wpdb;
        $this->table = $wpdb->prefix.'rivenditori';
        $this->fatherTable = $wpdb->prefix.'utenti';
        
        //Istanzio la classe DAO padre
        $this->DAO = new UtenteDAO($wpdb);
    }

    /**
     * La funzione salva un rivenditore nel database
     * @param Rivenditore $r
     * @return boolean
     */
    public function saveRivenditore(Rivenditore $r){
               
        if( $r->getIdUtente() != false){
            //salvo il rivenditore
            try{
                $this->wpdb->insert(
                        $this->table,
                        array(
                            'id_utente' => $r->getIdUtente(),
                            'nominativo' => $r->getNominativo(),
                            'sconto' => $r->getSconto(),
                            'codice' => $r->getCodice(),
                            'con_ven' => $r->getCondizioniVendita(),
                            'pagamento' => $r->getPagamento(),
                            'trasporto' => $r->getTrasporto()
                        ),
                        array('%d', '%s', '%f', '%s', '%s', '%s', '%s', '%d')
                    );
                return $this->wpdb->insert_id;
            } catch (Exception $ex) {
                _e($ex);
                return false;
            }
        }
    }
    
    /**
     * La funzione restituisce un Rivenditore passandogli l'ID utente di Wordpress
     * @param type $idUtente
     * @return \Rivenditore
     */
    public function getRivenditore($idUtente){
               
        if($idUtente != null){           
            try{
                $query = "SELECT * FROM ".$this->table." WHERE id_utente = ".$idUtente;
                $tempR = $this->wpdb->get_row($query);
                
                //restituisco un oggetto rivenditore
                $r = new Rivenditore();
                $r->setCodice($tempR->codice);
                $r->setCondizioniVendita($tempR->con_ven);
                $r->setID($tempR->ID);                
                $r->setNominativo($tempR->nominativo);
                $r->setPagamento($tempR->pagamento);                
                $r->setSconto($tempR->sconto);
                $r->setTrasporto($tempR->trasporto);
                return $r;
            } catch (Exception $ex) {
                _e($ex);
                return null;
            }
        }
        return null;
    }
    
    /**
     * La funzione controlla se un id Utente passato è nella tabella Rivenditori
     * @param type $idUtente
     * @return boolean
     */
    public function isRivenditore($idUtente){
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
     * La funzione restituisce l'ID rivenditore conoscendo l'id utente
     * @param type $idUtente
     * @return type
     */
    public function getIdRivenditore($idUtente){
        try{
            if($idUtente != false && $idUtente != null){
                $query = "SELECT ID FROM ".$this->table." WHERE id_utente = ".$idUtente;
                return $this->wpdb->get_var($query);                 
            }
            return null;
        } catch (Exception $ex) {
            _e($ex);
            return null;
        }
    }
    
    /**
     * La funzione restituisce un array di ID Rivenditori
     * @param type $parameters
     * @return type
     */
    public function getIdRivenditoriByParameters($parameters){
        //ottengo dei Rivenditori attraverso determinati parametri
        
        //parametri per rivenditori:
        //nominativo --> Ricerca per nominativo
	//sconto --> Ricerca per sconto
	//codice --> Ricerca per codice Rivenditore
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
     * La funzione cancella un Rivenditore dal DB
     * @param type $idUtente
     * @return boolean
     */
    public function deleteRivenditore($idUtente){
        //cancello prima il rivenditore 
        
        if($idUtente != null){
            try{
                return $this->wpdb->delete($this->table, array('id_utente' => $idUtente)); 
            } catch (Exception $ex) {
                _e($ex);
                return false;
            }
        }
        return false;
    }
    
    /**
     * La funzione aggiorna un rivenditore nel DB
     * @param Rivenditore $r
     * @return boolean
     */
    public function updateRivenditore(Rivenditore $r){      
       
        if($r->getIdUtente() != null){
            //aggiorno il rivenditore
            try{
                $this->wpdb->update(
                        $this->table,
                        array(
                            'nominativo' => $r->getNominativo(),
                            'sconto' => $r->getSconto(),
                            'codice' => $r->getCodice(),
                            'con_ven' => $r->getCondizioniVendita(),
                            'pagamento' => $r->getPagamento(),
                            'trasporto' => $r->getTrasporto()
                        ),
                        array('id_utente' => $r->getIdUtente()),
                        array('%s', '%f', '%s', '%s', '%s', '%s'),
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
