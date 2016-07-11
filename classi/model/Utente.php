'<?php

/**
 * Description of Utente
 *
 * @author Alex
 */
class Utente {
    private $ID;
    private $idUserWP;
    private $pi;
    private $indirizzo;
    private $telefono;
    
    
    function __construct() {
        
    }
    
    function getID() {
        return $this->ID;
    }

    function getIdUserWP() {
        return $this->idUserWP;
    }

    function getPi() {
        return $this->pi;
    }

    function setID($ID) {
        $this->ID = $ID;
    }

    function setIdUserWP($idUserWP) {
        $this->idUserWP = $idUserWP;
    }

    function setPi($pi) {
        $this->pi = $pi;
    }
    
    function getIndirizzo() {
        return $this->indirizzo;
    }

    function setIndirizzo($indirizzo) {
        $this->indirizzo = $indirizzo;
    }


    function getTelefono() {
        return $this->telefono;
    }

    function setTelefono($telefono) {
        $this->telefono = $telefono;
    }


}
