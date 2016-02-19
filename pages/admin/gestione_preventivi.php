<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

$writer = new GestionePreventivoView();

//stampo il titolo
$writer->printHeader();

//listeners
if(isset($_POST['visionato'])){
    $writer->listenerNonVisionati();
}

if(isset($_POST['cancella-preventivo'])){
    $writer->listenerCancella();
}


?>

<h3>Preventivi da visionare</h3>

<?php echo $writer->printPreventiviNonVisionati() ?>


<h3>Preventivi visionati</h3>

<?php echo $writer->printPreventiviVisionati() ?>


