<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GestionePreventiviView
 *
 * @author Alex
 */
class GestionePreventiviView {
    
    private $cPreventivo;
    
    
    function __construct() {
        $this->cPreventivo = new PreventivoController();
    }

    
}
