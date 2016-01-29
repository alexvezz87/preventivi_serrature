<?php

/**
 * Description of Prezzo
 *
 * @author Alex
 */
class Prezzo {
    
    private $idTabella;
    private $valRow;
    private $valCol;
    private $prezzo;
    
    function __construct() {
        //costruttore vuoto
    }

    function getIdTabella() {
        return $this->idTabella;
    }

    function getValRow() {
        return $this->valRow;
    }

    function getValCol() {
        return $this->valCol;
    }

    function getPrezzo() {
        return $this->prezzo;
    }

    function setIdTabella($idTabella) {
        $this->idTabella = $idTabella;
    }

    function setValRow($valRow) {
        $this->valRow = $valRow;
    }

    function setValCol($valCol) {
        $this->valCol = $valCol;
    }

    function setPrezzo($prezzo) {
        $this->prezzo = $prezzo;
    }


    
}
