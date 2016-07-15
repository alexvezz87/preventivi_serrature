<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

$view = new ClienteView();

?>

<?php $view->listenerForm() ?>

<h3 class="show-form">Inserisci Cliente</h3>
<hr>
<div class="form-container">
    <?php $view->printForm() ?>
</div>
<div class="clear"></div>
<hr>
<h3>Visualizza Clienti</h3>
<?php $view->printTableClienti() ?>

