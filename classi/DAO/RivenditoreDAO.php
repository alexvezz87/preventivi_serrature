<?php


/**
 * Description of RivenditoreDAO
 *
 * @author Alex
 */
class RivenditoreDAO {
    
    private $DAO;
    private $wpdb;
    private $table;
    
    function __construct() {
        global $wpdb;
        
        $wpdb->prefix = 'pps_';
        $this->wpdb = $wpdb;
        $this->table = $wpdb->prefix.'rivenditori';
        
        //Istanzio la classe DAO padre
        $this->DAO = new UtenteDAO($wpdb);
    }

    /**
     * La funzione salva un rivenditore nel database
     * @param Rivenditore $r
     * @return boolean
     */
    public function saveRivenditore(Rivenditore $r){
        //salvo prima l'utente
        $idUtente = $this->DAO->saveUtente($r);
        if($idUtente != false){
            //salvo il rivenditore
            try{
                $this->wpdb->insert(
                        $this->table,
                        array(
                            'id_utente' => $idUtente,
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
     * @param type $idUserWP
     * @return \Rivenditore
     */
    public function getRivenditore($idUserWP){
        //ottengo l'utente dalla classe padre
        $idUtente = $this->DAO->getIdUtente($idUserWP);
        if($idUtente != null){
            $utente = new Utente();
            $utente = $this->DAO->getUtente($idUserWP);
            try{
                $query = "SELECT * FROM ".$this->table." WHERE id_utente = ".$idUtente;
                $tempR = $this->wpdb->get_row($query);
                
                //restituisco un oggetto rivenditore
                $r = new Rivenditore();
                $r->setCodice($tempR->codice);
                $r->setCondizioniVendita($tempR->con_ven);
                $r->setID($tempR->ID);
                $r->setIdUserWP($utente->getIdUserWP());
                $r->setNominativo($tempR->nominativo);
                $r->setPagamento($tempR->pagamento);
                $r->setPi($utente->getPi());
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
     * La funzione cancella un Rivenditore dal DB
     * @param type $idUserWP
     * @return boolean
     */
    public function deleteRivenditore($idUserWP){
        //cancello prima il rivenditore 
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
     * La funzione aggiorna un rivenditore nel DB
     * @param Rivenditore $r
     * @return boolean
     */
    public function updateRivenditore(Rivenditore $r){
        //ottengo l'utente
        $idUtente = $this->DAO->getIdUtente($r->getIdUserWP());
        if($idUtente != null){
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
                        array('id_utente' => $idUtente),
                        array('%s', '%f', '%s', '%s', '%s', '%s'),
                        array('%d')
                    );
                //aggiorno utente
                $this->DAO->updateUtente($r);
                return true;
            } catch (Exception $ex) {
                _e($ex);
                return false;
            }
        }
        return false;
    }
    
}
