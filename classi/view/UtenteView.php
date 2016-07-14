<?php

/**
 * Description of UtenteView
 *
 * @author Alex
 */
class UtenteView extends PrinterView {
    
    private $province;
    private $controller;
    private $form, $label;
        
    
    function __construct() {
        
        global $FORM_PARTITA_IVA, $LABEL_PARTITA_IVA, $FORM_TELEFONO, $LABEL_TELEFONO, $FORM_EMAIL, $LABEL_EMAIL;
        global $FORM_INDIRIZZO, $FORM_CIVICO, $FORM_CAP, $FORM_CITTA, $FORM_PROV, $LABEL_INDIRIZZO, $LABEL_CIVICO, $LABEL_CAP, $LABEL_CITTA, $LABEL_PROV;
        
        $this->form['piva'] = $FORM_PARTITA_IVA;
        $this->form['telefono'] = $FORM_TELEFONO;
        $this->form['email'] = $FORM_EMAIL;
        $this->form['indirizzo'] = $FORM_INDIRIZZO;
        $this->form['civico'] = $FORM_CIVICO;
        $this->form['cap'] = $FORM_CAP; 
        $this->form['citta'] = $FORM_CITTA;
        $this->form['prov'] = $FORM_PROV;
                
        $this->label['piva'] = $LABEL_PARTITA_IVA;
        $this->label['telefono'] = $LABEL_TELEFONO;
        $this->label['email'] = $LABEL_EMAIL;
        $this->label['indirizzo'] = $LABEL_INDIRIZZO;
        $this->label['civico'] = $LABEL_CIVICO;
        $this->label['cap'] = $LABEL_CAP; 
        $this->label['citta'] = $LABEL_CITTA;
        $this->label['prov'] = $LABEL_PROV;
        
        $this->province = array(
            'AG' => 'Agrigento',
            'AL' => 'Alessandria',
            'AN' => 'Ancona',
            'AO' => 'Aosta',
            'AR' => 'Arezzo',
            'AP' => 'Ascoli Piceno',
            'AT' => 'Asti',
            'AV' => 'Avellino',
            'BA' => 'Bari',
            'BT' => 'Barletta-Andria-Trani',
            'BL' => 'Belluno',
            'BN' => 'Benevento',
            'BG' => 'Bergamo',
            'BI' => 'Biella',
            'BO' => 'Bologna',
            'BZ' => 'Bolzano',
            'BS' => 'Brescia',
            'BR' => 'Brindisi',
            'CA' => 'Cagliari',
            'CL' => 'Caltanissetta',
            'CB' => 'Campobasso',
            'CI' => 'Carbonia-Iglesias',
            'CE' => 'Caserta',
            'CT' => 'Catania',
            'CZ' => 'Catanzaro',
            'CH' => 'Chieti',
            'CO' => 'Como',
            'CS' => 'Cosenza',
            'CR' => 'Cremona',
            'KR' => 'Crotone',
            'CN' => 'Cuneo',
            'EN' => 'Enna',
            'FM' => 'Fermo',
            'FE' => 'Ferrara',
            'FI' => 'Firenze',
            'FG' => 'Foggia',
            'FC' => 'Forlì-Cesena',
            'FR' => 'Frosinone',
            'GE' => 'Genova',
            'GO' => 'Gorizia',
            'GR' => 'Grosseto',
            'IM' => 'Imperia',
            'IS' => 'Isernia',
            'SP' => 'La Spezia',
            'AQ' => 'L\'Aquila',
            'LT' => 'Latina',
            'LE' => 'Lecce',
            'LC' => 'Lecco',
            'LI' => 'Livorno',
            'LO' => 'Lodi',
            'LU' => 'Lucca',
            'MC' => 'Macerata',
            'MN' => 'Mantova',
            'MS' => 'Massa-Carrara',
            'MT' => 'Matera',
            'ME' => 'Messina',
            'MI' => 'Milano',
            'MO' => 'Modena',
            'MB' => 'Monza e della Brianza',
            'NA' => 'Napoli',
            'NO' => 'Novara',
            'NU' => 'Nuoro',
            'OT' => 'Olbia-Tempio',
            'OR' => 'Oristano',
            'PD' => 'Padova',
            'PA' => 'Palermo',
            'PR' => 'Parma',
            'PV' => 'Pavia',
            'PG' => 'Perugia',
            'PU' => 'Pesaro e Urbino',
            'PE' => 'Pescara',
            'PC' => 'Piacenza',
            'PI' => 'Pisa',
            'PT' => 'Pistoia',
            'PN' => 'Pordenone',
            'PZ' => 'Potenza',
            'PO' => 'Prato',
            'RG' => 'Ragusa',
            'RA' => 'Ravenna',
            'RC' => 'Reggio Calabria',
            'RE' => 'Reggio Emilia',
            'RI' => 'Rieti',
            'RN' => 'Rimini',
            'RM' => 'Roma',
            'RO' => 'Rovigo',
            'SA' => 'Salerno',
            'VS' => 'Medio Campidano',
            'SS' => 'Sassari',
            'SV' => 'Savona',
            'SI' => 'Siena',
            'SR' => 'Siracusa',
            'SO' => 'Sondrio',
            'TA' => 'Taranto',
            'TE' => 'Teramo',
            'TR' => 'Terni',
            'TO' => 'Torino',
            'OG' => 'Ogliastra',
            'TP' => 'Trapani',
            'TN' => 'Trento',
            'TV' => 'Treviso',
            'TS' => 'Trieste',
            'UD' => 'Udine',
            'VA' => 'Varese',
            'VE' => 'Venezia',
            'VB' => 'Verbano-Cusio-Ossola',
            'VC' => 'Vercelli',
            'VR' => 'Verona',
            'VV' => 'Vibo Valentia',
            'VI' => 'Vicenza',
            'VT' => 'Viterbo',
        );
        $this->controller = new UtenteController();
    }
    
