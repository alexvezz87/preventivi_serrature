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
    private $pdf;
    private $note;
    private $tipo;
    private $clienteTipo;
    private $clienteCF;
    private $clienteEmail;
    private $foto;
    private $nomeRivenditore;
    private $codiceRivenditore;
    private $agente;
    private $scontoRivenditore;
    private $trasporto;
    private $commessa;
    private $pdfOrdine;
        
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

    function getPdf() {
        return $this->pdf;
    }

    function setPdf($pdf) {
        $this->pdf = $pdf;
    }

    function getNote() {
        return $this->note;
    }

    function getTipo() {
        return $this->tipo;
    }

    function setNote($note) {
        $this->note = $note;
    }

    function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    function getClienteTipo() {
        return $this->clienteTipo;
    }

    function getClienteCF() {
        return $this->clienteCF;
    }

    function getClienteEmail() {
        return $this->clienteEmail;
    }

    function setClienteTipo($clienteTipo) {
        $this->clienteTipo = $clienteTipo;
    }

    function setClienteCF($clienteCF) {
        $this->clienteCF = $clienteCF;
    }

    function setClienteEmail($clienteEmail) {
        $this->clienteEmail = $clienteEmail;
    }

    function getFoto() {
        return $this->foto;
    }

    function setFoto($foto) {
        $this->foto = $foto;
    }

    function getNomeRivenditore() {
        return $this->nomeRivenditore;
    }

    function setNomeRivenditore($nomeRivenditore) {
        $this->nomeRivenditore = $nomeRivenditore;
    }

    function getCodiceRivenditore() {
        return $this->codiceRivenditore;
    }

    function setCodiceRivenditore($codiceRivenditore) {
        $this->codiceRivenditore = $codiceRivenditore;
    }

    function getAgente() {
        return $this->agente;
    }

    function setAgente($agente) {
        $this->agente = $agente;
    }

    function getScontoRivenditore() {
        return $this->scontoRivenditore;
    }

    function setScontoRivenditore($scontoRivenditore) {
        $this->scontoRivenditore = $scontoRivenditore;
    }

    function getTrasporto() {
        return $this->trasporto;
    }

    function setTrasporto($trasporto) {
        $this->trasporto = $trasporto;
    }

    function getCommessa() {
        return $this->commessa;
    }

    function getPdfOrdine() {
        return $this->pdfOrdine;
    }

    function setCommessa($commessa) {
        $this->commessa = $commessa;
    }

    function setPdfOrdine($pdfOrdine) {
        $this->pdfOrdine = $pdfOrdine;
    }



}
