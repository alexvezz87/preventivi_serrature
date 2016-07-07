<?php

/**
 * Description of AgenteController
 *
 * @author Alex
 */
class AgenteController extends ClienteController {
    private $aDAO;
    
    function __construct() {
        parent::__construct();
        $this->aDAO = new AgenteDAO();
    }
    
    /**
     * La funzione salva un Agente con Indirizzo
     * @param Agente $a
     * @param Indirizzo $i
     * @return boolean
     */
    public function saveAgente(Agente $a, Indirizzo $i){
        //salvo il cliente
        $idCliente = parent::saveCliente($a, $i);
        if($idCliente != false){
            $a->setIdUtente($idCliente);
            return $this->aDAO->saveAgente($a);
        }
        return false;
    }
    
    /**
     * LA funzione restituisce vero o falso a seconda se l'id di un utente di Wordpress passato è Agente
     * @param type $idUserWP
     * @return boolean
     */
    public function isAgente($idUserWP){
        $idCliente = parent::getIdCliente($idUserWP);
        if($idCliente != null){
            return $this->aDAO->isAgente($idCliente);
        }
        return false;
    }
    
     /**
     * LA funzione restituisce un Agente se questo è presente nella tabella Agenti
     * @param type $idUserWP
     * @return type
     */
    public function getAgente($idUserWP){
        $idCliente = parent::getIdCliente($idUserWP);
        if($this->isAgente($idUserWP)){
            $result = new Agente();
            
            //prendo il cliente
            $cliente = new Cliente();
            $cliente = parent::getCliente($idCliente);
            
            $result = $this->aDAO->getAgente($idCliente);
            //faccio il merge tra cliente e agente
            $result->setCognome($cliente->getCognome());
            $result->setIdUserWP($cliente->getIdUserWP());
            $result->setNome($cliente->getNome());
            $result->setPi($cliente->getPi());
            $result->setTelefono($cliente->getTelefono());
            
            return $result;
        }
        return null;
    }
    
    public function getAgenti($users){
        $result = array();
        
        foreach($users as $u){
            $user = new WP_User();
            $user = $u;
            $temp = $this->getAgente($user->ID);
            if($temp != null){
                array_push($result, $temp);
            }
        }
        return $result;
    }
    
    /**
     * Vengono restituiti degli Agenti in base a parametri passati
     * @param type $parameters
     * @return array
     */
    public function getAgentiByParameters($parameters){
        $agenti = $this->aDAO->getIdAgentiByParameters($parameters);
        if($agenti != null && count($agenti) > 0){
            $result = array();
            foreach($agenti as $a){
                $temp = $this->getAgente($a->ID);
                if($temp != null){
                    array_push($result, $temp);
                }
            }
            return $result;
        }
        return null;
    }
    
    /**
     * Aggiorno cliente ed indirizzo
     * @param Agente $a
     * @param Indirizzo $i
     * @return boolean
     */
    public function updateAgente(Agente $a, Indirizzo $i){
        if($this->aDAO->updateAgente($a)){
            return parent::updateCliente($a, $i);
        }
        return false;
    }
    
    /**
     * Funzione che cancella un Agente
     * @param type $idUserWP
     * @return boolean
     */
    public function deleteAgente($idUserWP){
        //cancello prima l'agente
        $idCliente = parent::getIdCliente($idUserWP);
        if($this->aDAO->deleteAgente($idCliente)){
            //elimino il cliente
            return parent::deleteCliente($idUserWP);
        }
        return false;
    }

}
