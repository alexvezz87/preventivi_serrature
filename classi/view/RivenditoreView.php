<?php


/**
 * Description of RivenditoreView
 *
 * @author Alex
 */
class RivenditoreView extends UtenteView {
    private $cRivenditore;
    private $vTrasporti; 
       

    function __construct() {
        parent::__construct();
        $this->cRivenditore = new RivenditoreController();
        $this->vTrasporti = new TrasportoView();
    }

    public function printForm() {
        global $RIV_FORM_SUBMIT, $RIV_LABEL_SUBMIT, $FORM_NOMINATIVO, $LABEL_NOMINATIVO, $FORM_SCONTO, $LABEL_SCONTO, $FORM_CODICE, $LABEL_CODICE, $FORM_CON_VEN, $LABEL_CON_VEN, $FORM_PAG, $LABEL_PAG;
        
    ?>
        <form class="form-horizontal" role="form" action="<?php echo curPageURL() ?>" name="form-rivenditore" method="POST" >
            <div class="col-sm-6">
                <?php parent::printTextFormField($FORM_CODICE, $LABEL_CODICE, true); ?>
                <?php parent::printTextFormField($FORM_NOMINATIVO, $LABEL_NOMINATIVO, true); ?>


                <?php parent::printForm(); ?>              

                <?php parent::printTextFormField($FORM_SCONTO, $LABEL_SCONTO); ?>
                <?php $this->vTrasporti->printSelectTrasporti() ?>
                <?php parent::printTextAreaFormField($FORM_CON_VEN, $LABEL_CON_VEN); ?>
                <?php parent::printTextAreaFormField($FORM_PAG, $LABEL_PAG); ?>
               
            </div>
            <div class="col-sm-6">
                <?php parent::printIndirizzoForm(); ?>
            </div>
            <div class="clear"></div>
            <?php parent::printSubmitFormField($RIV_FORM_SUBMIT, $RIV_LABEL_SUBMIT) ?>
            <div class="clear"></div>
        </form>
       
    <?php        
    }
    
    /**
     * Listener del form che aggiunge un rivenditore
     * @global type $RIV_FORM_SUBMIT
     * @global type $FORM_NOMINATIVO
     * @global type $FORM_SCONTO
     * @global type $FORM_CODICE
     * @global type $FORM_CON_VEN
     * @global type $FORM_PAG
     * @return type
     */
    public function listenerForm(){
        global $RIV_FORM_SUBMIT, $FORM_NOMINATIVO, $FORM_SCONTO, $FORM_CODICE, $FORM_CON_VEN, $FORM_PAG, $TRAS_FORM_SELECT;
        
        if(isset($_POST[$RIV_FORM_SUBMIT])){
            
            $utente = parent::listnerForm('rivenditore');
            
            //controllo se sono subentrati errori nella creazione dell'utente in Wordpress
            if(is_wp_error($utente)){
                //stampo l'errore a video
                parent::printErrorBoxMessage($utente->get_error_message()); 
                return;
            }
            
            //CREO L'OGGETTO RIVENDITORE            
            $rivenditore = new Rivenditore();
            
            //converto i campi ottenuti da UTENTE
            $rivenditore->setIdUserWP($utente->getIdUserWP());
            $rivenditore->setPi($utente->getPi());
            $rivenditore->setTelefono($utente->getTelefono());            
            $rivenditore->setIndirizzo($utente->getIndirizzo());
            
            //variabile conta errori
            $errors = 0;
            
            //controllo sul campo nominativo, campo obbligatorio
            if(isset($_POST[$FORM_NOMINATIVO]) && $_POST[$FORM_NOMINATIVO]!= ''){
                $rivenditore->setNominativo($_POST[$FORM_NOMINATIVO]);
            }
            else{
                //il campo nominativo passato non è corretto.                
                $errors++;
                parent::printErrorBoxMessage('Nominativo Rivenditore mancante o non corretto!');
            }
            
            //controllo sul campo sconto, campo facoltativo
            if(isset($_POST[$FORM_SCONTO])){
                $rivenditore->setSconto($_POST[$FORM_SCONTO]);
            }
            
            //controllo sul campo codice, campo obbligatorio
            if(isset($_POST[$FORM_CODICE]) && $_POST[$FORM_CODICE] != ''){
                $rivenditore->setCodice($_POST[$FORM_CODICE]);                
            }
            else{
                //il campo codice passato non è corretto.
                $errors++;
                parent::printErrorBoxMessage('Codice Rivenditore mancante o non corretto!');
            }
            
            //controllo sul campo condizioni di vendita, campo facoltativo
            if(isset($_POST[$FORM_CON_VEN])){
                $rivenditore->setCondizioniVendita($_POST[$FORM_CON_VEN]);
            }
            
            //controllo sul campo pagamento, campo facoltativo
            if(isset($_POST[$FORM_PAG])){
                $rivenditore->setPagamento($_POST[$FORM_PAG]);
            }
            
            //controllo sul campo trasporto, campo obbligatorio
            if(isset($_POST[$TRAS_FORM_SELECT]) && $_POST[$TRAS_FORM_SELECT] != ''){
                $rivenditore->setTrasporto($_POST[$TRAS_FORM_SELECT]);
            }
            else{
                //il campo codice passato non è corretto.
                $errors++;
                parent::printErrorBoxMessage('Trasporto mancante o non corretto!');
            }
            
            //controllo se ci sono degli errori
            if($errors > 0){
                //se ci sono stati errori concludo l'operazione
                wp_delete_user($utente->getIdUserWP());
                return;
            }
            
            //se ho superato tutti i controlli, posso salvare il rivenditore nel datbase
            if($this->cRivenditore->saveRivenditore($rivenditore) == false){
                //errore nel salvataggio
                parent::printErrorBoxMessage('Rivenditore non salvato nel Sistema!');
                //elimino anche l'utenza wordpress
                wp_delete_user($utente->getIdUserWP());
                return;
            }
            else{
                //tutto ok
                parent::printOkBoxMessage('Rivenditore salvato con successo!');
                //Pulisco la variabile $_POST
                unset($_POST);
                return;
            }
            
        }
    }

}
