<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

global $URL_IMG;

//istanzio il writer
$writer = new CalcolaPreventivoView();

$current_user = wp_get_current_user();

?>

<div class="calcola-preventivo">
    <form action="<?php echo curPageURL() ?>" method="POST" >
        <input type="hidden" name="ajax-url" value="<?php echo plugins_url()?>/preventivi_serrature/ajax/ajax_call.php" />
        
        <div class="intestazione">
            
            <div class="rivenditore field">
                <?php if(!current_user_can('edit_plugins')){ //se è un rivenditore ?>
                    <input disabled type="text" name="rivenditore" value="<?php echo $current_user->display_name ?>" />
                   
                <?php } else { //se è un admin ?>
                    <input name="rivenditore" placeholder="RIVENDITORE" type="text" value="" />
                <?php } ?>
                    
                    
                    <input type="hidden" name="id-user" value="<?php echo $current_user->ID ?>" />
            </div>
            <?php if(current_user_can('edit_plugins')){ ?>
                <div class="codice-rivenditore field">
                    <input name="codice-rivenditore" placeholder="CODICE" type="text" value="" />
                </div>
            <?php } ?>
                
            
            <div class="data-odierna field">                
                <input type="text" value="<?php echo date('d/m/Y') ?>" disabled />
                <input type="hidden" name="data-odierna" value="<?php echo date('d/m/Y') ?>" />
            </div>
            <?php if(current_user_can('edit_plugins')){ ?>
                <div class="agente field">
                    <input name="agente" placeholder="AGENTE" type="text" value="" />
                </div>
            
            <?php } ?>
            
            <?php if(current_user_can('edit_plugins')){ ?>
                <div class="sconto field">
                    <input name="sconto-rivenditore" placeholder="SCONTO" type="text" value="" />
                </div>            
            <?php } ?>
            
            <?php if(current_user_can('edit_plugins')){ ?>
                <div class="trasporto field">
                    <input name="trasporto" placeholder="TRASPORTO" type="text" value="" />
                </div>            
            <?php } ?>
            
            
            <div class="info-cliente field">
                <input type="text" placeholder="RIFERIMENTO" value="" name="nome-cliente" />
                
                <div class="tipo-cliente radio">
                    <input type="radio" name="tipo-cliente" value="privato" checked><label>PRIVATO</label>
                    <input type="radio" name="tipo-cliente" value="rivenditore"><label>RIVENDITORE</label>
                    <div class="clear"></div>
                </div>
                
                <input type="text" placeholder="INDIRIZZO" value="" name="via-cliente" /><br>
                <input type="text" placeholder="TELEFONO" value="" name="telefono-cliente" />
                <input type="email" placeholder="EMAIL" value="" name="mail-cliente" />
                
                <input type="text" placeholder="C.F. /P.IVA" value="" name="cf" />
            </div>
            <div class="tipo radio clear">                           
                <input type="radio" name="tipo" value="0" checked /><label>PREVENTIVO</label>
                <?php if(current_user_can('agente') || current_user_can( 'rivenditore') || current_user_can( 'edit_plugins')){ ?>
                    <input type="radio" name="tipo" value="1" /><label>CONFERMA ORDINE</label>
                <?php } ?>
            </div>
            <div class="clear"></div>
        </div>
        <div id="container-infissi">
            <div class="selezione-container" data-infisso="1">
            <?php
                $writer->printSelezioneInfisso();
            ?>
            </div>
        </div>
        
        <div class="clear clear-max"></div>
        <input type="button" name="aggiungi-copia-infisso" value="Aggiungi una copia dell'ultimo infisso" disabled />
        <input type="button" name="aggiungi-infisso" value="Aggiungi nuovo infisso" disabled />
        <div class="clear"></div>
        <hr>
        <div class="upload-immagini">
            <p class="step">Aggiungi eventuali foto (inserisci al massimo 5 foto)</p>
            <input id="fileupload" type="file" name="files[]" data-url="<?php echo plugins_url().'/preventivi_serrature/upload_index.php' ?>" multiple>
            
            <div class="clear" id="description"></div>
            <div id="progress">
                <div class="bar" style="width: 0%;"></div>
            </div>
            <input class="input-immagine" data-img="1" type="hidden" name="immagine-01" value="" />
            <input class="input-immagine" data-img="2" type="hidden" name="immagine-02" value="" />
            <input class="input-immagine" data-img="3" type="hidden" name="immagine-03" value="" />
            <input class="input-immagine" data-img="4" type="hidden" name="immagine-04" value="" />
            <input class="input-immagine" data-img="5" type="hidden" name="immagine-05" value="" />
        </div>
        <div class="note clear">
            <p class="step">Note</p>
            <textarea name="note" cols="10" rows="5"></textarea>
        </div>
        
        <div class="step totale-preventivo">
            <div class="prezzo-preventivo">0.00</div>
            <div>Totale preventivo: € </div>
            <div class="clear" style="float:none"></div>
        </div>
        
        <input type="button" name="invia-preventivo" value="Invia Preventivo" /> 
    </form>
    
    <div class="error-box message-box">
        <h4>Preventivo compilato non correttamente</h4>
        <p></p>
        <input type="button" name="close-box" value="OK" />
    </div>
    <div class="ok-box message-box">
        <div class="message"></div>
        <input type="button" name="close-box" value="OK" />
    </div>
    <div class="loading-container hidden">
        <img src="<?php echo $URL_IMG ?>loading.gif" alt="loading"/>
    </div>
</div>