<?php

/**
 * Description of class
 *
 * @author Alex
 */
class Tabella {
    
    private $nomeTabella;
    private $startRows;
    private $endRows;
    private $stepRows;
    private $startCols;
    private $endCols;
    private $stepCols;
    private $id;
    private $prezzi;
    private $ante;
    private $prezzoIniziale;
    private $incremento;
    
    function __construct() {
        
    }
    
    //metodi
    
    function getNomeTabella() {
        return $this->nomeTabella;
    }

    function getStartRows() {
        return $this->startRows;
    }

    function getEndRows() {
        return $this->endRows;
    }

    function getStepRows() {
        return $this->stepRows;
    }

    function getStartCols() {
        return $this->startCols;
    }

    function getEndCols() {
        return $this->endCols;
    }

    function getStepCols() {
        return $this->stepCols;
    }

    function setNomeTabella($nomeTabella) {
        $this->nomeTabella = $nomeTabella;
    }

    function setStartRows($startRows) {
        $this->startRows = $startRows;
    }

    function setEndRows($endRows) {
        $this->endRows = $endRows;
    }

    function setStepRows($stepRows) {
        $this->stepRows = $stepRows;
    }

    function setStartCols($startCols) {
        $this->startCols = $startCols;
    }

    function setEndCols($endCols) {
        $this->endCols = $endCols;
    }

    function setStepCols($stepCols) {
        $this->stepCols = $stepCols;
    }

    function getId() {
        return $this->id;
    }

    function setId($id) {
        $this->id = $id;
    }

    function getPrezzi() {
        return $this->prezzi;
    }

    function setPrezzi($prezzi) {
        $this->prezzi = $prezzi;
    }

    function getAnte() {
        return $this->ante;
    }

    function setAnte($ante) {
        $this->ante = $ante;
    }

    function getPrezzoIniziale() {
        return $this->prezzoIniziale;
    }

    function getIncremento() {
        return $this->incremento;
    }

    function setPrezzoIniziale($prezzoIniziale) {
        $this->prezzoIniziale = $prezzoIniziale;
    }

    function setIncremento($incremento) {
        $this->incremento = $incremento;
    }



    
}
