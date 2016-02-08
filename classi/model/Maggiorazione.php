<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Maggiorazione
 *
 * @author Alex
 */
class Maggiorazione {
    //put your code here
    private $nome;
    private $quantita;
    private $unitaMisura;
    private $ID;
    
    function __construct() {
        //costruttore vuoto
    }
    
    function getNome() {
        return $this->nome;
    }

    function getQuantita() {
        return $this->quantita;
    }

    function getUnitaMisura() {
        return $this->unitaMisura;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setQuantita($quantita) {
        $this->quantita = $quantita;
    }

    function setUnitaMisura($unitaMisura) {
        $this->unitaMisura = $unitaMisura;
    }

    function getID() {
        return $this->ID;
    }

    function setID($ID) {
        $this->ID = $ID;
    }



}
