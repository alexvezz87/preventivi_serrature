<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

$writer = new GestionePrezziView();


//LISTENER GENERA TABELLA
if(isset($_POST['genera-tabella-articolo'])){
    $writer->listenerFormGenerazioneTabella();
}

//LISTENER UPDATE TABELLA
if(isset($_POST['aggiorna-prezzi'])){
    $writer->listenerUpdateTabella();
}

//LISTENER CANCELLA TABELLA
if(isset($_POST['cancella-tabella'])){
    $writer->listenerDeleteTabella();
}

//stampo il titolo
$writer->printHeader();

//stampo il form di generatore tabelle
$writer->printFormGenerazioneTabella();

//stampo le tabelle già presenti nel database
$writer->printTabelleArticoli();

?>

