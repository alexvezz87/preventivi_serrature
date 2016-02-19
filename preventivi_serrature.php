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
require_once 'functions.php';

global $DIR_PDF;
global $URL_PDF;
global $URL_IMG;

$DIR_PDF = plugin_dir_path(__FILE__).'\\preventivi_pdf\\';
$URL_PDF = plugins_url().'/preventivi_serrature/preventivi_pdf/';
$URL_IMG = plugins_url().'/preventivi_serrature/images/';

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
    //dropDB();
}


//Aggiungo il menu di Plugin
function add_admin_menu(){
    add_menu_page('Gestione Preventivi', 'Gestione Preventivi', 'administrator', 'gestione_preventivi', 'add_gestione_preventivi', plugins_url('images/ico_plugin.png', __FILE__), 9 );
    add_submenu_page('gestione_preventivi', 'Gestione Prezzi', 'Gestione prezzi', 'administrator', 'gestione_prezzi', 'add_gestione_prezzi');
    add_submenu_page('gestione_preventivi', 'Maggiorazioni', 'Maggiorazioni', 'administrator', 'gestione_maggiorazioni', 'add_gestione_maggiorazioni');
}


function add_gestione_preventivi(){
    include 'pages/admin/gestione_preventivi.php';
}

function add_gestione_prezzi(){
     include 'pages/admin/gestione_prezzi.php';
}

function add_gestione_maggiorazioni(){
    include 'pages/admin/gestione_maggiorazioni.php';
}

//registro il menu
add_action('admin_menu', 'add_admin_menu');


//inserisco shortcode di pagina
add_shortcode('calcolaPreventivo', 'add_calcola_preventivo');
function add_calcola_preventivo(){
    include 'pages/public/calcola_preventivo.php';
}

//registro lo stile
add_action( 'wp_enqueue_scripts', 'register_style' );
function register_style(){
    wp_register_style('style_css', plugins_url('preventivi_serrature/css/style.css'));
    wp_enqueue_style('style_css');
}

//Aggiungo il file di Javascript al plugin
add_action( 'wp_enqueue_scripts', 'register_js_script' );

function register_js_script(){
     wp_register_script('functions-js', plugins_url('preventivi_serrature/js/script.js'), array('jquery'), '1.0', false);          
     wp_register_script('json-js', plugins_url('preventivi_serrature/js/jquery.json.min.js'), array('jquery'), '1.0', false);     
     wp_enqueue_script('json-js');   
     wp_enqueue_script('functions-js');   
} 


?>