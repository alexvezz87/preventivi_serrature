<?php

/**
 * Description of ClienteController
 *
 * @author Alex
 */
class ClienteController extends UtenteController {
    private $cDAO;   
    
    function __construct() {       
        parent::__construct();
        $this->cDAO = new ClienteDAO();        
    }
    
    /**
     * La funzione salva un Cliente con Indirizzo
     * @param Cliente $c
     * @param Indirizzo $i
     * @return boolean
     */
    public function saveCliente(Cliente $c, Indirizzo $i){
        //salvo subito il cliente nel db
        $idUtente = parent::saveUtente($c, $i);
        if($idUtente != false){
            $c->setIdUtente($idUtente);
            return $this->cDAO->saveCliente($c);     
        }
        return false;               
    }
    
    /**
     * La funzione restituisce vero o falso a seconda se l'id di un utente Wordpress passato è un Cliente
     * @param type $idUserWP
     * @return boolean
     */
    public function isCliente($idUserWP){
        $idUtente = parent::getIdUtente($idUserWP);
        if($idUtente != null){
            return $this->cDAO->isCliente($idUtente);
        }
        return false;
    }
    
    /**
     * La funzione restituisce un cliente se questo è presente nella tabella Clienti
     * @param type $idUserWP
     * @return type
     */
    public function getCliente($idUserWP){
        
        $idUtente = parent::getIdUtente($idUserWP);
        if($this->isCliente($idUserWP)){
            $result = new Cliente();
            
            //prendo l'utente
            $utente = new Utente();
            $utente = parent::getUtente($idUserWP);            
            
            $result = $this->cDAO->getCliente($idUtente);
            //faccio il merge tra utente e cliente
            $result->setIdUserWP($utente->getIdUserWP());
            $result->setIndirizzo($utente->getIndirizzo());
            $result->setPi($utente->getPi());
            $result->setTelefono($utente->getTelefono());
            
            return $result;
        }
        return null;
    }
    
    /**
     * La funzione restituisce un array di clienti ispezionando tutti i gli Utenti di Wordpress
     * @param type $users
     * @return array
     */
    public function getClienti($users){
        $result = array();         
        
        foreach($users as $u){
            $user = new WP_User();
            $user = $u;            
            $temp = $this->getCliente($user->ID);
            
            if($temp != null){
                array_push($result, $temp);
            }
        }
        
        return $result;
    }
    
    /**
     * Vengono restituiti dei clienti in base a parametri passati
     * @param type $parameters
     * @return type
     */
    public function getClientiByParameters($parameters){
        $clienti = $this->cDAO->getClientiByParameters($parameters);
        if($clienti != null && count($clienti) > 0){
            $result = array();
            foreach($clienti as $c){
                $temp = $this->getCliente($c->ID);
                if($temp != null){
                    array_push($result, $temp);
                }
            }
            return $result;
        }        
        return null;
       
    }
    
    /**
     * Aggiorno Cliente ed Indirizzo
     * @param Cliente $c
     * @param Indirizzo $i
     * @return boolean
     */
    public function updateCliente(Cliente $c, Indirizzo $i){
        if($this->cDAO->updateCliente($c)){
            return parent::updateUtente($c, $i);
        }            
        return false;        
    }
    
    /**
     * Funzione che cancella un Cliente
     * @param type $idUserWP
     * @return boolean
     */
    public function deleteCliente($idUserWP){
        //cancello prima il cliente
        $idUtente = parent::getIdUtente($idUserWP);
        if($this->cDAO->deleteCliente($idUtente)){
            //elimino l'utente
            return parent::deleteUtente($idUserWP);
        }
        return false;
        
    }
    
    /**
     * La funzione restituisce l'ID cliente conoscendo l'ID dell'utente di Wordpress
     * @param type $idUserWP
     * @return type
     */
    public function getIdCliente($idUserWP){
       $idUtente = parent::getIdUtente($idUserWP);
       if($idUtente != null){
           return $this->cDAO->getIdCliente($idUtente);
       }
       return null;
       
    }
    
    
    
    
    
    
   
    
    
    

    
}
