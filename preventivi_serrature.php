<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/
 /**
 * @package preventivi_serrature
 */
/*
Plugin Name: Preventivi Serrature
Plugin URI: 
Description: Plugin personalizzato per la visione di preventivi per Praddelli Serrature in modo dinamico
Version: 1.0
Author: Alex Vezzelli - Alex Soluzioni Web
Author URI: http://www.alexsoluzioniweb.it/
License: GPLv2 or later
*/

//includo le librerie
require_once 'install_db.php';
require_once 'classi/classes.php';


//indico la cartella dove è contenuto il plugin
require_once (dirname(__FILE__) . '/preventivi_serrature.php');


//creo il db al momento dell'attivazione
register_activation_hook(__FILE__, 'install_DB');
function install_DB(){
    install_preventivi();
}

//rimuovo il db quando disattivo il plugin
register_deactivation_hook( __FILE__, 'remove_DB');
function remove_DB(){
    dropDB();
}

?>