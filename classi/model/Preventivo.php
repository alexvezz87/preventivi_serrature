<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Preventivo
 *
 * @author Alex
 */
class Preventivo {
   
    private $id;
    private $data;
    private $idUtente;
    private $clienteNome;
    private $clienteVia;
    private $clienteTel;
    private $spesaTotale;
    private $infissi;
    private $visionato;
    
    function __construct() {
        //costruttore vuoto
    }
    
    function getId() {
        return $this->id;
    }

    function getData() {
        return $this->data;
    }

    function getIdUtente() {
        return $this->idUtente;
    }

    function getClienteNome() {
        return $this->clienteNome;
    }

    function getClienteVia() {
        return $this->clienteVia;
    }

    function getClienteTel() {
        return $this->clienteTel;
    }

    function getSpesaTotale() {
        return $this->spesaTotale;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setData($data) {
        $this->data = $data;
    }

    function setIdUtente($idUtente) {
        $this->idUtente = $idUtente;
    }

    function setClienteNome($clienteNome) {
        $this->clienteNome = $clienteNome;
    }

    function setClienteVia($clienteVia) {
        $this->clienteVia = $clienteVia;
    }

    function setClienteTel($clienteTel) {
        $this->clienteTel = $clienteTel;
    }

    function setSpesaTotale($spesaTotale) {
        $this->spesaTotale = $spesaTotale;
    }

    function getInfissi() {
        return $this->infissi;
    }

    function setInfissi($infissi) {
        $this->infissi = $infissi;
    }

    function getVisionato() {
        return $this->visionato;
    }

    function setVisionato($visionato) {
        $this->visionato = $visionato;
    }



}
