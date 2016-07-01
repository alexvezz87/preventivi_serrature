<?php


/**
 * Description of Agente
 *
 * @author Alex
 */
class Agente extends Cliente {
    
    private $provvigione;
    
    public function __construct() {
        parent::__construct();
    }

    public function getCognome() {
        return parent::getCognome();
    }

    public function getID() {
        return parent::getID();
    }

    public function getIdUserWP() {
        return parent::getIdUserWP();
    }

    public function getNome() {
        return parent::getNome();
    }

    public function getPi() {
        return parent::getPi();
    }

    public function setCognome($cognome) {
        parent::setCognome($cognome);
    }

    public function setID($ID) {
        parent::setID($ID);
    }

    public function setIdUserWP($idUserWP) {
        parent::setIdUserWP($idUserWP);
    }

    public function setNome($nome) {
        parent::setNome($nome);
    }

    public function setPi($pi) {
        parent::setPi($pi);
    }
    
    function getProvvigione() {
        return $this->provvigione;
    }

    function setProvvigione($provvigione) {
        $this->provvigione = $provvigione;
    }

    public function getIndirizzo() {
        return parent::getIndirizzo();
    }

    public function setIndirizzo($indirizzo) {
        parent::setIndirizzo($indirizzo);
    }




}
