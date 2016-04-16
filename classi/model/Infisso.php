<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Infisso
 *
 * @author Alex
 */
class Infisso {
    private $id;
    private $idPreventivo;
    private $tipo;
    private $nAnte;
    private $idInfisso;
    private $altezza;
    private $lunghezza;
    private $apertura;
    private $barra;
    private $serratura;
    private $nodo;
    private $colore;
    private $cerniera;
    private $nInfisso;
    private $spesaInfisso;
    private $maggiorazioni;
    private $antaPrincipale;
    private $posizioneSerratura;
    
    function __construct() {
        //costruttore vuoto
    }
    
    function getId() {
        return $this->id;
    }

    function getIdPreventivo() {
        return $this->idPreventivo;
    }

    function getTipo() {
        return $this->tipo;
    }

    function getNAnte() {
        return $this->nAnte;
    }

    function getIdInfisso() {
        return $this->idInfisso;
    }

    function getAltezza() {
        return $this->altezza;
    }

    function getLunghezza() {
        return $this->lunghezza;
    }

    function getApertura() {
        return $this->apertura;
    }

    function getBarra() {
        return $this->barra;
    }

    function getSerratura() {
        return $this->serratura;
    }

    function getNodo() {
        return $this->nodo;
    }

    function getColore() {
        return $this->colore;
    }

    function getCerniera() {
        return $this->cerniera;
    }

    function getNInfisso() {
        return $this->nInfisso;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setIdPreventivo($idPreventivo) {
        $this->idPreventivo = $idPreventivo;
    }

    function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    function setNAnte($nAnte) {
        $this->nAnte = $nAnte;
    }

    function setIdInfisso($idInfisso) {
        $this->idInfisso = $idInfisso;
    }

    function setAltezza($altezza) {
        $this->altezza = $altezza;
    }

    function setLunghezza($lunghezza) {
        $this->lunghezza = $lunghezza;
    }

    function setApertura($apertura) {
        $this->apertura = $apertura;
    }

    function setBarra($barra) {
        $this->barra = $barra;
    }

    function setSerratura($serratura) {
        $this->serratura = $serratura;
    }

    function setNodo($nodo) {
        $this->nodo = $nodo;
    }

    function setColore($colore) {
        $this->colore = $colore;
    }

    function setCerniera($cerniera) {
        $this->cerniera = $cerniera;
    }

    function setNInfisso($nInfisso) {
        $this->nInfisso = $nInfisso;
    }

    function getSpesaInfisso() {
        return $this->spesaInfisso;
    }

    function setSpesaInfisso($spesaInfisso) {
        $this->spesaInfisso = $spesaInfisso;
    }

    function getMaggiorazioni() {
        return $this->maggiorazioni;
    }

    function setMaggiorazioni($maggiorazioni) {
        $this->maggiorazioni = $maggiorazioni;
    }
    
    function getAntaPrincipale() {
        return $this->antaPrincipale;
    }

    function getPosizioneSerratura() {
        return $this->posizioneSerratura;
    }

    function setAntaPrincipale($antaPrincipale) {
        $this->antaPrincipale = $antaPrincipale;
    }

    function setPosizioneSerratura($posizioneSerratura) {
        $this->posizioneSerratura = $posizioneSerratura;
    }


    
}
