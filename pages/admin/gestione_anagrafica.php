<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/
?>

<h1>Gestione Anagrafica</h1>

<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#home">Rivenditori</a></li>
  <li><a data-toggle="tab" href="#menu1">Agenti</a></li>
  <li><a data-toggle="tab" href="#menu2">Clienti</a></li>
  <li><a data-toggle="tab" href="#menu3">Trasporto</a></li>
</ul>

<div class="tab-content">
    <!-- RIVENDITORI -->
    <div id="home" class="tab-pane fade in active">        
        <?php include 'anagrafica/gestione_rivenditori.php' ?>
    </div>
    <div id="menu1" class="tab-pane fade">
      <h3>Agenti</h3>

    </div>
    <div id="menu2" class="tab-pane fade">
      <h3>Clienti</h3>

    </div>
    
    <!-- TRASPORTI -->
    <div id="menu3" class="tab-pane fade">
        <?php include 'anagrafica/gestione_trasporti.php' ?>
    </div>
</div>