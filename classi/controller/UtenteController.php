<?php


/**
 * Description of UtenteController
 *
 * @author Alex
 */
class UtenteController {
    private $uDAO;
    private $iDAO;
    
    function __construct() {        
        $this->uDAO = new UtenteDAO();
        $this->iDAO = new IndirizzoDAO();
    }
    
    /**
     * La funzione salva un Utente ed un Indirizzo. Viene restituito l'ID dell'utente in caso di buona riuscita
     * @param Utente $u
     * @param Indirizzo $i
     * @return type
     */
    public function saveUtente(Utente $u, Indirizzo $i){
        //salvo l'utente e ne ottengo l'id
        $idUtente = $this->uDAO->saveUtente($u);
        if($idUtente != false && $i != null){
            //salvo l'indirizzo
            $idUserWP = $this->uDAO->getIdUserWP($idUtente);
            $i->setIdUtente($idUserWP);
            $this->iDAO->saveIndirizzo($i);
        }
        return $idUtente;        
    }
    
    /**
     * La funzione restituisce un oggetto Utente conoscendo l'ID Utente di Wordpress
     * @param type $idUserWP
     * @return type
     */
    public function getUtente($idUserWP){
        $utente = new Utente();
        $utente = $this->uDAO->getUtente($idUserWP);
        $param['id-utente'] = $idUserWP;        
        $utente->setIndirizzo($this->iDAO->getIndirizzo($param));
        
        return $utente;
    }
    
    /**
     * La funzione restituisce l'ID conoscendo l'ID utente di Wordpress
     * @param type $idUserWP
     * @return type
     */
    public function getIdUtente($idUserWP){
        return $this->uDAO->getIdUtente($idUserWP);
    }
    
    /**
     * La funzione aggiorna un Utente
     * @param Utente $u
     * @param Indirizzo $i
     * @return boolean
     */
    public function updateUtente(Utente $u, Indirizzo $i){
       //aggiorno utente
       if($this->uDAO->updateUtente($u)){
            if($i != null){
                return $this->iDAO->updateIndirizzo($i);
            }
            return true;
       }
       return false;
    }
    
    /**
     * La funzione cancella un utente e un possibile indirizzo associato
     * @param type $idUserWP
     * @return type
     */
    public function deleteUtente($idUserWP){
        //controllo se esiste un indirizzo associato all'utente
        if($this->iDAO->existsIndirizzo($idUserWP)){
            //cancello l'indirizzo
            $this->iDAO->deleteIndirizzo($idUserWP);
        }
        //cancello utente
        return $this->uDAO->deleteUtente($idUserWP);        
    }
    

}