    /**
     * La funzione stampa i campi caratteristici di utente
     */
    public function printForm(){               
        parent::printEmailFormField($this->form['email'], $this->label['email'], true);
        parent::printTextFormField($this->form['piva'], $this->label['piva']);
        parent::printTextFormField($this->form['telefono'], $this->label['telefono'], true);        
    }
    
    /**
     * La funzione stampa a video dettagli dell'utente
     * @param Utente $u
     */
    public function printDettaglioForm(Utente $u){
        $user = get_userdata($u->getIdUserWP());
        parent::printDisabledTextFormField('user-name-wordpress', 'ID WP', $user->user_login);
        parent::printHiddenFormField('id', $u->getID());
        parent::printHiddenFormField('idUserWP', $u->getIdUserWP());
        parent::printEmailFormField($this->form['email'], $this->label['email'], true, $user->user_email );
        parent::printTextFormField($this->form['piva'], $this->label['piva'], false, $u->getPi());
        parent::printTextFormField($this->form['telefono'], $this->label['telefono'], true, $u->getTelefono());           
    }
    
    /**
     * Ascoltatore del form di inserimento sul campo utente ed indirizzo
     * Restituisce alla funzione chiamante un oggetto utente 
     * La funzione salva un UTENTE WORDPRESS nel database
     * @param type $role
     * @return \WP_Error|\Utente
     */
    public function listnerForm($role){
        //la funzione di questo listner è generare un array di valori in modo che essi
        //siano ereditati dalle view figlie, così che sia mantenuta l'ereditarietà del codice
                
        $utente = new Utente();
        
        //partita iva
        if(isset($_POST[$this->form['piva']])){
            $utente->setPi($_POST[$this->form['piva']]);
        }
        //telefono
        if(isset($_POST[$this->form['telefono']]) && $_POST[$this->form['telefono']] != ''){
            $utente->setTelefono($_POST[$this->form['telefono']]);
        }
        else{
            return new WP_Error('telefono_incompleto', __("Manca il campo ".$this->label['telefono']));
        }
        //email e salvo l'utente 
        if(isset($_POST[$this->form['email']]) && $_POST[$this->form['email']] != ''){
            //salvo l'utente nuovo come utente wordpress                
            $data = array(
                'user_login'  =>  $_POST[$this->form['email']],
                'user_email' => $_POST[$this->form['email']],
                'role' => $role,
                'user_pass'   =>  NULL
            );
            
            //SALVO L'UTENTE WORDPRESS            
            $idUserWP = wp_insert_user($data);
            
            if(is_wp_error($idUserWP)){
                //se nasce un errore a livello di utenze wordpress, interrompo la procedura
                return $idUserWP;
            }
            
            //ho ottenuto l'id utente di wordpress
            $utente->setIdUserWP($idUserWP);
             
        }    
        else{
            return new WP_Error('email_incompleto', __("Manca il campo ".$this->label['email']));
        }
        
        //INDIRIZZO 
        if(isset($_POST[$this->form['indirizzo']]) && isset($_POST[$this->form['civico']]) && isset($_POST[$this->form['cap']]) && isset($_POST[$this->form['citta']]) && isset($_POST[$this->form['prov']])){
            //controllo sul loro settaggio
            $arrayInd = array();
            array_push($arrayInd, $_POST[$this->form['indirizzo']]);
            array_push($arrayInd, $_POST[$this->form['civico']]);
            array_push($arrayInd, $_POST[$this->form['cap']]);
            array_push($arrayInd, $_POST[$this->form['citta']]);
            array_push($arrayInd, $_POST[$this->form['prov']]);
            
            $okInd = 0;
            for($i=0; $i < count($arrayInd); $i++){
                if($arrayInd[$i] != ''){
                    $okInd++;
                }
            }
            
            if($okInd == 5){
                $indirizzo = new Indirizzo();
                $indirizzo->setIndirizzo($_POST[$this->form['indirizzo']]);
                $indirizzo->setCivico($this->form['civico']);
                $indirizzo->setCap($_POST[$this->form['cap']]);
                $indirizzo->setCitta($_POST[$this->form['citta']]);
                $indirizzo->setProv($_POST[$this->form['prov']]);
                
                $utente->setIndirizzo($indirizzo);
            }
            else if($okInd > 0 && $okInd < 5){
                wp_delete_user($utente->getIdUserWP());
                return new WP_Error('indirizzo_incompleto', __("L'indirizzo inserito non è stato compilato di tutti i suoi campi"));
            }
            else if($okInd == 0){
                $utente->setIndirizzo(null);
            }   
        }
        
        return $utente;  
        
    }
    
