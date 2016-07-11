<?php

/**
 * Description of UtenteView
 *
 * @author Alex
 */
class UtenteView extends PrinterView {
    
    private $province;
    
    
    function __construct() {
        
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
    }
    
    
    public function printForm(){
        global $FORM_PARTITA_IVA, $LABEL_PARTITA_IVA;
        global $FORM_TELEFONO, $LABEL_TELEFONO;
        global $FORM_EMAIL, $LABEL_EMAIL;
        
        parent::printEmailFormField($FORM_EMAIL, $LABEL_EMAIL, true);
        parent::printTextFormField($FORM_PARTITA_IVA, $LABEL_PARTITA_IVA);
        parent::printTextFormField($FORM_TELEFONO, $LABEL_TELEFONO, true);
        
    }
    
    /**
     * Ascoltatore del form di inserimento sul campo utente ed indirizzo
     * Restituisce alla funzione chiamante un oggetto utente 
     * La funzione salva un UTENTE WORDPRESS nel database
     * @global type $FORM_PARTITA_IVA
     * @global type $FORM_TELEFONO
     * @global type $FORM_EMAIL
     * @global type $FORM_INDIRIZZO
     * @global type $FORM_CIVICO
     * @global type $FORM_CAP
     * @global type $FORM_CITTA
     * @global type $FORM_PROV
     * @param type $role
     * @return \Utente
     */
    public function listnerForm($role){
        //la funzione di questo listner è generare un array di valori in modo che essi
        //siano ereditati dalle view figlie, così che sia mantenuta l'ereditarietà del codice
        
        //variabili globali riferite all'utente
        global $FORM_PARTITA_IVA, $FORM_TELEFONO, $FORM_EMAIL;
        
        $utente = new Utente();
        
        //partita iva
        if(isset($_POST[$FORM_PARTITA_IVA])){
            $utente->setPi($_POST[$FORM_PARTITA_IVA]);
        }
        //telefono
        if(isset($_POST[$FORM_TELEFONO])){
            $utente->setTelefono($_POST[$FORM_TELEFONO]);
        }
        //email e salvo l'utente 
        if(isset($_POST[$FORM_EMAIL])){
            //salvo l'utente nuovo come utente wordpress                
            $data = array(
                'user_login'  =>  $_POST[$FORM_EMAIL],
                'user_email' => $_POST[$FORM_EMAIL],
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
        
        
        //INDIRIZZO
        global $FORM_INDIRIZZO, $FORM_CIVICO, $FORM_CAP, $FORM_CITTA, $FORM_PROV;
        
        if(isset($_POST[$FORM_INDIRIZZO]) && isset($_POST[$FORM_CIVICO]) && isset($_POST[$FORM_CAP]) && isset($_POST[$FORM_CITTA]) && isset($_POST[$FORM_PROV])){
            //controllo sul loro settaggio
            $arrayInd = array();
            array_push($arrayInd, $_POST[$FORM_INDIRIZZO]);
            array_push($arrayInd, $_POST[$FORM_CIVICO]);
            array_push($arrayInd, $_POST[$FORM_CAP]);
            array_push($arrayInd, $_POST[$FORM_CITTA]);
            array_push($arrayInd, $_POST[$FORM_PROV]);
            
            $okInd = 0;
            for($i=0; $i < count($arrayInd); $i++){
                if($arrayInd[$i] != ''){
                    $okInd++;
                }
            }
            
            if($okInd == 5){
                $indirizzo = new Indirizzo();
                $indirizzo->setIndirizzo($_POST[$FORM_INDIRIZZO]);
                $indirizzo->setCivico($_POST[$FORM_CIVICO]);
                $indirizzo->setCap($_POST[$FORM_CAP]);
                $indirizzo->setCitta($_POST[$FORM_CITTA]);
                $indirizzo->setProv($_POST[$FORM_PROV]);
                
                $utente->setIndirizzo($indirizzo);
            }
            else if($okInd > 0 && $okInd < 5){
                return new WP_Error('indirizzo_incompleto', __("L'indirizzo inserito non è stato compilato di tutti i suoi campi"));
            }
            else if($okInd == 0){
                $utente->setIndirizzo(null);
            }
                
         
        }
        
        return $utente;  
        
    }
    
    public function printIndirizzoForm(){
        global $FORM_INDIRIZZO, $LABEL_INDIRIZZO, $FORM_CIVICO, $LABEL_CIVICO, $FORM_CAP, $LABEL_CAP, $FORM_CITTA, $LABEL_CITTA, $FORM_PROV, $LABEL_PROV;
        
        echo '<h4>Indirizzo</h4>';
        parent::printTextFormField($FORM_INDIRIZZO, $LABEL_INDIRIZZO);
        parent::printTextFormField($FORM_CIVICO, $LABEL_CIVICO);
        parent::printTextFormField($FORM_CAP, $LABEL_CAP);
        parent::printTextFormField($FORM_CITTA, $LABEL_CITTA);
        parent::printSelectFormField($FORM_PROV, $LABEL_PROV, $this->province);  
    }
    
    
    
}
