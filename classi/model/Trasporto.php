<?php


/**
 * Description of Trasporto
 *
 * @author Alex
 */
class Trasporto {
    private $ID;
    private $area;
    private $prezzo;
    
    function __construct() {
        
    }
    
    function getID() {
        return $this->ID;
    }

    function getArea() {
        return $this->area;
    }

    function getPrezzo() {
        return $this->prezzo;
    }

    function setID($ID) {
        $this->ID = $ID;
    }

    function setArea($area) {
        $this->area = $area;
    }

    function setPrezzo($prezzo) {
        $this->prezzo = $prezzo;
    }



}
