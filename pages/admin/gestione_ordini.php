<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

$writer = new GestionePreventivoView();

//stampo il titolo
$writer->printHeaderOrdini();

//listeners
if(isset($_POST['visionato'])){
    $writer->listenerNonVisionati();
}

if(isset($_POST['cancella-preventivo'])){
    $writer->listenerCancella();
}


?>

<div class="fascia-titolo">
    <h3>Ordini da visionare</h3>
</div>
<div class="container-tabella">
    <?php echo $writer->printOrdiniNonVisionati() ?>
</div>
<div class="fascia-titolo">
    <h3>Ordini visionati</h3>
</div>
<div class="container-tabella">    
    <?php echo $writer->printOrdiniVisionati() ?>
</div>