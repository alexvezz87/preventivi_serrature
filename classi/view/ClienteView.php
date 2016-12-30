<?php

/**
 * Description of ClientiView
 *
 * @author Alex
 */
class ClienteView extends UtenteView {
    private $cCliente;
    private $form, $label, $uLabel;
    
    function __construct() {
        parent::__construct();
        $this->cCliente = new ClienteController();
        
        $this->uLabel = parent::getLabel();        
        
        //variabili globali
        global $CLI_FORM_SUBMIT, $CLI_LABEL_SUBMIT, $FORM_NOME, $LABEL_NOME, $FORM_COGNOME, $LABEL_COGNOME;
        
        $this->form['submit'] = $CLI_FORM_SUBMIT;
        $this->form['cognome'] = $FORM_COGNOME;
        $this->form['nome'] = $FORM_NOME;
        
        $this->label['submit'] = $CLI_LABEL_SUBMIT;
        $this->label['cognome'] = $LABEL_COGNOME;
        $this->label['nome'] = $LABEL_NOME;
    }
    
    /**
     * Stampa un form di inserimento Cliente
     */
    public function printForm(){
    
    ?>
        <form class="form-horizontal" role="form" action="<?php echo curPageURL() ?>" name="form-cliente" method="POST" >
            <div class="col-sm-6">
                <?php parent::printTextFormField($this->form['cognome'], $this->label['cognome'], true)  ?>
                <?php parent::printTextFormField($this->form['nome'], $this->label['nome'], true)  ?>
                
                <?php parent::printForm() ?>
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
    
    public function printDettaglioClienteForm($idUserWP){
        $c = new Cliente();
        $c = $this->cCliente->getCliente($idUserWP);
        if($c != null){
    ?>
        <h3>Dettaglio Cliente: <?php echo $c->getCognome() ?> <?php echo $c->getNome() ?></h3>
        <div class="col-sm-8">
            <form class="form-horizontal" role="form" action="<?php echo curPageURL() ?>" name="form-cliente" method="POST" >
                <?php parent::printTextFormField($this->form['cognome'], $this->label['cognome'], true, $c->getCognome()) ?>
                <?php parent::printTextFormField($this->form['nome'], $this->label['nome'], true, $c->getNome()) ?>
                
                <?php parent::printDettaglioForm($c, $c->getIdUtente()); ?>
                                
                <?php parent::printDettaglioIndirizzo($c->getIndirizzo()) ?>
                
                <?php parent::printActionDettaglio('cliente') ?>
            </form>
        </div>
    <?php
        }
        else{
            echo '<p>Cliente non trovato :(</p>';
        }
        
    }
    
    /**
     * Listener di printForm
     * @return type
     */
    public function listenerForm(){
        //salvo il cliente
        if(isset($_POST[$this->form['submit']])){
            
            //aggiungo il listner form utente per il cliente
            $utente = parent::listnerForm('cliente');
            
            //controllo se sono subentrati errori nella creazione dell'utente in Wordpress
            if(is_wp_error($utente)){
                //stampo l'errore a video
                parent::printErrorBoxMessage($utente->get_error_message()); 
                return;
            }
            
            //CREO L'OGGETTO CLIENTE
            $cliente = new Cliente();
            
            //converto i campi ottenuti da UTENTE
            $cliente->setIdUserWP($utente->getIdUserWP());
            $cliente->setPi($utente->getPi());
            $cliente->setTelefono($utente->getTelefono());
            $cliente->setIndirizzo($utente->getIndirizzo());
            
            //controllo i campi specifici di cliente
            $check = $this->checkFields($cliente);
            $errors = $check['errors'];
            $cliente = $check['cliente'];
            
            //controllo se ci sono degli errori
            if($errors > 0){
                //se ci sono stati errori concludo l'operazione
                wp_delete_user($utente->getIdUserWP());
                return;
            }
            
            //se ho superato tutti i controlli, posso salvare il cliente nel database
            if($this->cCliente->saveCliente($cliente) == false){
                //errore nel salvataggio
                parent::printErrorBoxMessage('Cliente non salvato nel Sistema!');
                //elimino anche l'utenza wordpress
                wp_delete_user($utente->getIdUserWP());
                return;
            }
            else{
                //tutto ok
                parent::printOkBoxMessage('Cliente salvato con successo!');
                //Pulisco la variabile $_POST
                unset($_POST);
                return;
            }            
        }
        
    }
    
    
    public function listenerDettaglioForm(){
        //AGGIORNA
        if(isset($_POST['update-cliente'])){
            $utente = parent::listenerDettaglioForm();
            //controllo se ci sono stati degli errori
            if(is_wp_error($utente)){
                parent::printErrorBoxMessage($utente->get_error_message());
                return;
            }
            //CREO L'OGGETTO CLIENTE
            $cliente = new Cliente();
            //converto i campi ottenuti da utente
            $cliente->setIdUserWP($utente->getIdUserWP());
            $cliente->setIdUtente($utente->getID());
            $cliente->setPi($utente->getPi());
            $cliente->setTelefono($utente->getTelefono());
            $cliente->setIndirizzo($utente->getIndirizzo());
            
            //controllo i campi specifici di rivenditore
            $check = $this->checkFields($cliente);
            $errors = $check['errors'];
            $cliente = $check['cliente'];
            
            
            //controllo se ci sono degli errori
            if($errors > 0){
                //se ci sono stati errori concludo l'operazione               
                return;
            }
            
            //se ho superato tutti i controlli posso aggiornare il cliente nel database
            if($this->cCliente->updateCliente($cliente) == false){
                 //errore nell'aggiornamento
                parent::printErrorBoxMessage('Cliente non aggiornato');
                return;
            }
            else{
                //tutto ok
                parent::printOkBoxMessage('Cliente aggiornato con successo!');
                //Pulisco la variabile $_POST
                unset($_POST);
                return;
            } 
        }
        
        //CANCELLA
        if(isset($_POST['delete-cliente'])){
            $idUserWP = $_POST['idUserWP'];
            if($this->cCliente->deleteCliente($idUserWP) == false){
                parent::printErrorBoxMessage('Cliente non eliminato');
                return;
            }
            else{
                parent::printOkBoxMessage('Cliente eliminato con successo!');
                return;
            }
        }
    }
    
    private function checkFields(Cliente $cliente){
        $errors = 0;
        //controllo sul campo cognome, campo obbligatorio
        if(isset($_POST[$this->form['cognome']]) && trim($_POST[$this->form['cognome']]) != ''){
            $cliente->setCognome($_POST[$this->form['cognome']]);
        }
        else{
            //campo non corretto
            $errors++;
            parent::printErrorBoxMessage('Campo '.$this->label['cognome'].' mancante o non corretto!');
        }
        
        //controllo sul campo nome, campo obbligatorio
        if(isset($_POST[$this->form['nome']]) && trim($_POST[$this->form['nome']]) != ''){
            $cliente->setNome($_POST[$this->form['nome']]);
        }
        else{
            //campo non corretto
            $errors++;
            parent::printErrorBoxMessage('Campo '.$this->label['nome'].' mancante o non corretto!');
        }
        
        $result = array();
        $result['cliente'] = $cliente;
        $result['errors'] = $errors;        
       
        return $result;
    }
    
    
    public function printTableClienti(){
         
        //ottengo i clienti
        $clienti = $this->cCliente->getClienti();
        
        $header = array(
            $this->label['cognome'],
            $this->label['nome'],
            $this->uLabel['email'],
            $this->uLabel['telefono'],
            'Azioni'
        );
        
        $bodyTable = $this->printBodyTable($clienti);
        
        parent::printTableHover($header, $bodyTable);
    }
    
    protected function printBodyTable($array) {
        parent::printBodyTable($array);
        
        $html = "";
        foreach($array as $item){
            $c = new Cliente();
            $c = $item;
            
            $user_info = get_userdata($c->getIdUserWP());
            
            $html.='<tr>';
            //cognome
            $html.='<td>'.parent::printTextField(null, $c->getCognome()).'</td>';
            //nome 
            $html.='<td>'.parent::printTextField(null, $c->getNome()).'</td>';
            //email 
            $html.='<td>'.parent::printTextField(null, $user_info->user_email).'</td>';
            //telefono
            $html.='<td>'.parent::printTextField(null, $c->getTelefono()).'</td>';
            //azioni
            $html.='<td><a href="'.get_admin_url().'admin.php?page=pagina_dettaglio&tipo=cliente&id='.$c->getIdUserWP().'">Visualizza dettagli</a></td>';
            $html.='</tr>';            
        }
        
        return $html;
    }
    
    
    
    
    function getLabel() {        
        return array_merge($this->label, $this->uLabel); 
    }
    


}
