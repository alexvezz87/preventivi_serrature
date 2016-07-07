<?php

/**
 * Description of Cliente
 *
 * @author Alex
 */
class Cliente extends Utente {
    private $nome;
    private $cognome;
    private $idUtente;
    
    function __construct() {
        parent::__construct();
    }
    
    public function getID() {
        return parent::getID();
    }

    public function getIdUserWP() {
        return parent::getIdUserWP();
    }

    public function getPi() {
        return parent::getPi();
    }

    public function setID($ID) {
        parent::setID($ID);
    }

    public function setIdUserWP($idUserWP) {
        parent::setIdUserWP($idUserWP);
    }

    public function setPi($pi) {
        parent::setPi($pi);
    }
    
    function getNome() {
        return $this->nome;
    }

    function getCognome() {
        return $this->cognome;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setCognome($cognome) {
        $this->cognome = $cognome;
    }

    public function getIndirizzo() {
        return parent::getIndirizzo();
    }

    public function setIndirizzo($indirizzo) {
        parent::setIndirizzo($indirizzo);
    }

    public function getTelefono() {
        return parent::getTelefono();
    }

    public function setTelefono($telefono) {
        parent::setTelefono($telefono);
    }

    function getIdUtente() {
        return $this->idUtente;
    }

    function setIdUtente($idUtente) {
        $this->idUtente = $idUtente;
    }




    
}
