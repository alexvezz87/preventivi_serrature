<?php


/**
 * Description of RivenditoreController
 *
 * @author Alex
 */
class RivenditoreController extends UtenteController {
    private $rDAO;
    private $raDAO;
    private $rcDAO;
    function __construct() {
        parent::__construct();
        $this->rDAO = new RivenditoreDAO();
        $this->raDAO = new RivenditoreAgenteDAO();
        $this->rcDAO = new RivenditoreClienteDAO();
    }
    
    /**
     * La fuznione salva un Rivenditore con Indirizzo
     * @param Rivenditore $r
     * @param Indirizzo $i
     * @return boolean
     */
    public function saveRivenditore(Rivenditore $r){
        //salvo utente
        $idUtente = parent::saveUtente($r);        
        if($idUtente != false){
            $r->setIdUtente($idUtente);
            return $this->rDAO->saveRivenditore($r);
        }
        return false;       
    }    
    
    /**
     * La funzione restituisce vero o falso a seconda se un utente di Wordpress è un Rivenditore
     * @param type $idUserWP
     * @return boolean
     */
    public function isRivenditore($idUserWP){
        $idUtente = parent::getIdUtente($idUserWP);
        if($idUtente != null){
            return $this->rDAO->isRivenditore($idUtente);
        }
        return false;
    }
    
    /**
     * La funzione restituisce un Rivenditore se questo è presnete nella tabella dei Rivenditori
     * @param type $idUserWP
     * @return type
     */
    public function getRivenditore($idUserWP){
        
        $idUtente = parent::getIdUtente($idUserWP);
        
        if($this->isRivenditore($idUserWP)){            
            $result = new Rivenditore();
            
            //prendo l'utente
            $utente = new Utente();
            $utente = parent::getUtente($idUserWP);
            
            $result = $this->rDAO->getRivenditore($idUtente);
            //faccio il merge tra utente e rivenditore
            $result->setIdUserWP($utente->getIdUserWP());
            $result->setIndirizzo($utente->getIndirizzo());
            $result->setPi($utente->getPi());
            $result->setTelefono($utente->getTelefono());
            
            //trovo gli agenti associati (sono un array di ID)
            $result->setAgenti($this->raDAO->getAgenti($result->getID()));
            
            //trovo tutti i clienti associati (sono array di ID)
            $result->setClienti($this->rcDAO->getClienti($result->getID()));
            
            return $result;
        }
        return null;
    }

    /**
     * La funzione resitituisce un array di Rivenditori ispezionando tutti gli utenti di Wordpress
     * @param type $users
     * @return array
     */
    public function getRivenditori(){
        
        //ottengo i rivenditori
        $args = array('role' => 'rivenditore');
        $users = get_users($args);
        
       
        
        $result = array();
        foreach($users as $u){
            $user = new WP_User();
            $user = $u;  
            $temp = $this->getRivenditore($user->ID);
            
            if($temp != null){
                array_push($result, $temp);
            }
        }
        
        return $result;
    }
    
    /**
     * Vengono restituiti dei Rivenditori in base a parametri passati
     * @param type $parameters
     * @return array
     */
    public function getRivenditoriByParameters($parameters){
        $rivenditori = $this->rDAO->getIdRivenditoriByParameters($parameters);
        if($rivenditori != null && count($rivenditori) > 0){
            $result = array();
            foreach($rivenditori as $r){
                $temp = $this->getRivenditore($r->ID);
                if($temp != null){
                    array_push($result, $temp);
                }
                return $result;
            }
        }
        return null;
    }
    
    /**
     * Aggiorno Rivenditore ed Indirizzo
     * @param Rivenditore $r
     * @param Indirizzo $i
     * @return boolean
     */
    public function updateRivenditore(Rivenditore $r){
        if($this->rDAO->updateRivenditore($r)){
            return parent::updateUtente($r);
        }
        return false;
    }
    
    /**
     * Elimino un Rivenditore
     * @param type $idUserWP
     * @return boolean
     */
    public function deleteRivenditore($idUserWP){
        //cancello prima il rivenditore
        $idUtente = parent::getIdUtente($idUserWP);
        //ottengo l'id rivenditore
        $idRivenditore = $this->rDAO->getIdRivenditore($idUtente);
        
        //elimino tutte le eventuali associazioni tra rivenditore e agenti
        $this->raDAO->deleteRivenditore($idRivenditore);
        
        //elimino tutte le eventuali associazioni tra rivenditori e clienti
        $this->rcDAO->deleteRivenditore($idRivenditore);
        
        if($this->rDAO->deleteRivenditore($idUtente)){
            //elimino l'utente
            return parent::deleteUtente($idUserWP);
        }
        return false;
    }
    
    /**
     * Associo un agente ad un rivenditore
     * @param type $idRivenditore
     * @param type $idAgente
     * @return type
     */ 
    public function associaAgente($idRivenditore, $idAgente){
        return $this->raDAO->saveRivenditoreAgente($idRivenditore, $idAgente);
    }
    
    /**
     * Toglie l'associazine tra rivenditore ed agente
     * @param type $idRivenditore
     * @param type $idAgente
     * @return type
     */
    public function rimuoviAgente($idRivenditore, $idAgente){
        return $this->raDAO->deleteRivenditoreAgente($idRivenditore, $idAgente);
    }
    
    /**
     * Toglie l'associazione tra rivenditore e cliente
     * @param type $idRivenditore
     * @param type $idCliente
     * @return type
     */
    public function rimuoviCliente($idRivenditore, $idCliente){
        return $this->rcDAO->deleteRivenditoreCliente($idRivenditore, $idCliente);
    }
            
       
}
