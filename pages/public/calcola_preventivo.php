<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

//istanzio il writer
$writer = new CalcolaPreventivoView();

$current_user = wp_get_current_user();


?>

<div class="calcola-preventivo">
    
    <h2>Calcola il Preventivo</h2>
        
    <form action="<?php echo curPageURL() ?>" method="POST" >
        <input type="hidden" name="ajax-url" value="<?php echo plugins_url()?>/preventivi_serrature/ajax/ajax_call.php" />
        
        <p class="data-odierna">
            Data: <?php echo date('d/m/Y') ?>
            <input type="hidden" name="data-odierna" value="<?php echo date('d/m/Y') ?>" />
        </p>
        <p class="rivenditore">
            Rivenditore/Agente: <?php echo $current_user->display_name ?>
            <input type="hidden" name="rivenditore-agente" value="<?php echo $current_user->display_name ?>" />
        </p>
        <p class="info-cliente">
            <label>Nome Cliente</label><input type="text" value="" name="nome-cliente" /><br>
            <label>Via</label><input type="text" value="" name="via-cliente" /><br>
            <label>Telefono</label><input type="text" value="" name="telefono-cliente" />
        </p>
        <div id="container-infissi">
            <div class="selezione-container">
            <?php
                $writer->printSelezioneInfisso();
            ?>
            </div>
        </div>
        <br><br>
        <input class="clear" type="button" name="aggiungi-infisso" value="Aggiungi nuovo infisso" />
        
        
        <div class="totale-preventivo">
        <h3>Totale preventivo</h3>
        
            <div class="prezzo-preventivo">0</div>
        </div>
        
    </form>
    
    
</div>