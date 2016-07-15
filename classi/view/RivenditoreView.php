<?php


/**
 * Description of RivenditoreView
 *
 * @author Alex
 */
class RivenditoreView extends UtenteView {
    private $cRivenditore;
    private $vTrasporti; 
    private $form, $label, $uLabel;   

    function __construct() {
        parent::__construct();
        $this->cRivenditore = new RivenditoreController();
        $this->vTrasporti = new TrasportoView();
        
        $this->uLabel = parent::getLabel();
        
        global $RIV_FORM_SUBMIT, $RIV_LABEL_SUBMIT, $FORM_NOMINATIVO, $LABEL_NOMINATIVO, $FORM_SCONTO, $LABEL_SCONTO, $FORM_CODICE, $LABEL_CODICE, $FORM_CON_VEN, $LABEL_CON_VEN, $FORM_PAG, $LABEL_PAG;
        $this->form['submit'] = $RIV_FORM_SUBMIT;
        $this->form['nominativo'] = $FORM_NOMINATIVO;
        $this->form['sconto'] = $FORM_SCONTO;
        $this->form['codice'] = $FORM_CODICE;
        $this->form['conVen'] = $FORM_CON_VEN;
        $this->form['pag'] = $FORM_PAG;
        
        $this->label['submit'] = $RIV_LABEL_SUBMIT;
        $this->label['nominativo'] = $LABEL_NOMINATIVO;
        $this->label['sconto'] = $LABEL_SCONTO;
        $this->label['codice'] = $LABEL_CODICE;
        $this->label['conVen'] = $LABEL_CON_VEN;
        $this->label['pag'] = $LABEL_PAG;
    }
    
    /**
     * Funzione che stampa un form di inserimento Rivenditore
     */
    public function printForm() {        
        
    ?>
        <form class="form-horizontal" role="form" action="<?php echo curPageURL() ?>" name="form-rivenditore" method="POST" >
            <div class="col-sm-6">
                <?php parent::printTextFormField($this->form['codice'], $this->label['codice'], true); ?>
                <?php parent::printTextFormField($this->form['nominativo'], $this->label['nominativo'], true); ?>


                <?php parent::printForm(); ?>              

                <?php parent::printTextFormField($this->form['sconto'], $this->label['sconto']); ?>
                <?php $this->vTrasporti->printSelectTrasporti() ?>
                <?php parent::printTextAreaFormField($this->form['conVen'], $this->label['conVen']); ?>
                <?php parent::printTextAreaFormField($this->form['pag'], $this->label['pag']); ?>
               
            </div>
            <div class="col-sm-6">
                <?php parent::printIndirizzoForm(); ?>
            </div>
            <div class="clear"></div>
            <?php parent::printSubmitFormField($this->form['submit'], $this->label['submit']) ?>
            <div class="clear"></div>
        </form>
       
    <?php        
    }
    
    /**
     * Viene stampato a video il form di dettaglio Rivenditore
     * @param type $idUserWP
     */
    public function printDettaglioRivendioreForm($idUserWP){
        $r = new Rivenditore();
        $r = $this->cRivenditore->getRivenditore($idUserWP);
        if($r != null){
    ?>
        <h3>Dettaglio Rivenditore: <?php echo $r->getNominativo() ?></h3>
        <div class="col-sm-8">
            <form class="form-horizontal" role="form" action="<?php echo curPageURL() ?>" name="form-rivenditore" method="POST" >
                <?php parent::printTextFormField($this->form['codice'], $this->label['codice'], true, $r->getCodice()) ?>
                <?php parent::printTextFormField($this->form['nominativo'], $this->label['nominativo'], true, $r->getNominativo()) ?>
                
                <?php parent::printDettaglioForm($r, $r->getIdUtente()); ?>
                
                <?php parent::printTextFormField($this->form['sconto'], $this->label['sconto'], false, $r->getSconto()); ?>
                <?php $this->vTrasporti->printSelectTrasporti($r->getTrasporto()) ?>
                <?php parent::printTextAreaFormField($this->form['conVen'], $this->label['conVen'], false, $r->getCondizioniVendita()); ?>
                <?php parent::printTextAreaFormField($this->form['pag'], $this->label['pag'], false, $r->getPagamento()); ?>
                
                <?php parent::printDettaglioIndirizzo($r->getIndirizzo()) ?>
                
                <?php parent::printActionDettaglio('rivenditore') ?>
            </form>
        </div>
    <?php
        }
        else{
            echo '<p>Rivenditore non trovato :(</p>';
        }
    }
        
