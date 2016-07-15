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
    public function saveUtente(Utente $u){
        //salvo l'utente e ne ottengo l'id
        $idUtente = $this->uDAO->saveUtente($u);
        if($idUtente != false && $u->getIndirizzo() != null){            
            //salvo l'indirizzo
            //l'indirizzo ha come riferimento l'id utente appena creato
            $i = $u->getIndirizzo();
            $i->setIdUtente($idUtente);
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
        
        //l'indirizzo è relazionato all'id utente appena ottenuto
        $param['id_utente'] = $utente->getID();        
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
    public function updateUtente(Utente $u){
       //aggiorno utente
       //imposto l'idUtente
       $u->setID($this->uDAO->getIdUtente($u->getIdUserWP()));          
        
       if($this->uDAO->updateUtente($u)){
            if($u->getIndirizzo() != null){
                
                //quando aggiorno l'utente con un indirizzo, può capitare che l'indirizzo non sia presente nel database
                if($this->iDAO->existsIndirizzo($u->getID())){                    
                    //nel caso l'indirizzo esista allora aggiorno 
                    $i = new Indirizzo();
                    $i = $u->getIndirizzo();
                    $i->setIdUtente($u->getID());
                    return $this->iDAO->updateIndirizzo($u->getIndirizzo());
                }
                else{
                    //altrimenti devo aggiungerne uno nuovo
                    $i = new Indirizzo();
                    $i = $u->getIndirizzo();
                    $i->setIdUtente($u->getID());
                    
                    return $this->iDAO->saveIndirizzo($i);                    
                }
            }
            else{
                //Se l'indirizzo non c'è guardo se esiste e in quel caso lo elimino
                if($this->iDAO->existsIndirizzo($u->getID())){
                    //elimino l'indirizzo
                    $this->iDAO->deleteIndirizzo($u->getID());
                }
                
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
       
        $idUtente = $this->uDAO->getIdUtente($idUserWP);
        
        //cancello indirizzo
        //controllo se esiste un indirizzo associato all'utente   
        if($this->iDAO->existsIndirizzo($idUtente)){
            //cancello l'indirizzo
            $this->iDAO->deleteIndirizzo($idUtente);
        }
        
        //cancello utente
        if($this->uDAO->deleteUtente($idUserWP)!= false){
          
            //cancello l'utenza di wordpress
            return wp_delete_user($idUserWP);            
        } 
        
        return false;
    }
    

}
