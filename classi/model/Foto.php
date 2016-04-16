<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Foto
 *
 * @author Alex
 */
class Foto {
    //put your code here
    
    private $ID;
    private $idPreventivo;
    private $nomeFoto;
    private $urlFoto;
    private $urlThumbFoto;
    
    function __construct() {
        //costruttore vuoto
    }
    
    function getID() {
        return $this->ID;
    }

    function getIdPreventivo() {
        return $this->idPreventivo;
    }

    function getNomeFoto() {
        return $this->nomeFoto;
    }

    function getUrlFoto() {
        return $this->urlFoto;
    }

    function setID($ID) {
        $this->ID = $ID;
    }

    function setIdPreventivo($idPreventivo) {
        $this->idPreventivo = $idPreventivo;
    }

    function setNomeFoto($nomeFoto) {
        $this->nomeFoto = $nomeFoto;
    }

    function setUrlFoto($urlFoto) {
        $this->urlFoto = $urlFoto;
    }

    function getUrlThumbFoto() {
        return $this->urlThumbFoto;
    }

    function setUrlThumbFoto($urlThumbFoto) {
        $this->urlThumbFoto = $urlThumbFoto;
    }


    
    
}