    /**
     * Listener del form che aggiunge un rivenditore
     * @return type
     */
    public function listenerForm(){       
        
        //salvo il rivenditore
        if(isset($_POST[$this->form['submit']])){
            
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
                       
            //controllo i campi specifici di rivenditre
            
            $check = $this->checkFields($rivenditore);
            $errors = $check['errors'];
            $rivenditore = $check['rivenditore'];
            
            //controllo se ci sono degli errori
            if($errors > 0){
                //se ci sono stati errori concludo l'operazione
                wp_delete_user($utente->getIdUserWP());
                return;
            }
            
            //se ho superato tutti i controlli, posso salvare il rivenditore nel database
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
    
    
     public function listnerDettaglioForm(){
       
         //AGGIORNA RIVENDITORE
         if(isset($_POST['update-rivenditore'])){
            
            
            $utente = parent::listenerDettaglioForm();
            //controllo se ci sono stati degli errori
            if(is_wp_error($utente)){
                parent::printErrorBoxMessage($utente->get_error_message());
                return;
            }
            
            //CREO L'OGGETTO RIVENDITORE
            $rivenditore = new Rivenditore();
            //converto i campi ottenuti da utente
            $rivenditore->setIdUserWP($utente->getIdUserWP());
            $rivenditore->setIdUtente($utente->getID());
            $rivenditore->setPi($utente->getPi());
            $rivenditore->setTelefono($utente->getTelefono());
            $rivenditore->setIndirizzo($utente->getIndirizzo());
            
            //controllo i campi specifici di rivenditore
            $check = $this->checkFields($rivenditore);
            $errors = $check['errors'];
            $rivenditore = $check['rivenditore'];
            
            //controllo se ci sono degli errori
            if($errors > 0){
                //se ci sono stati errori concludo l'operazione                
                return;
            }
            
            //se ho superato tutti i controlli posso aggiornare il rivenditore nel database
            if($this->cRivenditore->updateRivenditore($rivenditore) == false){
                //errore nell'aggiornamento
                parent::printErrorBoxMessage('Rivenditore non aggiornato');
                return;
            }
            else{
                //tutto ok
                parent::printOkBoxMessage('Rivenditore aggiornato con successo!');
                //Pulisco la variabile $_POST
                unset($_POST);
                return;
            }            
        }
        
        //CANCELLA RIVENDITORE
        if(isset($_POST['delete-rivenditore'])){
            
            $idUserWP = $_POST['idUserWP'];
            if($this->cRivenditore->deleteRivenditore($idUserWP) == false){
                parent::printErrorBoxMessage('Rivenditore non eliminato');
                return;
            }
            else{
                parent::printOkBoxMessage('Rivenditore eliminato con successo!');
                return;
            }
        }
    }
    
    /**
     * Funzione che controlla se i campi di rivenditore sono stati compilati nel modo corretto
     * @return int
     */
    private function checkFields(Rivenditore $rivenditore){
        $errors = 0;
        //controllo sul campo nominativo, campo obbligatorio
        if(isset($_POST[$this->form['nominativo']]) && $_POST[$this->form['nominativo']]!= ''){
            $rivenditore->setNominativo($_POST[$this->form['nominativo']]);
        }
        else{
            //il campo nominativo passato non è corretto.                
            $errors++;
            parent::printErrorBoxMessage('Campo '.$this->label['nominativo'].' mancante o non corretto!');
        }

        //controllo sul campo sconto, campo facoltativo
        if(isset($_POST[$this->form['sconto']])){
            $rivenditore->setSconto($_POST[$this->form['sconto']]);
        }

        //controllo sul campo codice, campo obbligatorio
        if(isset($_POST[$this->form['codice']]) && $_POST[$this->form['codice']] != ''){
            $rivenditore->setCodice($_POST[$this->form['codice']]);                
        }
        else{
            //il campo codice passato non è corretto.
            $errors++;
            parent::printErrorBoxMessage('Campo '.$this->label['codice'].' mancante o non corretto!');
        }

        //controllo sul campo condizioni di vendita, campo facoltativo
        if(isset($_POST[$this->form['conVen']])){
            $rivenditore->setCondizioniVendita($_POST[$this->form['conVen']]);
        }

        //controllo sul campo pagamento, campo facoltativo
        if(isset($_POST[$this->form['pag']])){
            $rivenditore->setPagamento($_POST[$this->form['pag']]);
        }

        //controllo sul campo trasporto, campo obbligatorio
        $tForm = $this->vTrasporti->getForm();

        if(isset($_POST[$tForm['select']]) && $_POST[$tForm['select']] != ''){
            $rivenditore->setTrasporto($_POST[$tForm['select']]);
        }
        else{
            //il campo codice passato non è corretto.
            $errors++;
            parent::printErrorBoxMessage('Trasporto mancante o non corretto!');
        }
        
        $result = array();
        $result['rivenditore'] = $rivenditore;
        $result['errors'] = $errors;
        return $result;
    }
    
    public function printTableRivenditori(){
        
        //ottengo i rivenditori
        $rivenditori = $this->cRivenditore->getRivenditori();
        
        $header = array(
            $this->label['codice'],
            $this->label['nominativo'],
            $this->uLabel['email'],
            $this->uLabel['telefono'],
            'Azioni'
        );
        
        $bodyTable = $this->printBodyTable($rivenditori, true);
        
        parent::printTableHover($header, $bodyTable);
        
    }
    
    protected function printBodyTable($array, $actions = false) {
        parent::printBodyTable($array);
        
        $html = "";
        foreach($array as $item){
            $r = new Rivenditore();
            $r = $item;
            
            $user_info = get_userdata($r->getIdUserWP());
            $htmlActions = ""; 
            if($actions != false){
               
            }
            
            $html.='<tr>';
            //codice
            $html.='<td>'.parent::printTextField(null, $r->getCodice()).'</td>';
            //nominativo
            $html.='<td>'.parent::printTextField(null, $r->getNominativo()).'</td>';
            //email 
            $html.='<td>'.parent::printTextField(null, $user_info->user_email).'</td>';
            //telefono
            $html.='<td>'.parent::printTextField(null, $r->getTelefono()).'</td>';
            //azioni
            $html.='<td><a href="'.get_admin_url().'admin.php?page=pagina_dettaglio&tipo=rivenditore&id='.$r->getIdUserWP().'">Visualizza dettagli</a></td>';
            $html.='</tr>';
        }
        
        return $html;
    }

    
    

}
