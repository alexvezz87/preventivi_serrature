<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

$writer = new GestioneMaggiorazioneView();


//LISTENER GENERA MAGGIORAZIONE
if(isset($_POST['aggiungi-maggiorazione'])){
    $writer->listenerGenerazioneMaggiorazione();
}

//LISTENER TABELLA MAGGIORAZIONI
if(isset($_POST['cancella-maggiorazione'])){
    $writer->listenerTableMaggiorazioni();
}

//stampo il titolo
$writer->printHeader();

//stampo il form
$writer->printFormGenerazioneMaggiorazione();

//stampo le maggiorazioni presenti nel db
$writer->printTableMaggiorazioni();


?>

