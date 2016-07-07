<?php

/**
 * Description of Rivenditore
 *
 * @author Alex
 */
class Rivenditore extends Utente {
    private $nominativo;
    private $sconto;
    private $codice;
    private $condizioniVendita;
    private $pagamento;
    private $trasporto;
    private $idUtente;
    
    public function __construct() {
        parent::__construct();
    }

    public function getID() {
        return parent::getID();
    }

    public function getIdUserWP() {
        return parent::getIdUserWP();
    }

    public function getIndirizzo() {
        return parent::getIndirizzo();
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

    public function setIndirizzo($indirizzo) {
        parent::setIndirizzo($indirizzo);
    }

    public function setPi($pi) {
        parent::setPi($pi);
    }
    
    function getNominativo() {
        return $this->nominativo;
    }

    function getSconto() {
        return $this->sconto;
    }

    function getCodice() {
        return $this->codice;
    }

    function getCondizioniVendita() {
        return $this->condizioniVendita;
    }

    function getPagamento() {
        return $this->pagamento;
    }

    function setNominativo($nominativo) {
        $this->nominativo = $nominativo;
    }

    function setSconto($sconto) {
        $this->sconto = $sconto;
    }

    function setCodice($codice) {
        $this->codice = $codice;
    }

    function setCondizioniVendita($condizioniVendita) {
        $this->condizioniVendita = $condizioniVendita;
    }

    function setPagamento($pagamento) {
        $this->pagamento = $pagamento;
    }

    function getTrasporto() {
        return $this->trasporto;
    }

    function setTrasporto($trasporto) {
        $this->trasporto = $trasporto;
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