    /**
     * Listener del form dettaglio utente
     * @return \WP_Error|\Utente
     */
    public function listenerDettaglioForm(){
        $utente = new Utente;
        $email = "";
        
        //imposto gli ID
        if(isset($_POST['id'])){
            $utente->setID($_POST['id']);
        }
        if(isset($_POST['idUserWP'])){
            $utente->setIdUserWP($_POST['idUserWP']);
        }
        //partita iva 
        if(isset($_POST[$this->form['piva']])){
            $utente->setPi($_POST[$this->form['piva']]);
        }
        //telefono
        if(isset($_POST[$this->form['telefono']]) && $_POST[$this->form['telefono']] != ''){
            $utente->setTelefono($_POST[$this->form['telefono']]);
        }
        else{
            return new WP_Error('telefono_incompleto', __("Manca il campo ".$this->label['telefono']));
        }
        
        //email
         if(isset($_POST[$this->form['email']]) && $_POST[$this->form['email']] != ''){
             //salvo in una variabile per salvare successivamente, salvo errori
             $email = $_POST[$this->form['email']];
         }
         else{
             return new WP_Error('email_incompleto', __("Manca il campo ".$this->label['email']));
         }
         
         //INDIRIZZO
         if(isset($_POST[$this->form['indirizzo']]) && isset($_POST[$this->form['civico']]) && isset($_POST[$this->form['cap']]) && isset($_POST[$this->form['citta']]) && isset($_POST[$this->form['prov']])){
            //controllo sul loro settaggio
            $arrayInd = array();
            array_push($arrayInd, $_POST[$this->form['indirizzo']]);
            array_push($arrayInd, $_POST[$this->form['civico']]);
            array_push($arrayInd, $_POST[$this->form['cap']]);
            array_push($arrayInd, $_POST[$this->form['citta']]);
            array_push($arrayInd, $_POST[$this->form['prov']]);
            
            $okInd = 0;
            for($i=0; $i < count($arrayInd); $i++){
                if($arrayInd[$i] != ''){
                    $okInd++;
                }
            }
            
            if($okInd == 5){
                $indirizzo = new Indirizzo();
                $indirizzo->setIndirizzo($_POST[$this->form['indirizzo']]);
                $indirizzo->setCivico($this->form['civico']);
                $indirizzo->setCap($_POST[$this->form['cap']]);
                $indirizzo->setCitta($_POST[$this->form['citta']]);
                $indirizzo->setProv($_POST[$this->form['prov']]);
                
                $utente->setIndirizzo($indirizzo);
            }
            else if($okInd > 0 && $okInd < 5){                
                return new WP_Error('indirizzo_incompleto', __("L'indirizzo inserito non è stato compilato di tutti i suoi campi"));
            }
            else if($okInd == 0){
                $utente->setIndirizzo(null);
            }   
        }
        
        //aggiorno l'indirizzo email
        $user_data = wp_update_user( array( 'ID' => $utente->getIdUserWP(), 'user_email' => $email ) );
        
        if(is_wp_error($user_data)){
            //se ho problemi con l'update della mail, restituisco l'errore
            return $user_data;
        }
        
        return $utente;
    }
    
    
    /**
     * Funzione che stampa i campi per l'inserimento dell'indirizzo
     */
    public function printIndirizzoForm(){        
        
        echo '<h4>Indirizzo</h4>';
        parent::printTextFormField($this->form['indirizzo'], $this->label['indirizzo']);
        parent::printTextFormField($this->form['civico'], $this->label['civico']);
        parent::printTextFormField($this->form['cap'], $this->label['cap']);
        parent::printTextFormField($this->form['citta'], $this->label['citta']);
        parent::printSelectFormField($this->form['prov'], $this->label['prov'], $this->province);  
    }
    
    
    public function printDettaglioIndirizzo($indirizzi){
        
        if(count($indirizzi) > 0){
            foreach($indirizzi as $indirizzo){
                $i = new Indirizzo();
                $i = $indirizzo;
                echo '<h4>Indirizzo</h4>';
                parent::printTextFormField($this->form['indirizzo'], $this->label['indirizzo'], false, $i->getIndirizzo());
                parent::printTextFormField($this->form['civico'], $this->label['civico'], false, $i->getCivico());
                parent::printTextFormField($this->form['cap'], $this->label['cap'], false, $i->getCap());
                parent::printTextFormField($this->form['citta'], $this->label['citta'], false, $i->getCitta());           
                parent::printSelectFormField($this->form['prov'], $this->label['prov'], $this->province, false, $i->getProv());  
            }
        }
        else{
            //se non ci sono inseriti indirizzi
            echo '<h4>Indirizzo</h4>';
                parent::printTextFormField($this->form['indirizzo'], $this->label['indirizzo'], false);
                parent::printTextFormField($this->form['civico'], $this->label['civico'], false);
                parent::printTextFormField($this->form['cap'], $this->label['cap'], false);
                parent::printTextFormField($this->form['citta'], $this->label['citta'], false);           
                parent::printSelectFormField($this->form['prov'], $this->label['prov'], $this->province, false);
        }
    }
    
    public function getUtenteFieldsforTable($idUserWP){
        //la funzione si preoccupa di restituire un html tabellare inerente ai campi comuni di utente:
        //telefono, partita iva, indirizzo
        $html = "";
        $utente = new Utente();
        $utente = $this->controller->getUtente($idUserWP);
        
        $user_info = get_userdata($idUserWP);
        
        //email
        $html = '<td>'.parent::printTextField($this->form['email'], $user_info->user_email).'</td>';
        //telefono
        $html.= '<td>'.parent::printTextField($this->form['telefono'], $utente->getTelefono()).'</td>';
        
        
        return $html;
        
    }
    
    function getForm() {
        return $this->form;
    }

    function getLabel() {
        return $this->label;
    }

    function setForm($form) {
        $this->form = $form;
    }

    function setLabel($label) {
        $this->label = $label;
    }


    
    
    
}
