<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

$id = $_GET['id'];

?>

<div class="back" style="margin-top:20px">
    <a href="<?php echo admin_url() ?>/admin.php?page=gestione_anagrafica"><<<< Torna alla pagina precedente</a>
</div>
<div class="dettaglio-utente">
<?php

//ottengo il tipo di view
if($_GET['tipo'] == 'rivenditore'){    
    //RIVENDITORE
    $viewR = new RivenditoreView();    
    $viewR->listnerDettaglioForm();
    $viewR->printDettaglioRivendioreForm($id);
}
else if($_GET['tipo'] == 'agente'){
    include 'dettaglio/dettaglio_agente.php';
}
else if($_GET['tipo'] == 'cliente'){
    include 'dettaglio/dettaglio_cliente.php';
}

?>

</div>
