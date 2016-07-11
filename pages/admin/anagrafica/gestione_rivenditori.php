<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

$view = new RivenditoreView();

?>

<?php $view->listenerForm() ?>

<h3 class="show-form">Inserisci Rivenditore</h3>
<hr>
<div class="form-container">
    <?php $view->printForm() ?>
</div>
<div class="clear"></div>
<hr>