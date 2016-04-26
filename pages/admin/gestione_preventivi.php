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

if(isset($_POST['ordine'])){
    $writer->listenerPreventivo();
}


?>

<div class="fascia-titolo">
    <h3>Preventivi da visionare</h3>
</div>

<div class="container-tabella">
    <?php echo $writer->printPreventiviNonVisionati() ?>
</div>

<div class="fascia-titolo">
    <h3>Preventivi visionati</h3>
</div>

<div class="container-tabella">    
    <?php echo $writer->printPreventiviVisionati() ?>
</div>
